<?php

/**
 * Grid component. Allow to manage and render data grids.
 * Grid component provides:
 *  - Paged output
 *  - List sorting
 *  - List filters
 *  - Mass actions on list items
 *  - Allow to use view helpers for items
 *
 * @category    Ext
 * @package     Grid
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Grid 
{
    /**
     *
     * @staticvar string
     */
    protected static $_defaultNamespace = 'grid';

    /**
     * Default partials
     *
     * @staticvar array
     */
    protected static $_defaultPartials = array(
        'table'         => 'grid/table.phtml',
        'row'           => 'grid/row.phtml',
        'cell'          => 'grid/cell.phtml',
        'header'        => 'grid/header.phtml',
        'filter'        => 'grid/filter.phtml',
        'checkBox'      => 'grid/check-box.phtml',
        'actions'       => 'grid/actions.phtml',
    );

    /**
     *
     * @staticvar array
     */
    protected static $_defaultPerPageOptions = array(
        'default' => 25,
        50,
        100
    );

    /**
     * Custom partials. May overload in child classes
     * 
     * @var array
     */
    protected $_partials = array();
    
    /**
     * 
     * @var string
     */
    protected $_actionsDelimiter = ' | ';
    
    /**
     * Default view helper
     *
     * @var string
     */
    protected $_viewHelper = 'Ext_View_Helper_Grid';
    
    /**
     *
     * @var ArrayObject
     */
    protected $_session;

    /**
     *
     * @var Zend_Controller_Request_Abstract
     */
    protected $_request;

    /**
     *
     * @var Zend_Paginator
     */
    protected $_paginator;
    
    /**
     *
     * @var array
     */
    protected $_perPageOptions;

    /**
     *
     * @var int 
     */
    protected $_perPage;

    /**
     *
     * @var array 
     */
    protected $_columns = array();

    /**
     * @var string
     */
    protected $_sortColumn;

    /**
     * @var string
     */
    protected $_sortDirection;
    
    /**
     *
     * @var bool
     */
    protected $_massActionsEnabled = false;

    /**
     *
     * @var bool
     */
    protected $_filtersEnabled = false;

    /**
     *
     * @var bool
     */
    protected $_headerEnabled = true;

    /**
     *
     * @var bool
     */
    protected $_paginationEnabled = true;
    /**
     *
     * @var string 
     */
    protected $_massActionsId = 'id';

    /**
     * Grid mass actions.
     * example:
     *      array(
     *          'delete' => 'Delete Products'
     *      )
     * @var array
     */
    protected $_massActions = array();

    /**
     *
     * @var Ext_Grid_Adapter_Abstract
     */
    protected $_adapter;

    /**
     *
     * @var Zend_View_Interface
     */
    protected $_view;

    /**
     *
     * @var int
     */
    protected $_currentPageNumber;

    /**
     *
     * @var string
     */
    protected $_gridName;

    public function __construct(Ext_Grid_Adapter_Abstract $adapter = null)
    {
        $this->init();
        
        if ($adapter) {
            $this->setAdapter($adapter);
        }

        $this->setSortColumn($this->getRequest()->getParam('sort'));
        $this->setSortDirection($this->getRequest()->getParam('dir'));
        $this->setCurrentPageNumber($this->getRequest()->getParam('page'));
        $this->setPerPage($this->getRequest()->getParam('per_page'));

        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');

        if ($this->getRequest()->getParam('reset_filter')) {
            $this->resetFilter();
            $redirector->gotoUrlAndExit($this->getView()->url(array('page' => 0, 'reset_filter' => null)));
            
        } elseif ($this->getRequest()->getParam('set_filter')) {

            $this->setFilter($this->getRequest()->getParam('filter'));            
            $redirector->gotoUrlAndExit($this->getView()->url(array('page' => 0, 'set_filter' => null)));
        }
    }

    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return Ext_Grid
     */
    public function setView(Zend_View_Interface $view = null)
    {
        $this->_view = $view;
        return $this;
    }

    /**
     * Retrieve view object
     *
     * @return Zend_View_Interface|null
     */
    public function getView()
    {
        if (null === $this->_view) {
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            $this->setView($viewRenderer->view);
        }

        return $this->_view;
    }

    /**
     * Retrieve request object
     *
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = Zend_Controller_Front::getInstance()->getRequest();
        }
        return $this->_request;
    }

    /**
     * Return adapter
     *
     * @return Ext_Grid_Adapter_Abstract
     */
    public function getAdapter()
    {
        if (!isset($this->_adapter)) {
            throw new Ext_Grid_Exception('Adapter not found');
        }
        return $this->_adapter;
    }

    /**
     * Set adapter
     *
     * @param Ext_Grid_Adapter_Abstract $adapter
     * @return Ext_Grid
     */
    public function setAdapter($adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Set Grid Columns
     *
     * @param array $columns
     * @return Ext_Grid
     */
    public function setColumns($columns)
    {
        $this->_columns = $columns;
        return $this;
    }

    /**
     * Add Grid Column
     *
     * @param string $name
     * @param array $options
     * @return Ext_Grid
     */
    public function addColumn($name, $options)
    {
        $this->_columns[$name] = $options;
        return $this;
    }

    /**
     * Remove column from columns array
     * 
     * @param string $name
     * @return Ext_Grid
     */
    public function removeColumn($name)
    {
        unset($this->_columns[$name]);
        return $this;
    }

    /**
     * Return columns array
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->_columns;
    }

    /**
     * Return column
     *
     * @param string $name
     * @return array
     */
    public function getColumn($name)
    {
        if (isset ($this->_columns[$name])) {
            return $this->_columns[$name];
        } 
        return null;        
    }

    /**
     * Set items count per page
     *
     * @param int|string $perPage
     * @return Ext_Grid
     */
    public function setPerPage($perPage)
    {
        if ($perPage) {
            $this->_perPage = $perPage;
            $session = $this->_getSession();
            $session->perPage = $perPage;
        }
        return $this;
    }

    /**
     * Return items count per page
     *
     * @return int|string
     */
    public function getPerPage()
    {
        $session = $this->_getSession();
        if (isset($session->perPage)) {
            return $session->perPage;
        } elseif (!$this->_perPage) {
            $perPageOptions = $this->getPerPageOptions();
            if ($perPageOptions) {
                if (isset($perPageOptions['default'])) {
                    $this->setPerPage($perPageOptions['default']);
                } else {
                    $this->setPerPage($perPageOptions[0]);
                }
            }
        }
        return $this->_perPage;
    }

    /**
     * Set current psge number
     *
     * @param int $page
     * @return Ext_Grid
     */
    public function setCurrentPageNumber($page)
    {
        if ($this->isPaginationEnabled()) {
            $this->_currentPageNumber = $page;
        }
        return $this;
    }

    /**
     * Return current page number
     *
     * @return int
     */
    public function getCurrentPageNumber()
    {
        if (isset($this->_currentPageNumber)) {
            return $this->_currentPageNumber;
        } 
        return 1;
    }

    /**
     * Set mass actions
     *
     * @param array $optrions
     * @return Ext_Grid
     */
    public function setMassActions($actions)
    {
        $this->_massActions = $actions;
        return $this;
    }

    /**
     * Return mass actions
     *
     * @return array
     */
    public function getMassActions()
    {
        return $this->_massActions;
    }

    /**
     * Enable mass actions
     *
     * @param bool $flag
     * @return Ext_Grid
     */
    public function enableMassAction($flag)
    {
        $this->_massActionsEnabled = $flag;
        return $this;
    }

    /**
     * Enable filters
     *
     * @param bool $flag
     * @return Ext_Grid
     */
    public function enableFilters($flag)
    {
        $this->_filtersEnabled = $flag;
        return $this;
    }

    /**
     * Enable pagination
     *
     * @param bool $flag
     * @return Ext_Grid
     */
    public function enablePagination($flag)
    {
        $this->_paginationEnabled = $flag;
        return $this;
    }

    /**
     * Is mass action enabled?
     *
     * @return bool
     */
    public function isMassActionEnabled()
    {
        return $this->_massActionsEnabled;
    }

    /**
     * Is filters enabled?
     *
     * @return bool
     */
    public function isFiltersEnabled()
    {
        return $this->_filtersEnabled;
    }

    /**
     * is pagination enabled?
     *
     * @return bool
     */
    public function isPaginationEnabled()
    {
        return $this->_paginationEnabled;
    }

    /**
     * Set default namespace
     *
     * @param string $name
     */
    public static function setDefaultNamespace($name)
    {
        self::$_defaultNamespace = $name;
    }

    /**
     * Return default namespace
     * 
     * @return string
     */
    public static function getDefaultNamespace()
    {
        return self::$_defaultNamespace;
    }

    /**
     * Set partial for cell
     *
     * @param string $viewScript
     */
    public static function setCellPartial($viewScript)
    {
        self::$_defaultPartials['cell'] = $viewScript;
    }

    /**
     * Set partial for row
     *
     * @param string $viewScript
     */
    public static function setRowPartial($viewScript)
    {
        self::$_defaultPartials['row'] = $viewScript;
    }

    /**
     * Set partial for header
     *
     * @param string $viewScript
     */
    public static function setHeaderPartial($viewScript)
    {
        self::$_defaultPartials['header'] = $viewScript;
    }

    /**
     * Set partial for table
     *
     * @param table $viewScript
     */
    public static function setTablePartial($viewScript)
    {
        self::$_defaultPartials['table'] = $viewScript;
    }

    /**
     *Set partial for checkbox
     *
     * @param string $viewScript
     */
    public static function setCheckBoxPartial($viewScript)
    {
        self::$_defaultPartials['checkBox'] = $viewScript;
    }

    /**
     * Set partial for text filter
     *
     * @param string $viewScript
     */
    public static function setFilterPartial($viewScript)
    {
        self::$_defaultPartials['filter'] = $viewScript;
    }

    /**
     * Return array partials
     *
     * @return array
     */
    public function getPartials()
    {        
        return array_merge(self::$_defaultPartials, $this->_partials);
    }

    /**
     * Set column name to sort
     *
     * @param string $columnName
     * @return Ext_Grid
     */
    public function setSortColumn($columnName)
    {
       if ($columnName) {
            $this->_sortColumn = $columnName;
            $session = $this->_getSession();
            $session->sortColumn = $columnName;
        }
        return $this;
    }

    /**
     * Return sort column name
     *
     * @return string
     */
    public function getSortColumn()
    {
        $session = $this->_getSession();
        if (isset($session->sortColumn)) {
            return $session->sortColumn;
        }
        return $this->_sortColumn;
    }

    /**
     * Set id to mass actions
     *
     * @param string $id
     * @return Ext_Grid
     */
    public function setMassActionsId($id)
    {
        $this->_massActionsId = $id;
        return $this;
    }

    /**
     * Return mass action id
     *
     * @return string
     */
    public function getMassActionsId()
    {
        return $this->_massActionsId;
    }

    /**
     * Set sort direction
     *
     * @param string $direction
     * @return Ext_Grid
     */
    public function setSortDirection($direction)
    {
       if ($direction) {
            $this->_sortDirection = $direction;
            $session = $this->_getSession();
            $session->sortDirection= $direction;
        }
        return $this;
    }

    /**
     * Return sort direction
     *
     * @return string
     */
    public function getSortDirection()
    {
        $session = $this->_getSession();
        if (isset($session->sortDirection)) {
            return $session->sortDirection;
        }
        return $this->_sortDirection;
    }

    /**
     * Set grid name
     *
     * @param string $name
     * @return Ext_Grid
     */
    public function setGridName($name)
    {
        $this->_gridName = $name;
        return $this;
    }

    /**
     * Return grid name
     *
     * @return string
     */
    public function getGridName()
    {
        if (!isset($this->_gridName)) {
            throw new Ext_Grid_Exception('Grid name not found');
        }
        return $this->_gridName;
    }

    /**
     * Set default count per page options
     * 
     * @param array $options
     * @return Ext_Grid
     */
    public static function setDefaultPerPageOptions($options)
    {
        self::$_defaultPerPageOptions = $options;
    }

    /**
     * Return default count per page options
     *
     * @return array
     */
    public static function getDefaultPerPageOptions()
    {
        return self::$_defaultPerPageOptions;
    }

    /**
     * Set count per page options
     *
     * @param array $options
     * @return Ext_Grid
     */
    public function setPerPageOptions($options)
    {
        $this->_perPageOptions = $options;
        if (isset($options['default'])) {
            $this->setPerPage($options['default']);
        } else {
            $this->setPerPage($options[0]);
        }
        return $this;
    }

    /**
     * Return count per page options
     *
     * @return array
     */
    public function getPerPageOptions()
    {
        if (isset($this->_perPageOptions)) {
            return $this->_perPageOptions;
        } 
        return self::$_defaultPerPageOptions;        
    }

    /**
     * Set filter options
     *
     * @param array|null $options
     * @return Ext_Grid
     */
    public function setFilter($filterOptions = null)
    {
        $session = $this->_getSession();
        if (isset($filterOptions)) {
            $session->filter = $filterOptions;
        } elseif (!isset($session->filter)) {
            $session->filter = array();
        }
        return $this;
    }

    /**
     * Return filter options
     *
     * @return array
     */
    public function getFilter()
    {
        $filterOptions = $this->_getSession()->filter;

        $filter = array();
        if (is_array($filterOptions)) {
            foreach($filterOptions as $fieldName => $filterValue) {
                $column = $this->getColumn($fieldName);
                if ($column) {
                    $filter[] = array(
                        'fieldName'         => $column['field'],
                        'filterValue'       => $filterValue,
                        'filterType'        => $column['filter'],
                        'filterCallback'    => isset($column['filter_callback']) ? $column['filter_callback'] : null,
                    );
                }
            }
        }
        return $filter;
    }

    /**
     * Return filter Value
     *
     * @param string
     * @return array
     */
    public function getFilterValue($name)
    {
        $filters = $this->getFilter();
        foreach ($filters as $filter) {
            if ($name == $filter['fieldName']) {
                return $filter['filterValue'];
            }
        }
    }

    /**
     * Reset filter options
     *
     * @return Ext_Grid
     */
    public function resetFilter()
    {
        $this->_getSession()->filter = array();

        return $this;
    }

    /**
     * Initialize form (used by extending classes)
     *
     * @return void
     */
    public function init()
    {
        foreach ($this->_columns as $key => &$column) {
            if (!isset($column['field']) && !is_numeric($key)) {
                $column['field'] = $key;
            }
        }
    }

    /**
     * Return paginator
     *
     * @return Zend_paginator
     */
    public function getPaginator()
    {
        if (!$this->_paginator) {
            $this->_paginator =  $this->getAdapter()->getPaginator($this);
        }
        return $this->_paginator;
    }
    
    /**
     * Return actions delimiter
     * 
     * @return string
     */
    public function getActionsDelimiter()
    {
        return $this->_actionsDelimiter;
    }
    /**
     * Render grid
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        if (null !== $view) {
            $this->setView($view);
        }

        return $this->getView()->{$this->_viewHelper}($this);
    }

    /**
     * Serialize as string
     *
     * Proxies to {@link render()}.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (Exception $e) {
            $message = "Exception caught by grid: " . $e->getMessage()
                     . "\nStack Trace:\n" . $e->getTraceAsString();
            trigger_error($message, E_USER_WARNING);

            return '';
        }
    }

    /**
     *
     * @return Zend_Session_Namespace
     */
    protected function _getSession()
    {
        if (!$this->_session) {
            $this->_session = new Zend_Session_Namespace(self::$_defaultNamespace . '-' . $this->getGridName());;
        }
        return $this->_session;
    }
}
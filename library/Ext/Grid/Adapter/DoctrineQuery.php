<?php

/**
 * Grid Doctrine Query adapter
 *
 * @category    Ext
 * @package     Grid
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Grid_Adapter_DoctrineQuery extends Ext_Grid_Adapter_Abstract
{
    /**
     * @param Doctrine_Query_Abstract $query
     */
    public function __construct(Doctrine_Query_Abstract $query)
    {
        $this->_query = $query;
    }

    /**
     *
     * @return Doctrine_Query_Abstract
     */
    public function getQuery()
    {
        if (!$this->_query) {
            $this->_query = Doctrine_Query::create();
        }
        return $this->_query;
    }

    /**
     * Set criteria
     *
     * @param Doctrine_Query_Abstract $crit
     * @return Ext_Grid_Adapter_DoctrineQuery
     */
    public function setQuery(Doctrine_Query_Abstract $crit)
    {
        $this->_query = $crit;
    }
    
    /**
     * Return paginator
     *
     * @return Zend_Paginator
     */
    public function getPaginator(Ext_Grid $grid)
    {
        $sort = $grid->getSortColumn();
        
        $query = $this->getQuery();

        if ($sort) {
            $dir = $grid->getSortDirection();
            if ($dir) {
                $query->addOrderBy($sort . ' ' . $dir);
            }
        }

        $alias = $query->getRootAlias();

        if (is_array($grid->getFilter())) {
            foreach ($grid->getFilter() as $filter) {
                $name  = $alias . '.' . $filter['fieldName'];
                $type  = $filter['filterType'];
                $value = $filter['filterValue'];
                $callback = $filter['filterCallback'];
                if ($callback) {
                    $callbackName = '_' . $filter['filterCallback'] . 'FilterCallback';
                    $grid->$callbackName($query, $value);
                } elseif ('text' == $type && $value !== '') {
                    $query->addWhere($name . ' LIKE ?', '%' . $value . '%');
                } elseif ('range' == $type) {
                    if (!empty($value['from'])) {
                        $query->addWhere($name . ' >= ?', $value['from']);
                    }
                    if (!empty($value['to'])) {
                        $query->addWhere($name . ' <= ?', $value['to']);
                    }
                } elseif ('select' == $type && $value !== '') {
                    $query->addWhere($name . ' = ?', $value);
                }
            }
        }

        $paginator = new Zend_Paginator(new ZFDoctrine_Paginator_Adapter_DoctrineQuery($query));
        $paginator->setItemCountPerPage($grid->getPerPage());
        $paginator->setCurrentPageNumber($grid->getCurrentPageNumber());
        
        return $paginator;
    }

    protected function _getSortDirection()
    {
        $allowedDirections = array(
            'asc', 'desc'
        );

        $session = $this->_getSession();
        if (isset($session->sortDirection)) {
            $sortDirection = $session->sortDirection;
        } else {
            $sortDirection = $this->_sortDirection;
        }
        $sortDirection = strtolower($sortDirection);

        if (!in_array($sortDirection, $allowedDirections)) {
            $sortDirection = 'desc';
        }

        return $sortDirection;
    }
}
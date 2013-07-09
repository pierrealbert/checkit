<?php

/**
 * Mail template
 *
 * @category    Ext
 * @package     Mail
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Mail_Template
{
    /**
     *
     * @var Zend_View
     */
    static protected $_view;

    protected $_data     = '';
    protected $_template = '';

    public function  __construct($template, $data = array())
    {
        $this->_data     = $data;
        $this->_template = $template;
    }

    static public function setView(Zend_View $view)
    {
        self::$_view = $view;
    }

    /**
     *
     * @return Zend_View
     */
    static public function getView()
    {
        if (!self::$_view) {
            self::$_view = new Zend_View();
        }
        return self::$_view;
    }

    public function getSubject()
    {
        return self::getView()->translate($this->_template);
    }

    public function getBody()
    {
        return $this->_renderBody($this->_template, $this->_data);
    }

    public function _renderBody($template, $data = array())
    {
        $data['template'] = $template;

        $view = self::getView();
        $view->assign($data);

        return $view->render('layout.phtml');
    }
}
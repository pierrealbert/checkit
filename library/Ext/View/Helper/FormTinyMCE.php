<?php

/**
 * Helper to generate a "tinyMCE" form element
 * 
 * @category Ext
 * @package  Ext_View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_FormTinyMCE extends Zend_View_Helper_FormElement
{
    /**
     * JS file location
     *
     * @var string
     */
    public $tinyMceJs = '/js/tiny_mce/tiny_mce.js';

    /**
     * The default number of rows for a textarea.
     *
     * @access public
     *
     * @var int
     */
    public $rows = 10;

    /**
     * The default number of columns for a textarea.
     *
     * @access public
     *
     * @var int
     */
    public $cols = 50;

    /**
     * Generates a 'tinyMCE' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formTinyMCE($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);

        extract($info);

        if ( !empty($attribs['tinyMCE']) ) {
            
            $this->view->JQuery()
                    ->addOnload('tinyMCE.init('. json_encode($attribs['tinyMCE']) .');');
            
            unset($attribs['tinyMCE']);
        }
        
        $disabled = '';
        if ($disable) {
            $disabled = ' disabled="disabled"';
        }
        
        /** add rows and cols */
        if (empty($attribs['rows'])) {
            $attribs['rows'] = (int) $this->rows;
        }
        
        if (empty($attribs['cols'])) {
            $attribs['cols'] = (int) $this->cols;
        }

        $this->view->JQuery()->addJavascriptFile($this->tinyMceJs);
        
        /** build the element */
        $xhtml = '<textarea name="' . $this->view->escape($name) . '"'
               . ' id="' . $this->view->escape($id) . '"'
               . $disabled
               . $this->_htmlAttribs($attribs) . '>'
               . $this->view->escape($value) . '</textarea>';
               
        return $xhtml;
    }
}

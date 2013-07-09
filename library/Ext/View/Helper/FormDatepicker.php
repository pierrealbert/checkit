<?php

/**
 * Datepicker form element
 *
 * @category    Ext
 * @package     Ext_View
 * @subpackage  Ext_View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_FormDatepicker extends Zend_View_Helper_FormText
{
    protected $_defaultSettings = array(
        'dateFormat' => 'yy-mm-dd',
        'changeYear' =>  1,
        'changeMonth'=>  0,
    );
    
    public function  __construct() 
    {
        $config = App::getConfig();
        
        if (isset($config->settings->formats->datepicker)) {
            $this->_defaultSettings['format'] = $config->settings->formats->datepicker;
        }
    }

    public function formDatepicker($name, $value = null, $attribs = null)
    {
        $attribs['class'] = 'input-text';

        $xhtml = parent::formText($name, $value, $attribs);

        extract($this->_getInfo($name, $value, $attribs));

        $this->_getInfo($name, $value, $attribs);

        $settings = array_merge($this->_defaultSettings, $attribs);

        $xhtml .= "
            <script type=\"text/javascript\">
                $(function() {
                    $('#{$this->view->escape($id)}').datepicker(" . json_encode($settings) . ");
                });
            </script>
        ";
        return $xhtml;
    }
}

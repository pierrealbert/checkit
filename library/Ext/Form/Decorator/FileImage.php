<?php

/**
 * File Image Element Decorator
 *
 * @category    Ext
 * @package     Ext_From
 * @subpackage  Decorator
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Form_Decorator_FileImage extends Zend_Form_Decorator_File
{
    /**
     * Render a form file
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }

        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            return $content;
        }
       
        $attribs = $this->getAttribs();
       
        $currImage = null;
        if (isset($attribs['currImage'])) {
            $currImage = $attribs['currImage'];            
            $this->getElement()->setAttrib('currImage', null);
        }

        $result = '';
        
        if ($currImage) {
            $result = $this->_imagePreview($currImage);
        }
        
        $result .= parent::render($content);

        return $result;
    }

    private function _imagePreview($path)
    {
        return '<div class="preview-image"><img src="' . $path . '" alt="" /></div>';
    }     
}

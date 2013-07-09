<?php

/**
 * AJAX loader
 *
 * @category    Ext
 * @package     View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_AjaxLoader extends Zend_View_Helper_HtmlElement
{
    public static $enabled = true;

    protected $_defaultArgs = array(
        'autoOpen'      => false,
        'draggable'     => false,
        'modal'         => true,
        'resizable'     => false,
        'closeOnEscape' => true,
        'bgiframe'      => false,
        'minHeight'     => 50,
        'width'         => 200
    );

    public static function disable()
    {
        self::$enabled = false;
    }

    public function ajaxLoader($id = 'ajax-loader', $args = array())
    {
        if (!self::$enabled) {
            return '';
        }
        $script = "
            $(document).ajaxSend(function() {
                $('#{$id}').dialog('open');
            });

            $(document).ajaxStop(function() {
                $('#{$id}').dialog('close');
            });
        ";

        $args = array_merge($this->_defaultArgs, $args);

        $args['dialogClass'] = $id;

        $result = $this->view->dialogContainer(
            $id,
            '<p>' . $this->view->translate('loading') . '</p>',
            $args
        );

        $this->view->JQuery()->addOnload($script);

        return $result;
    }
}
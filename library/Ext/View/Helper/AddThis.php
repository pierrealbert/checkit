<?php

/**
 * Display AddThis.com sharing buttons
 * @see http://www.addthis.com/
 *
 * @category    Ext
 * @package     View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_AddThis extends Zend_View_Helper_Abstract
{
    protected $_defaultConfig = array(
        'data_track_clickback' => true
    );

    protected $_addThisJS = 'http://s7.addthis.com/js/250/addthis_widget.js';

    /**
     * @param int $size
     * @param string $pubId Account identifier
     * @param array $config
     * @return string
     */
    public function addThis($pubId = 'beetsoft', $size = 16, $config = array())
    {
        $config = array_merge($this->_defaultConfig, $config);

        $jsonConfig = Zend_Json::encode($config);

        $size = (int)$size;

        $html = <<<EOS
<div class="addthis_toolbox addthis_default_style addthis_{$size}x{$size}_style">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_compact"></a>
</div>
EOS;

        $this->view->headScript()->appendScript("var addthis_config = {$jsonConfig}");
        $this->view->headScript()->appendFile("{$this->_addThisJS}#pubid={$pubId}");

        return $html;
    }
}

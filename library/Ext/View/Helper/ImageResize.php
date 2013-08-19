<?php

class Ext_View_Helper_ImageResize extends Zend_View_Helper_Abstract
{
    public function imageResize($width, $height, $image, $attribs=array())
    {
        $attr_string = '';

        if (is_array($attribs) && $attribs) {
            foreach ($attribs as $name => $value) {
                $attr_string .= " {$name}=\"{$value}\"";
            }
        }

        $image = "<img src=\"/image/by/w/{$width}/h/{$height}/i/" . str_replace("/", '_', $image) . "\" alt=\"{$width}x{$height}_{$image}\"{$attr_string} />";

        return $image;
    }
}
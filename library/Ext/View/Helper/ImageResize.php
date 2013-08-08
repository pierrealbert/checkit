<?php

class Ext_View_Helper_ImageResize extends Zend_View_Helper_Abstract
{
    public function imageResize($width, $height, $image)
    {
        $image = "<img src=\"/image/by/w/{$width}/h/{$height}/i/" . str_replace("/", '_', $image) . "\" alt=\"{$image}_{$width}x{$height}\" />";

        return $image;
    }
}
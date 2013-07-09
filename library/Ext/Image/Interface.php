<?php

/**
 * Image Interface
 *
 * @category    Ext
 * @package     Ext_Image
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
interface Ext_Image_Interface
{
    public function open($filename);
    
    public function save($destination, $newName = null);

    public function resize($frameWidth = null, $frameHeight = null);

    public function setKeepAspectRatio($flag);

    public function setConstrainOnly($flag);

    public function setKeepFrame($flag);
}

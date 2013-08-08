<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @category    Ext
 * @package     Ext_View
 * @subpackage  Ext_View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_ImageUrl extends Ext_View_Helper_FileUrl
{
    /**
     *
     * @var Ext_Image_Interface
     */
    protected $_imageProcessor;
   
    /**
     *
     * @param string $image
     * @param int $width
     * @param int $height
     * @return string 
     */
    public function imageUrl($image, $width = null, $height = null)
    {       
        $imageUrl = $this->fileUrl($image);

        if (!$width && !$height) {
            return $imageUrl;
        }

        if (!$height) {
            $height = $width;
        }

        $pathInfo = pathinfo($image);

        $resizedImage = trim($pathInfo['dirname'], '.')
            . '/' . $pathInfo['filename']
            . '_' . $width
            . '_' . $height
            . '.' . $pathInfo['extension'];

        $image        = trim($image, '/');
        $resizedImage = trim($resizedImage, '/');
        
        $imagePath         = $this->getBasePath() . '/' . $image;
        $resizedImagePath  = $this->getBasePath() . '/' . $resizedImage;
        $resizedImageUrl   = $this->getBaseUrl()  . '/' . $resizedImage;

        $image = $this->getBasePath() . $image;

        if (!is_file($resizedImagePath)) {
            $this->getImageProcessor()
                    ->setKeepFrame(false)
                    ->open($imagePath)
                    ->resize($width, $height)
                    ->save($resizedImagePath);
        }

        return $resizedImageUrl;
    }

    /**
     *
     * @return Ext_Image_Interface
     */
    public function getImageProcessor()
    {
        if (!$this->_imageProcessor) {
            $this->_imageProcessor = new Ext_Image();
        }
        return $this->_imageProcessor;
    }

    /**
     *
     * @param Ext_Image_Interface $image
     * @return Ext_View_Helper_ImageUrl
     */
    public function setImageProcessor(Ext_Image_Interface $image)
    {
        $this->_imageProcessor = $image;
        return $this;
    }
}
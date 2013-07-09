<?php

/**
 * Image resize component
 *
 * @category    Ext
 * @package     Image
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Image implements Ext_Image_Interface
{
    protected $_fileName        = null;

    protected $_fileType        = null;

    protected $_fileMimeType    = null;

    protected $_imageHandler    = null;
    
    protected $_keepFrame       = true;

    protected $_keepAspectRatio = true;

    protected $_constrainOnly   = false;
    
    protected $_backgroundColor = array(255, 255, 255);

    protected static $_callbacks = array(
        IMAGETYPE_GIF  => array('output' => 'imagegif',  'create' => 'imagecreatefromgif'),
        IMAGETYPE_JPEG => array('output' => 'imagejpeg', 'create' => 'imagecreatefromjpeg'),
        IMAGETYPE_PNG  => array('output' => 'imagepng',  'create' => 'imagecreatefrompng'),
        IMAGETYPE_XBM  => array('output' => 'imagexbm',  'create' => 'imagecreatefromxbm'),
        IMAGETYPE_WBMP => array('output' => 'imagewbmp', 'create' => 'imagecreatefromxbm'),
    );

    public function  __construct($filename = null)
    {
        if (null !== $filename) {
            $this->open($filename);
        }
    }

    public function setConstrainOnly($flag)
    {
        $this->_constrainOnly = $flag;
        return $this;
    }

    public function setKeepAspectRatio($flag)
    {
        $this->_keepAspectRatio = $flag;
        return $this;
    }

    public function setKeepFrame($flag)
    {
        $this->_keepFrame = $flag;
        return $this;
    }

    public function setBackgroundColor($color = array())
    {
        $this->_backgroundColor = $color;
        return $this;
    }

    public function open($filename)
    {
        $this->_fileName = $filename;
        
        $this->_getMimeType();

        $this->_imageHandler = call_user_func($this->_getCallback('create'), $this->_fileName);
        return $this;
    }

    public function save($destination, $newName = null)
    {
        if ($newName) {            
            $fileName = $destination . "/" . $newName;
        } else {
            $info        = pathinfo($destination);
            $fileName    = $destination;
            $destination = $info['dirname'];
        }

        if (!is_writable($destination) ) {
            throw new Ext_Image_Exception("Unable to write file into directory '{$destination}'. Access forbidden.");
        }

        call_user_func($this->_getCallback('output'), $this->_imageHandler, $fileName);
    }

    public function resize($frameWidth = null, $frameHeight = null)
    {
        $srcWidth  = imagesx($this->_imageHandler);
        $srcHeight = imagesy($this->_imageHandler);
        $srcX      = 0;
        $srcY      = 0;
        $dstX      = 0;
        $dstY      = 0;

        if (!$this->_keepFrame) {
            if (null === $frameWidth) {
                $frameWidth = round($frameHeight * ($srcWidth / $srcHeight));
            } elseif (null === $frameHeight) {
                $frameHeight = round($frameWidth * ($srcHeight / $srcWidth));
            }
        } else {
            if (null === $frameWidth) {
                $frameWidth = $frameHeight;
            } elseif (null === $frameHeight) {
                $frameHeight = $frameWidth;
            }
        }

        // define coordinates of image inside new frame
        $dstWidth  = $frameWidth;
        $dstHeight = $frameHeight;

        if ($this->_constrainOnly && ($frameWidth >= $srcWidth) && ($frameHeight >= $srcHeight)) {
            return $this;
        }

        if ($this->_keepAspectRatio) {
            // keep aspect ratio
            if ($srcWidth / $srcHeight >= $frameWidth / $frameHeight) {
                $dstHeight = round(($dstWidth / $srcWidth) * $srcHeight);
            } else {
                $dstWidth = round(($dstHeight / $srcHeight) * $srcWidth);
            }
        }

        // define position in center (TODO: add positions option)
        $dstY = round(($frameHeight - $dstHeight) / 2);
        $dstX = round(($frameWidth - $dstWidth) / 2);

        // get rid of frame (fallback to zero position coordinates)
        if (!$this->_keepFrame) {
            $frameWidth  = $dstWidth;
            $frameHeight = $dstHeight;
            $dstY = 0;
            $dstX = 0;
        }
               
        $newImage = imagecreatetruecolor($frameWidth, $frameHeight);

        if ($this->_fileType == IMAGETYPE_PNG || $this->_fileType == IMAGETYPE_GIF) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage,true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, $dstX, $dstY, $srcX, $srcY, $transparent);
        } else {
            $this->_fillBackground($newImage);
        }

        imagecopyresampled($newImage, $this->_imageHandler, $dstX, $dstY, $srcX, $srcY, $dstWidth, $dstHeight, $srcWidth, $srcHeight);
        
        $this->_imageHandler = $newImage;

        return $this;
    }

    public function _getMimeType()
    {
        if ($this->_fileType) {
            return $this->_fileType;
        } else {
            $imagesize = getimagesize($this->_fileName);
            $this->_fileType     = $imagesize[2];
            $this->_fileMimeType = image_type_to_mime_type($this->_fileType);
            return $this->_fileMimeType;
        }
    }

    protected function _fillBackground(&$image)
    {
        list($r, $g, $b) = $this->_backgroundColor;

        $color = imagecolorallocate($image, $r, $g, $b);

        return imagefill($image, 0, 0, $color);
    }

    private function _getCallback($callbackType, $fileType = null)
    {
        if (null === $fileType) {
            $fileType = $this->_fileType;
        }
        if (empty(self::$_callbacks[$fileType])) {
            throw new Ext_Image_Exception('Unsupported image format.');
        }
        if (empty(self::$_callbacks[$fileType][$callbackType])) {
            throw new Ext_Image_Exception('Callback not found.');
        }
        return self::$_callbacks[$fileType][$callbackType];
    }
}

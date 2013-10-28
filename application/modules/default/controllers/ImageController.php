<?php

class ImageController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();
 
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function byAction()
    {
        $width  = (int) $this->_getParam('w');

        $height = (int) $this->_getParam('h');

        $image  = str_replace("_", '/', $this->_getParam('i'));

        $path = APPLICATION_PATH . "/../public";

        $imagePath = $path . $image;

        $image_info = pathinfo($image);

        $resizedImagePath = "{$path}{$image_info['dirname']}/thumb/";

        if (!is_dir($resizedImagePath) && !mkdir($resizedImagePath, 0777, true)) {
            throw new Zend_Controller_Action_Exception("Can't create dirictory \"{$resizedImagePath}\"");
        }

        $resizedImagePath .= "{$image_info['filename']}_{$width}x{$height}.{$image_info['extension']}";

        $request_modified = 0;

        if ($mod_since = $this->getRequest()->getHeader('If-Modified-Since')) {
 
            $request_modified = explode(';', $mod_since);
            $request_modified = strtotime($request_modified[0]);
        }

        if (!is_file($imagePath)) {
            $this->returnHeader('404 Not Found');

        } else if (is_file($resizedImagePath) 
            && $this->getFiletime($resizedImagePath) > 0 
            && $this->getFiletime($resizedImagePath) <= $request_modified
        ) {
            $this->returnHeader('304 Not Modified');

        } else {

            if (!is_file($resizedImagePath) 
                && !self::resize($imagePath, $resizedImagePath, $width, $height)
            ) {
                throw new Zend_Controller_Action_Exception("Can't create thumb image.");
            }

            $imagedata = file_get_contents($resizedImagePath);

            $mimetype = $this->getMIMEType($resizedImagePath);

            $this->getResponse()->setHeader('Content-type', $mimetype);

            $expires = 60 * 60 * 24 * 3;
            $exp_gmt = gmdate("D, d M Y H:i:s", time() + $expires) . " GMT";
            $mod_gmt = gmdate("D, d M Y H:i:s", $this->getFiletime($resizedImagePath)) . " GMT";
 
            $this->getResponse()->setHeader('Expires', $exp_gmt, true);
            $this->getResponse()->setHeader('Last-Modified', $mod_gmt, true);
            $this->getResponse()->setHeader('Cache-Control', 'public, max-age=' . $expires, true);
            $this->getResponse()->setHeader('Pragma', '!invalid', true);
            $this->getResponse()->setHeader('Content-Length', strlen($imagedata), true);
            $this->getResponse()->setHeader('ETag', md5($imagedata), true);

            echo $imagedata;
        }
    }

    private function getMIMEType($image)
    {
        $imgsize = getimagesize($image);

        return $imgsize[ 'mime' ];
    }

    private function getFiletime($image)
    {
        $time = filemtime($image);

        if (false === $time) return 0;

        return $time;
    }

    private function returnHeader($message)
    {
        $header = (php_sapi_name() == 'cgi') ? 'Status: ' : 'HTTP/1.1 ';

        header($header . $message);
        exit();
    }

    private static function _getCropDelta($oldWidth, $oldHeight, $newWidth, $newHeight) {
        $src_aspect  = $oldHeight/$oldWidth;
        $dest_aspect = $newHeight/$newWidth;

        if ($dest_aspect > $src_aspect) {
            $scaleK = $newHeight / $oldHeight;
        } else {
            $scaleK = $newWidth / $oldWidth;
        }

        $x_delta = ($oldWidth - ($newWidth / $scaleK));
        $y_delta = ($oldHeight - ($newHeight / $scaleK));

        $oldWidth = round($oldWidth - $x_delta);
        $oldHeight = round($oldHeight - $y_delta);

        $x_delta = round($x_delta / 2);
        $y_delta = round($y_delta / 2);

        if ($dest_aspect < $src_aspect) {
            $y_delta = 0;
        }

        return array($x_delta, $y_delta, $oldWidth, $oldHeight);
    }

    private static function resize($sourceImage, $destinationImage, $width, $height)
    {
        $image_info = getimagesize($sourceImage);

        if (!$image_info) return false;

        $image = self::loadImage($sourceImage, $image_info[2]);

        if (!$image) return false;

        $old_height = $image_info[0];
        $old_width = $image_info[1];
/*
		if (($old_height  >= $old_width) && ($old_height > $width)) {
            $new_height = (float)$width;                       
            $new_width = (int)(($new_height/$old_height ) * $old_width);
        } elseif (($old_width  >= $old_height) && ($old_width > $height)) {
            $new_width = (float)$height;                      
            $new_height = (int)(($new_width/$old_width) * $old_height) ;
        } else {
			$new_height = $old_height;
			$new_width = $old_width;
		}
*/

        $new_width  = $width;
        $new_height = $height;
        list($x_delta, $y_delta, $old_width, $old_height) = self::_getCropDelta($old_width, $old_height, $new_width, $new_height);

        $new_image = imagecreatetruecolor($new_height, $new_width);

        //if (!imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_height, $new_width, $old_height, $old_width)) return false;
        if (!imagecopyresampled($new_image, $image, 0, 0, $x_delta, $y_delta, $new_height, $new_width, $old_height, $old_width)) return false;
		
        return self::saveImage($new_image, $destinationImage, $image_info[2]);
    }
    public static function loadImage($fileName, $imageFormat)
    {
        switch ($imageFormat) {
            case IMG_GIF:
                $image = imagecreatefromgif($fileName);
                break;
            case IMG_JPG:
                $image = imagecreatefromjpeg($fileName);
                break;
            case IMG_PNG:
                $image = imagecreatefrompng($fileName);
                break;
            default:
                return false;
                break;
        }

        return $image;
    }

    private static function saveImage($image, $fileName, $imageFormat)
    {
        switch ($imageFormat) {
            case IMG_GIF:
                return imagegif($image, $fileName);
                break;
            case IMG_JPG:
                return imagejpeg($image, $fileName);
                break;
            case IMG_PNG:
                return imagepng($image, $fileName);
                break;
        }
        return false;
    }
}
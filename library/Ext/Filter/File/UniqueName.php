<?php

/**
 * Filter
 *
 * @category    Ext
 * @package     Ext_Filter
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Filter_File_UniqueName implements Zend_Filter_Interface
{
    /**
     * @var string
     */
    private $_targetDir;

    private $_patternName;

    /**
     * @return string
     */
    public function getTargetDir()
    {
        return $this->_targetDir;
    }

    /**
     * @param string $dir
     * @return Ext_Filter_File_UniqueName
     */
    public function setTargetDir($dir)
    {
        $dir = rtrim($dir, '\/');
        $dir .= DIRECTORY_SEPARATOR;

        $this->_targetDir = $dir;

        return $this;
    }

    /**
     * Constructor for filter
     *
     * @param array $options Options to set
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } elseif (is_string($options)) {
            $options = array('targetDir' => $options);
        } elseif (!is_array($options)) {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Invalid options argument provided to filter');
        }

        if (isset($options['targetDir']) && $options['targetDir']) {
            $this->setTargetDir($options['targetDir']);
        } else {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Invalid target directory argument provided to filter');
        }

        if (isset($options['patternName']) && $options['patternName']) {
            $this->_patternName = $options['patternName'];
        }
    }

    /**
     * Finds unique filename and move source file to need directory with unique filename
     *
     * @param string $value full filename
     * @return string
     */
    public function filter($value)
    {
        if (!$value) {
            return $value;
        }

        $uniqueName = $this->getUniqueName($value);

        // TODO: handle renaming errors
        $result = rename($value, $uniqueName);

        return $uniqueName;
    }

    /**
     * Finds unique filename
     *
     * @param string $value full filename
     * @return string
     */
    public function getUniqueName($value)
    {
        $fullFilePath   = $value;
        $fileInfo       = pathinfo($fullFilePath);
        $fileName       = $fileInfo['filename'];
        $targetDir       = $this->getTargetDir();
        $fileExtension  = '.' . $fileInfo['extension'];

        $fileName = (!$this->_patternName) ?
            $this->_translitString($fileName) :
            $this->_translitString($this->_patternName);

        $tempFileBaseName = $fileName . $fileExtension;

        if (file_exists($targetDir . $tempFileBaseName)) {

            $i = 0;

            while (file_exists($targetDir . $tempFileBaseName)) {
                $tempFileBaseName = $fileName . "_" . $i++ . $fileExtension;
            }
        }
        return $targetDir . $tempFileBaseName;
    }

    /**
     * Translit input string
     *
     * @param string $value filename
     * @return string
     * @todo Properly hanle Unicode characters
     */
    private function _translitString($value)
    {
        $fromSymbol = array(" ", "'", '"', "`", '/', '\\', ':', '*', '?', '<', '>', '|');
        $toSymbol = array("_", "", "", "", "", "", "", "", "", "", "", "",);
  
        $value = str_replace($fromSymbol, $toSymbol, $value);

        return $value;
    }
}

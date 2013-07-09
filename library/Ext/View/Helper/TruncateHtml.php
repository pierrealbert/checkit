<?php

/**
 * @example
 *
 * $truncate = new Ext_View_Helper_TruncateHtml();
 * $truncate->truncateHtml($string, 10, ' more...', true, true);
 *
 * @category    Ext
 * @package     Ext_View
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_TruncateHtml extends Zend_View_Helper_Abstract
{
    /**
     * Remove tables from html.
     *
     * @var bool
     */
    protected $_removeTable = true;
    
    /**
     * Maximum length of the string.
     *
     * @var int 
     */
    protected $_length = 100;

    /**
     * Ending
     * 
     * @var string 
     */
    protected $_ending;

    /**
     * Encoding
     *
     * @var string
     */
    protected $_encoding = 'UTF-8';

    /**
     * Whether to truncate exact or on whole words.
     * 
     * @var bool 
     */
    protected $_exact = false;


    /**
     * Whether treat string as HTML (considering HTML tags).
     *
     * @var bool
     */
    protected $_html = true;
    
    /**
     * Sets the value of the private property 'ending'
     *
     * @access public
     * @param string $ending Property value
     * @return Ext_Filter_Truncate
     */
    public function setEnding($ending)
    {
        $this->_ending = $ending;

        return $this;
    }

    /**
     * Gets the value of the private property 'ending'
     *
     * @access public
     * @return string
     */
    public function getEnding()
    {
        return $this->_ending;
    }

    /**
     * Sets the value of the private property $_length
     *
     * @param int $length Property value.
     * @return Ext_Filter_Truncate
     */
    public function setLength($length)
    {
        $this->_length = $length;
        return $this;
    }

    /**
     * Gets property _length
     *
     * @return int Property value.
     */
    public function getLength()
    {
        return $this->_length;
    }

    /**
     * Sets the value of the private property $_exact
     *
     * @param bool $flag Property value.
     * @return Ext_Filter_Truncate
     */
    public function setExact($flag)
    {
        $this->_exact = $flag;

        return $this;
    }

    /**
     * Gets the value of the private property $_exact
     *
     * @return bool Property value.
     */
    public function getExact()
    {
        return $this->_exact;
    }

    /**
     * Sets the value of the private property $_html
     *
     * @param bool $flag Property value.
     * @return Ext_Filter_Truncate
     */
    public function setHtml($flag = true)
    {
        $this->_html = $flag;

        return $this;
    }

    /**
     * Gets the value of the private property $_html
     *
     * @return bool Property value.
     */
    public function getHtml()
    {
        return $this->_html;
    }

    /**
     * Sets the value of the private property $_removeTable
     *
     * @param bool $flag Property value.
     * @return Ext_Filter_Truncate
     */
    public function setRemoveTable($flag = true)
    {
        $this->_removeTable = $flag;

        return $this;
    }

    /**
     * Gets the value of the private property $_removeTable
     *
     * @see $_removeTable
     * @return bool Property value.
     */
    public function getRemoveTable()
    {
        return $this->_removeTable;
    }

    /**
     * Truncates text.
     *
     * @param $string
     * @param integer $length Length of returned string, including ellipsis.
     * @param string $ending
     * @param boolean $exact If false, $text will not be cut mid-word
     * @param bool $html
     * @param bool $removeTable
     *
     * @return string Truncated string.
     */
    public function truncateHtml($string, $length = 100, $ending = null, $exact = false, $html = true, $removeTable = true)
    {
        if ($ending !== null) {
            $this->setEnding($ending);
        }
        
        if ($html) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (mb_strlen(preg_replace('/<.*?>/', '', $string), $this->_encoding) <= $length) {

                return $string;
            }

            if ($removeTable) {
                $string = trim(preg_replace('#<table.*<\/table>#s', '', $string));
            }

            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $string, $lines, PREG_SET_ORDER);
            $total_length = 0;
            $open_tags = array();
            $truncate = '';
            foreach ($lines as $line_matches) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matches[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matches[1])) {
                        // do nothing
                        // if tag is a closing tag (f.e. </b>)
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matches[1], $tag_matches)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matches[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag (f.e. <b>)
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matches[1], $tag_matches)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matches[1]));
                    }
                    // add html-tag to $truncated text
                    $truncate .= $line_matches[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matches[2]), $this->_encoding);
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matches[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entities_length <= $left) {
                                $left--;
                                $entities_length += mb_strlen($entity[0], $this->_encoding);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= mb_substr($line_matches[2], 0, ($left + $entities_length), $this->_encoding);
                    // maximum length is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matches[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($string, $this->_encoding) <= $length) {
                return $string;
            } else {
                $truncate = mb_substr($string, 0, $length, $this->_encoding);
            }
        }
        // if the words shouldn't be cut in the middle...
        if ($exact) {
            // ...search the last occurrence of a space...
            $spacePos = strrpos($truncate, ' ');
            if (isset($spacePos)) {
                $rest = mb_substr($truncate, $spacePos);

                foreach ($open_tags as $key => $tag) {
                    if (strpos($rest, $open_tags[$key]) !== false) {
                        unset($open_tags[$key]);
                    }
                }
                $truncate = mb_substr($truncate, 0, $spacePos, $this->_encoding);
            }
        }

        if (!empty($truncate)) {
            $truncate .= $this->getEnding();
        }

        if ($html) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }
}

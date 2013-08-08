<?php

/**
 * View helper for building <a />
 *
 * @category    Ext
 * @package     View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_ActionLink extends Zend_View_Helper_Abstract
{
    /**
     * @param array $url
     * @param string $title
     * @param array $attribs
     * @return string
     * @throws Ext_Exception 
     */
    public function actionLink($url = array(), $title, $attribs = array())
    {
        if ( empty($url) || empty($title) ) {
            throw new Ext_Exception('Invalid arguments');
        }

        $isAllowed = $this->_isAllowed($url);

        $link = '<a title="' . ($isAllowed
            ? (strpos($title, '"') === false ? $title : '')
            : $this->view->t('action_not_allowed')) 
                . '" href="' . ($isAllowed 
                    ? $this->view->url($url, 'default', true)
                    : 'javascript:void(0);') . '"';
        if (empty($attribs['class']) && !$isAllowed) {
            $link .= ' class="action-link disabled"';
        }
        if (!empty($attribs['class'])) {
            $link .= ' class="' . ($isAllowed ? '' : 'action-link disabled ') . $attribs['class'] . '"';
        }
        if (!empty($attribs['id'])) {
            $link .= ' id="' . $attribs['id'] . '"';
        }
        if (!empty($attribs['target'])) {
            $link .= ' target="' . $attribs['target'] . '"';
        }
        $text = isset($attribs['text']) ? $attribs['text'] : $title;
        $link .= '>' . $text . '</a>';

        return $link;
    }

    /**
     * @param array $url
     */
    protected function _isAllowed($url = array())
    {
        return true;
    }
}
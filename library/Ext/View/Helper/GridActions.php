<?php

/**
 * View helper for building action links
 *
 * @category    Ext
 * @package     View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_GridActions extends Zend_View_Helper_Abstract
{
    /**
     * @param array $buttons
     *      format: array(
     *          'title' => string
     *          'url'   => array - controller, action, e.g.
     *          'icon'  => string
     *          'class' => string (Optional)
     *      )
     * @param bool $isHidden
     * @return string
     * @throws Ext_Exception
     */
    public function gridActions($buttons = array(), $isHidden = false)
    {
        if (!is_array($buttons) || !count($buttons)) {
            throw new Ext_Exception('Missing dataTablesAction argument: $buttons');
        }

        if ($isHidden) {
            return '';
        }

        $xhtml = array();
        $xhtml[] = '<div class="btn-group">';

        foreach ($buttons as $button) {
            $xhtml[] = $this->view->actionLink(
                $button['url'],
                $this->view->t($button['title']),
                array(
                    'text'  => (!empty($button['icon']) ? '<i class="' . $button['icon'] . '"></i>' : ''),
                    'class' => (!empty($button['class']) ? ' ' . $button['class'] : '')
                )
            );
        }

        $xhtml[] = '</div>';

        return join(PHP_EOL, $xhtml);
    }
}
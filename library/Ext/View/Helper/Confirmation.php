<?php

/**
 * Displays confirmation alert
 *
 * Add .delete class and title attribute to the dangerous links:
 *
 * <a href="/delete" class="delete" title="Delete all">Delete</a>
 * Run the helper once from the view.
 * $this->confirmation();
 *
 * @category    Ext
 * @package     Ext_View
 * @subpackage  Helper_JQuery
 * @since       1.0
 * @version     $Revision: 1.0 $ 
 */
class Ext_View_Helper_Confirmation extends Zend_View_Helper_Abstract
{
    /**
     * Confirmation
     *
     * @param string $onload Attach onload to which element?
     * @param string $message Confirm message
     */
    public function confirmation($selector = '.delete', $message = 'Are you sure you want to delete this record?')
    {
        $selector = (string) $selector;

        $message = $this->view->translate($message);

        $this->view->JQuery()->addOnload("
            $('{$selector}').click(function() {
                var title = $(this).attr('title');
                if (!title) {
                    title = '{$message}';
                } else {
                    title = title + '? {$message}';
                }
                return confirm(title);
            });");
    }
}

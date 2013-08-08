<?php

class View_Helper_UiTabs extends Zend_View_Helper_FormElement
{
    public function uiTabs(Array $tabs, $content, $attribs = null)
    {
        $tabLinks = array();
        foreach ($tabs as $tab) {
            if (!isset($tab['title']) || !isset($tab['ref'])) {
                contitue;
            }
            $tabLinks[] = '<a href="' . $tab['ref'] . '">' . $this->view->translate($tab['title'])  . '</a>';
        }
        $htmlTabs = $this->view->htmlList($tabLinks, false, null, false);

        $javascript = '<script type="text/javascript">' . self::EOL
                        . '    $(function() {' . self::EOL
                        . '        var tabs = $(\'#tabs\').tabs();' . self::EOL
                        . '        tabs.find(\'.ui-tabs-panel\').each(function(index, el) {' . self::EOL
                        . '             if ($(el).find(\'.errors\').length) {' . self::EOL
                        . '                 tabs.tabs(\'select\', index);' . self::EOL
                        . '                 return false;' . self::EOL
                        . '             }' . self::EOL
                        . '        });' . self::EOL
                        . '    });' . self::EOL
                        . '</script>' . self::EOL;


        return '<div id="tabs">' . self::EOL . $htmlTabs . $content . '</div>' . self::EOL . $javascript;
    }
}

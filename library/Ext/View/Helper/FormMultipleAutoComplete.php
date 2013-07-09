<?php

/**
 * @see ZendX_JQuery_View_Helper_UiWidget
 */
require_once "ZendX/JQuery/View/Helper/UiWidget.php";

/**
 * jQuery Autocomplete View Helper
 *
 * @category    Ext
 * @package     Ext_View
 * @subpackage  Ext_View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_FormMultipleAutoComplete extends ZendX_JQuery_View_Helper_UiWidget
{
    /**
     * Builds an AutoComplete ready input field.
     *
     * This view helper builds an input field with the {@link Zend_View_Helper_FormText} FormText
     * Helper and adds additional javascript to the jQuery stack to initialize an AutoComplete
     * field. Make sure you have set one out of the two following options: $params['data'] or
     * $params['url']. The first one accepts an array as data input to the autoComplete, the
     * second accepts an url, where the autoComplete content is returned from. For the format
     * see jQuery documentation.
     *
     * @link   http://docs.jquery.com/UI/Autocomplete
     * @throws ZendX_JQuery_Exception
     * @param  String $id
     * @param  String $value
     * @param  array $params
     * @param  array $attribs
     * @return String
     */
    public function formMultipleAutoComplete($id, $value = null, array $params = array(), array $attribs = array())
    {
        $attribs = $this->_prepareAttributes($id, $value, $attribs);

        if (!isset($params['source'])) {
            if (isset($params['data'])) {
                $params['source'] = $params['data'];
                unset($params['data']);
            } else {
                require_once "ZendX/JQuery/Exception.php";
                throw new ZendX_JQuery_Exception(
                    "Cannot construct AutoComplete field without specifying 'source' field, "
                );
            }
        }
        if (!is_array($params['source'])) {
            require_once "ZendX/JQuery/Exception.php";
            throw new ZendX_JQuery_Exception(
                "To construct MultipleAutoComplete field specifyed 'source' field should be an array."
            );
            
        }
        $sourceData = $params['source'];
        unset($params['source']);

        $params = ZendX_JQuery::encodeJson($params);
        
        $jsOnKeydown = '';
        $jsOnKeydown .= 'function( event ) {';
        $jsOnKeydown .= '    if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "autocomplete" ).menu.active ) {';
        $jsOnKeydown .= '        event.preventDefault();';
        $jsOnKeydown .= '    }';
        $jsOnKeydown .= '}';
        
        $jsSourceCallback = '';
        $jsSourceCallback .=         'function (request, response) {';
        $jsSourceCallback .=         '   response( ';
        $jsSourceCallback .= sprintf('   $.ui.autocomplete.filter(%s, request.term.split(", ").pop()))', ZendX_JQuery::encodeJson($sourceData));
        $jsSourceCallback .= '}';
        
        $jsOnAutocompleteFocus = '';
        $jsOnAutocompleteFocus .= 'function () {';
        $jsOnAutocompleteFocus .= '    return false;';
        $jsOnAutocompleteFocus .= '}';

        $jsOnAutocompleteSelect = '';
        $jsOnAutocompleteSelect .= 'function (event, ui) {';
        $jsOnAutocompleteSelect .= '    var terms = this.value.split(", ");';
        $jsOnAutocompleteSelect .= '    terms.pop();';
        $jsOnAutocompleteSelect .= '    terms.push( ui.item.value );';
        $jsOnAutocompleteSelect .= '    terms.push( "" );';
        $jsOnAutocompleteSelect .= '    this.value = terms.join( ", " );';
        $jsOnAutocompleteSelect .= '    return false;';
        $jsOnAutocompleteSelect .= '}';


        

        $js = '';
        $js .= sprintf('%s("#%s")', ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(), $attribs['id']);
        $js .= sprintf('    .bind("keydown", %s)', $jsOnKeydown);
        $js .= sprintf('    .autocomplete(%s)', $params);
        $js .= sprintf('    .autocomplete("option", "source", %s)', $jsSourceCallback);
        $js .= sprintf('    .bind("autocompletefocus", %s)', $jsOnAutocompleteFocus);
        $js .= sprintf('    .bind("autocompleteselect", %s);', $jsOnAutocompleteSelect);

        $this->jquery->addOnLoad($js);

        return $this->view->formText($id, $value, $attribs);
    }
}

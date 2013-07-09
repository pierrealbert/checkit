
function submitForm(formId, action) {
    var form = $('#' + formId);
    if (form) {
        form.append('<input type="hidden" name="' + action + '" value="' + action + '" />');
        form.submit();
    }
}

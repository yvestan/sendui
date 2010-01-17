function jelix_ckeditor_default(textarea_id, form_id) {
    var textarea_name = document.getElementById(textarea_id).getAttribute('name');
    CKEDITOR.replace(textarea_name);
}

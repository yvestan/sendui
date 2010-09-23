/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};

// configuration de ckeditor pour les messages
CKEDITOR.editorConfig = function( config )
{
    config.toolbar = 'html_message';

    config.toolbar_html_message =
    [
        ['Preview','Source'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['Maximize'],
        ['SpecialChar'],
        '/',
        ['Styles','Format'],
        ['Bold','Italic','Underline','Strike'],
        ['TextColor','BGColor'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['NumberedList','BulletedList','-','Indent','Blockquote'],
        ['Link','Unlink'],
        ['Image','Table','HorizontalRule'],
    ];
};

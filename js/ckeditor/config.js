/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	// config.width = 750;
	config.toolbarStartupExpanded = true;
	config.toolbar = [
						['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    					['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
						['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
						['Styles','Format','Font','FontSize','-','SelectAll','RemoveFormat'],
    					['TextColor','BGColor'],
				 	  ];
};

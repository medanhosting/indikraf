/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

 CKEDITOR.editorConfig = function( config ) {
 	config.toolbarGroups = [
 		{ name: 'document', groups: [ 'mode', 'doctools', 'document' ] },
 		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
 		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
 		{ name: 'forms', groups: [ 'forms' ] },
 		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
 		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
 		{ name: 'links', groups: [ 'links' ] },
 		{ name: 'insert', groups: [ 'insert' ] },
 		{ name: 'colors', groups: [ 'colors' ] },
 		{ name: 'tools', groups: [ 'tools' ] },
 		{ name: 'styles', groups: [ 'styles' ] },
 		{ name: 'about', groups: [ 'about' ] },
 		{ name: 'others', groups: [ 'others' ] }
 	];

 	config.removeButtons = 'Scayt,Checkbox,Radio,TextField,Textarea,Select,Form,Button,ImageButton,HiddenField,RemoveFormat,Outdent,Indent,BidiLtr,BidiRtl,Language,Flash,SelectAll,Find,Replace,Source,Templates,Save,NewPage,Preview,Print,PageBreak,Smiley,Anchor,Unlink';
 };

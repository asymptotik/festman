<?php

require_once dirname(__FILE__).'/../../objects/IControl.php';

class TextEditorScriptControl implements IControl
{
	function __construct() {

	}
	
	public function render()
	{
echo <<<EOF
<!-- TinyMCE -->
<script type="text/javascript" src="../script/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "specific_textareas",
		editor_selector : "mceEditor",
		theme : "advanced",
		//theme : "simple",

		// General options
		plugins : "safari,pagebreak,style,advlink,iespell,preview,searchreplace,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,charmap,|,undo,redo",
		theme_advanced_buttons2 : "forecolor,|,sub,sup,|,nonbreaking,link,unlink,cleanup,removeformat,|,help,code,preview,visualchars",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		relative_urls : true,
		document_base_url : "http://www.communikey.us/festival2009/",
		
		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->
EOF;
	}
}
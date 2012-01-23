<?php
/*
 Plugin Name: cmkyf
 Plugin URI: http://www.communikey.us/cmkyf
 Description: A festival pluggin for Communikey Festival of Electronic Arts
 Version: 1.0
 Author: Rick Boykin
 Author URI: http://www.communikey.us
 */
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require dirname(__FILE__).'/widgets/CmkyfRssWidget.php';
require dirname(__FILE__).'/widgets/CmkyfMailingListWidget.php';

// add_action('admin_menu', 'my_plugin_menu');
register_activation_hook(__FILE__,'festman_install');

/**
  * Register widgets.
  *
  * Calls 'widgets_init' action after the Hello World widget has been registered.
  */
add_action('widgets_init', 'register_cmkyf_widgets');

$festman_db_version = "1.0";

function register_cmkyf_widgets() {
  	register_widget('Cmkyf_Widget_RSS');
  	register_widget('Cmkyf_Widget_Mailing_List');
}
  
function my_plugin_menu() {
	add_menu_page('Festman Config', 'Festman', 'administrator', 'fm-top-level-handle', 'fm_acts_page', null, 40);
	add_submenu_page( 'fm-top-level-handle', 'Festival Acts', 'Acts', 'administrator', '../wp-content/plugins/cmkyfestman/festman/admin/program_item_selector.php');
	add_submenu_page( 'fm-top-level-handle', 'Festival Classes', 'Classes', 'administrator', '../wp-content/plugins/cmkyfestman/festman/admin/program_item_selector.php');
	add_submenu_page( 'fm-top-level-handle', 'Festival Films', 'Films', 'administrator', '../wp-content/plugins/cmkyfestman/festman/admin/program_item_selector.php');
	add_submenu_page( 'fm-top-level-handle', 'Festival Panels', 'Panels', 'administrator', '../wp-content/plugins/cmkyfestman/festman/admin/program_item_selector.php');
	add_submenu_page( 'fm-top-level-handle', 'Festival Workshops', 'Workshops', 'administrator', '../wp-content/plugins/cmkyfestman/festman/admin/program_item_selector.php');
	add_submenu_page( 'fm-top-level-handle', 'Festival Installations', 'Installations', 'administrator', '../wp-content/plugins/cmkyfestman/festman/admin/program_item_selector.php');
	add_submenu_page( 'fm-top-level-handle', 'Festival Locations', 'Locations', 'administrator', '../wp-content/plugins/cmkyfestman/festman/admin/location_selector.php');
	add_submenu_page( 'fm-top-level-handle', 'Festival Events', 'Events', 'administrator', '../wp-content/plugins/cmkyfestman/festman/admin/event_selector.php');
}

function fm_acts_page() {
	$_SESSION['current_program_item_class'] = 'Act';
}

function fm_classes_page() {
	$_SESSION['current_program_item_class'] = 'Klass';
}

function fm_films_page() {
	$_SESSION['current_program_item_class'] = 'Film';
}

function fm_panels_page() {
	$_SESSION['current_program_item_class'] = 'Panel';
}

function fm_workshops_page() {
	$_SESSION['current_program_item_class'] = 'Workshop';
}

function fm_installations_page() {
	$_SESSION['current_program_item_class'] = 'Installation';
}

function fm_locations_page() {

}

function fm_events_page() {

}


function festman_install()
{
	global $wpdb;
	global $cmky_db_version;

	add_option("festman_db_version", $festman_db_version);
			
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	$table_name = $wpdb->prefix . "fm_collateral";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `Collateral_Id` int(11) NOT NULL auto_increment,
		  `MimeType_Id` int(11) NOT NULL,
		  `Caption` varchar(1024) default NULL,
		  `Location` varchar(255) NOT NULL,
		  `Name` varchar(128) NOT NULL,
		  PRIMARY KEY  (`Collateral_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

		dbDelta($sql);

		//$insert = "INSERT INTO " . $table_name .
        //    " (time, name, text) " .
        //    "VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','" . $wpdb->escape($welcome_text) . "')";
		//$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_collaterallocation";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `CollateralLocation_Id` int(11) NOT NULL auto_increment,
		  `Name` varchar(1024) default NULL,
		  `Location` varchar(255) default NULL,
		  PRIMARY KEY  (`CollateralLocation_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

		dbDelta($sql);

		//$insert = "INSERT INTO " . $table_name .
        //    " (time, name, text) " .
        //    "VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','" . $wpdb->escape($welcome_text) . "')";
		//$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_event";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `Event_Id` int(11) NOT NULL auto_increment,
		  `Name` varchar(256) default NULL,
		  `Description` varchar(16384) default NULL,
		  `StartTime` datetime NOT NULL,
		  `EndTime` datetime NOT NULL,
		  `Location_Id` int(11) NOT NULL,
		  `Program_Id` int(11) NOT NULL,
		  PRIMARY KEY  (`Event_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

		dbDelta($sql);

		//$insert = "INSERT INTO " . $table_name .
        //    " (time, name, text) " .
        //    "VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','" . $wpdb->escape($welcome_text) . "')";
		//$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_fileextension";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `FileExtension_Id` int(11) NOT NULL auto_increment,
		  `Extension` varchar(32) NOT NULL,
		  `MimeType_Id` int(11) NOT NULL,
		  PRIMARY KEY  (`FileExtension_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21;';

		dbDelta($sql);

		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (1, 'jpg', 1)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (2, 'jpeg', 1)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (3, 'jpe', 1)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (4, 'png', 2)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (5, 'gif', 3)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (6, 'mpeg', 4)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (7, 'mpg', 4)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (8, 'mpe', 4)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (9, 'mpv', 4)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (10, 'vbs', 4)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (11, 'mpegv', 4)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (12, 'avi', 5)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (13, 'mp3', 6)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (14, 'doc', 7)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (15, 'pdf', 8)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (16, 'rtf', 9)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (17, 'ogg', 10)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (18, 'html', 11)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (19, 'txt', 12)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (FileExtension_Id, Extension, MimeType_Id) VALUES (20, 'wav', 13)";
		$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_location";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `Location_Id` int(11) NOT NULL auto_increment,
		  `Name` varchar(256) default NULL,
		  `Description` varchar(16384) default NULL,
		  `Url` varchar(256) default NULL,
		  `UrlText` varchar(128) default NULL,
		  `MapUrl` varchar(1024) default NULL,
		  `MapUrlText` varchar(128) default NULL,
		  `Address` varchar(64) default NULL,
		  `City` varchar(32) default NULL,
		  `State` varchar(32) default NULL,
		  `ZipCode` varchar(16) default NULL,
		  PRIMARY KEY  (`Location_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

		dbDelta($sql);

		//$insert = "INSERT INTO " . $table_name .
        //    " (time, name, text) " .
        //    "VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','" . $wpdb->escape($welcome_text) . "')";
		//$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_mimetype";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `MimeType_Id` int(11) NOT NULL auto_increment,
		  `Type` varchar(32) NOT NULL,
		  `SubType` varchar(32) NOT NULL,
		  `Description` varchar(255) default NULL,
		  PRIMARY KEY  (`MimeType_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14;';

		dbDelta($sql);

		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (1, 'image', 'jpeg', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (2, 'image', 'png', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (3, 'image', 'gif', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (4, 'video', 'mpeg', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (5, 'video', 'x-msvideo', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (6, 'audio', 'mpeg', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (7, 'application', 'msword', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (8, 'application', 'pdf', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (9, 'application', 'rtf', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (10, 'application', 'ogg', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (11, 'text', 'html', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (12, 'text', 'plain', NULL)";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (MimeType_Id, Type, SubType, Description) VALUES (13, 'audio', 'wav', NULL)";
		$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_objecttable";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `ObjectTable_Id` int(11) NOT NULL auto_increment,
		  `ClassName` varchar(64) default NULL,
		  `TableName` varchar(64) default NULL,
		  `IdName` varchar(64) default NULL,
		  PRIMARY KEY  (`ObjectTable_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13;';

		dbDelta($sql);

		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (1, 'Event','" . $wpdb->prefix . "fm_Event','Event_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (2, 'Collateral','" . $wpdb->prefix . "fm_Collateral', 'Collateral_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (3, 'Location','" . $wpdb->prefix . "fm_Location', 'Location_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (4, 'CollateralLocation','" . $wpdb->prefix . "fm_CollateralLocation', 'CollateralLocation_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (5, 'Object_Collateral','" . $wpdb->prefix . "fm_Object_Collateral', 'Object_Collateral_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (6, 'Program','" . $wpdb->prefix . "fm_Program', 'Program_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (7, 'ProgramItem','" . $wpdb->prefix . "fm_ProgramItem', 'ProgramItem_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (8, 'Program_ProgramItem','" . $wpdb->prefix . "fm_Program_ProgramItem', 'Program_ProgramItem_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (9, 'RelatedPerson','" . $wpdb->prefix . "fm_RelatedPerson', 'RelatedPerson_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (10, 'MimeType','" . $wpdb->prefix . "fm_MimeType', 'MimeType_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (11, 'Extension_MimeType','" . $wpdb->prefix . "fm_Extension_MimeType', 'Extension_MimeType_Id')";
		$results = $wpdb->query( $insert );
		$insert = "INSERT INTO " . $table_name . " (ObjectTable_Id, ClassName, TableName, IdName) VALUES (12, 'ObjectTable','" . $wpdb->prefix . "fm_ObjectTable', 'ObjectTable_Id')";
		$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_object_collateral";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `Object_Collateral_Id` int(11) NOT NULL auto_increment,
		  `ObjectTable_Id` int(11) NOT NULL,
		  `Object_Id` int(11) NOT NULL,
		  `Collateral_Id` int(11) NOT NULL,
		  `SortOrder` int(11) default NULL,
		  `IsDefault` int(11) default NULL,
		  PRIMARY KEY  (`Object_Collateral_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

		dbDelta($sql);

		//$insert = "INSERT INTO " . $table_name .
        //    " (time, name, text) " .
        //    "VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','" . $wpdb->escape($welcome_text) . "')";
		//$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_program";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `Program_Id` int(11) NOT NULL auto_increment,
		  `Name` varchar(256) default NULL,
		  `Description` varchar(16384) default NULL,
		  PRIMARY KEY  (`Program_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

		dbDelta($sql);

		//$insert = "INSERT INTO " . $table_name .
        //    " (time, name, text) " .
        //    "VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','" . $wpdb->escape($welcome_text) . "')";
		//$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_programitem";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `ProgramItem_Id` int(11) NOT NULL auto_increment,
		  `Name` varchar(256) default NULL,
		  `Description` varchar(16384) default NULL,
		  `Url` varchar(256) default NULL,
		  `ObjectClass` varchar(16) NOT NULL,
		  `Origin` varchar(128) default NULL,
		  `UrlText` varchar(128) default NULL,
		  PRIMARY KEY  (`ProgramItem_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

		dbDelta($sql);

		//$insert = "INSERT INTO " . $table_name .
        //    " (time, name, text) " .
        //    "VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','" . $wpdb->escape($welcome_text) . "')";
		//$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_program_programitem";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `Program_ProgramItem_Id` int(11) NOT NULL auto_increment,
		  `Position` int(11) default NULL,
		  `StartTime` datetime default NULL,
		  `Program_Id` int(11) NOT NULL,
		  `ProgramItem_Id` int(11) NOT NULL,
		  PRIMARY KEY  (`Program_ProgramItem_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

		dbDelta($sql);

		//$insert = "INSERT INTO " . $table_name .
        //    " (time, name, text) " .
        //    "VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','" . $wpdb->escape($welcome_text) . "')";
		//$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "fm_relatedperson";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = 'CREATE TABLE ' . $table_name . ' (
		  `RelatedPerson_Id` int(11) NOT NULL auto_increment,
		  `Name` varchar(256) default NULL,
		  `Description` varchar(16384) default NULL,
		  `Url` varchar(256) default NULL,
		  `UrlText` varchar(128) default NULL,
		  `Role` varchar(16) default NULL,
		  `ProgramItem_Id` int(11) NOT NULL,
		  PRIMARY KEY  (`RelatedPerson_Id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

		dbDelta($sql);

		//$insert = "INSERT INTO " . $table_name .
        //    " (time, name, text) " .
        //    "VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','" . $wpdb->escape($welcome_text) . "')";
		//$results = $wpdb->query( $insert );
	}
}

?>
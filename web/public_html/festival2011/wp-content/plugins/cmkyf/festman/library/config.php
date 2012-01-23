<?php
require_once dirname(__FILE__).'/../objects/FestmanDb.php';

$fm_dbhost = 'localhost';
$fm_dbuser = 'communik_cmkyf';
$fm_dbpass = 'yess0ngs';
$fm_dbname = 'communik_cmkyf2011';
global $fm_db;
$fm_db = FestmanDb::getInstance("wp_fm_");
?>
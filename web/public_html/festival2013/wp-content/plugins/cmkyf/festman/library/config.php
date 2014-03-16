<?php
require_once dirname(__FILE__).'/../objects/FestmanDb.php';
global $fm_db;
$fm_db = FestmanDb::getInstance("wp_fm_");
?>
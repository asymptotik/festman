<?php
$fm_conn = mysql_connect($fm_dbhost, $fm_dbuser, $fm_dbpass) or die
                     ('Error connecting to mysql');
mysql_select_db($fm_dbname);

?>
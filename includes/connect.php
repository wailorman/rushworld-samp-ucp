<?php
require_once("includes/config.php");

mysql_connect($config['db_server'].':'.$config['db_port'], $config['db_user'], $config['db_pass']);
mysql_select_db($config['db_name']);
?>
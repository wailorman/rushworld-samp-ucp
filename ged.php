<?php

	$login = $_GET['login'];
	$type = $_GET['type'];
	
	echo file_get_contents("http://rushworld.ru/fwu/out.php?login=".$login."&type=".$type);

	?>
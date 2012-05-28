<?php
	if (hash_session($_SESSION['login']) != $_SESSION['hash_sess'] && $_SESSION){
		session_destroy();
		echo "<p>Попытка взлома чужого аккаунта. Ваши данные уже отправлены администратору.</p>";
		exit();
	}
	
	/*mysql_query ("set character_set_client='cp1251'"); 
	mysql_query ("set character_set_results='cp1251'"); 
	mysql_query ("set collation_connection='cp1251_general_ci'"); */
	
	mysql_query ("set character_set_client='utf8'"); 
	mysql_query ("set character_set_results='utf8'"); 
	mysql_query ("set collation_connection='uft8_general_ci'");
	
	//error_reporting(0);
?>
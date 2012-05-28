<?php
	
	require_once("includes/config.php");
	require_once("includes/functions.php");
	require_once("includes/connect.php");
	require_once("includes/adds.php");
	
	session_start();
	
	
	
	/*if ($_POST['enter']) { 
		
		Все данные, введенные в форму отправляются через сессию, затем
		отправляются на doLogin.php
		
		$_SESSION['user_login'] = $_POST['user_login'];
		$_SESSION['user_pass'] = $_POST['user_pass'];
		header("Location: ".$config['site_addr']."/dologin.php");
	}*/
	
	
	$bt = microtime(true);
	//echo get_some_user_data("ConnectedTime", "wailorman");
	//echo search_last_id("ucp_users");
	//echo get_user_data("Tarantula");
	
	//print_r(get_all_user_data("wailorman"));
	//get_all_user_data("wailorman");
	
	echo "<br>".find_rank_str(2, 2, 0)."<br>";
	
	
	$at = microtime(true);
	echo number_format(($at - $bt), 4, ".", "")." секунд";
	//echo get_some_user_data("Key", "ROMAN_MIR");
	
	
	
	
	
?>

	<form action="dologin.php" method="POST">
		<input type="text" name="user_login">
		<input type="password" name="user_pass">
		<input type="submit" name="enter">
	</form>
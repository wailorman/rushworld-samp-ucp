<?php
	require_once("includes/config.php");
	require_once("includes/functions.php");
	require_once("includes/connect.php");
	require_once("includes/adds.php");
	
	session_start();
	/*print_r($_POST);
	if ($_POST['enter']) {
		
		$sql = mysql_query("SELECT * FROM ".$config['tbl_users']);
		$row = mysql_num_rows($sql);
		
		while ($row = mysql_fetch_assoc($sql)) {
			if ($row['login'] == $_POST['user_login']) {
				$ud['login'] = $row['login']; //user data
				$ud['pass'] = $row['pass'];
			}else{
				echo '<p>Этого пользователя нема в базьке</p>';
			}
		}
		if (check_reg_user($_POST['login'])) {
			//Такой пользователь уже зарегистрирован
		}
		
		if (md5($_POST['user_pass']) == $ud['pass']) {
			echo '<p>Я ВОШЕЛ ННА!</p>';
			$_POST['login'] = $ud['login'];
			
		}
		
		
	}else{
		echo '<p>Тупой! Жми enter! Не пущу!</p>';
	}*/
	
	echo "<p>На подходе к условию</p>";
	if (get_some_user_data("Key", $_POST['user_login']) == $_POST['user_pass']) {
		echo "<p>Первое условие выполнено</p>";
		if (check_reg_user($_POST['user_login'])){
			echo "<p>Кажется, такой пользователь зарегистрирован. Приступаю к процессу авторизации</p>";
			//если он зарегистрирован....
			update_user_data($_POST['user_login']);
			if (authorize($_POST['user_login'], $_POST['user_pass'])) {
				echo '<p>Авторизован</p>';
			}else{
				echo "<p>Нет, тебя я не зарегистрирую</p>";
				//Не авторизован
			}
		}else{
			echo "<p>Второе условие пошло</p>";
			reg_user($_POST['user_login'], $_POST['user_pass']);
			echo "<p>После регистрации</p>";
			if ($reg == 1){
				echo '<p>Авторизован</p>'; //Авторизация при первом входе
			}elseif($reg == 2){
				echo '<p>SQL-запрос завершился ошибкой</p>';
			}elseif($reg == 3){
				echo '<p>Пароль в INI-файле не совпадает с паролем, введенным в атрибуты функции.</p>';
			}elseif($reg == 4){
				echo '<p>Такой пользователь уже зарегистрирован.</p>';
			}else{
				echo '<p>Пользователя не получилось зарегать</p>';
			}
			if (update_user_data($_POST['user_login'])) {
				echo '<p>Дата юзера обновлена</p>';
			}else{
				echo '<p>Дата юзера не обновлена</p>';
			}
			if (authorize($_POST['user_login'], $_POST['user_pass'])) {
				echo '<p>Авторизован</p>'; //Авторизация при первом входе
				
				$_SESSION['login'] = $_POST['login'];
				$_SESSION['hash_sess'] = hash_sesion($_POST['login']);
			}else{
				echo '<p>Пользователь не авторизован</p>';
			}
		}
	}else{
		echo "<p>Неверный пароль</p>";
		echo "<p>Пароли не сошлись. Вот что у меня получилось. 1: ".get_some_user_data("Key", $_POST['user_login'])."   2: ".$_POST['user_pass']." И этот логин: ".$_POST['user_login']."</p>";
	}
	
	
	
	
?>
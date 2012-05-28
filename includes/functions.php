<?php
	require_once("config.php");
	require_once("connect.php");
	require_once("adds.php");
	
	
	function check_reg_user($login) { //Проверка, зарегистрирован ли такой пользователь или нет.
		/*
		TRUE - Такой пользователь есть.
		FALSE - Такого пользователя еще не зарегистрировано.
		*/
		settype($login, "string");		
		global $config;
				
		
		$sq = "SELECT * FROM ".$config['tables']['users']." WHERE `login`='".$login."'";
		$sql = mysql_query($sq);
		$row = mysql_num_rows($sql);
		
		while ($row = mysql_fetch_assoc($sql)) {
			if ($row['login'] == $login) { 	//Если проверяемая ячейка соответствует логину, то
				return true;				//говорим, что такой пользователь уже есть
					
			}else{
				return false;
					
			}
		}
		return false;
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/	
	function search_last_id($table) { //Функция для поиска последнего ID в выбранной таблице
		global $config;
		
		$sq = "SELECT `".$config['fields']['id']."` FROM ".$config['tables']['users']." ORDER BY ".$config['fields']['id']." ASC";
		if ($sql = mysql_query($sq)) {
			$row = mysql_num_rows($sql);		
			while ($row = mysql_fetch_assoc($sql)) { 
				$i = $row[$config['fields']['id']]; 			//Каждый раз записываем новый ID в $i. Т.к. у нас идет 
			}													//филтрация поля 'id' в алфавитном порядке, то последний ID
			if ($i == false) { /*Если не выдал никаких  ||  */	//в любом случае будет последний ID.
				$i = 0;		   /*ID, говорим, что       ||  */	//Выводит только последний ID. При создании новой записи к
			}				   /*Последний ID - 0       ||  */	//значению этой функции еще нужно будет прибавить 1
		}else{
			$i = 1; //Если скрипт не может даже подключиться к БД, возвращаем 1
		}
		return $i;
		
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/		
	function reg_user($login, $pass) { 	//Функция быстрой регистрации пользователя
		/*
	---	1 - Пользователь успешно зарегистрирован.
	---	2 - SQL-запрос завершился ошибкой.
	---	3 - Пароль в INI-файле не совпадает с паролем, введенным в атрибуты функции.
	---	4 - Такой пользователь уже зарегистрирован.
		*/
	
		settype($login, "string");
		settype($pass, "string");
		global $config;
		$hash_pass = md5($login);	//Хешируем данный нам пароль
		$last_id = search_last_id($config['tables']['users']) + 1;	//Высчитываем последний ID
		$sql_reg = "INSERT INTO ".$config['tables']['users']." (`".$config['fields']['id']."`, `".$config['fields']['login']."`, `".$config['fields']['pass']."`, `".$config['fields']['hash_pass']."`, `".$config['ini']['Key']."`) 
		VALUES ('".$last_id."', '".$login."', '".$pass."', '".$hash_pass."', '".$pass."')";
			if (!check_reg_user($login) && strlen($login) >= 1 && strlen($pass) >= 1) {	//Проверяем длинну выданных данных, т.к. все значения должны быть длиннее 1 символа. В противном случае нет никакого смысла их регистрировать 
				if (get_some_user_data("Key", $login) == $pass) {	//Есди выданный пароль соответсвует тому, что в INI-файле, пускаем его на дальнейшие действия					
					if (mysql_query($sql_reg)) {										
						return 1; //Если удалось зарегистрировать пользователя, возвращаем 1
					}else{
						return 2; 
					}
				}else{
					return 3; 
				}
			}else{
				return 4;
			}		
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/		
	function get_file($file){	//Функция для быстрого скачивания файла.
		error_reporting (0); //Запрещаем выводить ошибки 
		global $config;
		settype($file, "string");
		
		
		
		if ($config['connection_type'] == 'sftp') {
			
			$sftp_connection = ssh2_connect($config['ftp_server'], $config['sftp_port']);
			ssh2_auth_password($sftp_connection, $config['ftp_login'], $config['ftp_pass']);
			$sftp_connect = ssh2_sftp($sftp_connection);
			if (@$content = file_get_contents("ssh2.sftp://".$sftp_connect.$config['sftp_homedir'].$file)) {
				return $content;
			}else{
				return false; //Если загрузить данные не удалось, возвращаем FALSE
			}
		}elseif($config['connection_type'] == 'ftp'){
			if (@$content = file_get_contents("ftp://".$config['ftp_login'].":".$config['ftp_pass']."@".$config['ftp_server'].$file)) {
				return $content;
			}else{
				return false; //Если загрузить данные не удалось, возвращаем FALSE
			}
		}
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/		
	function get_user_data($login, $mode = false){ 	//Возвращает содержимое INI файла с данными пользователя
		global $config;								//Моды - fwr (когда выводится массив с заголовками и содержанием)
		settype($login, "string");
		
		
		$data = get_file($config['accounts_dir'].$login.".ini");
		
			if ($mode == "fwr") {
				$ini = get_user_data($login, "");
				settype($ini, "string");
				$ex = explode("\n", $ini);
				trim($ex);
				$i = 0;		

				while ($ex[$i] == true) {
					$ch[$i] = explode("=", $ex[$i]);
					$i++;
				}
				
				$data = $ch;
			}
			
		return $data;
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/		
	function get_some_user_data($type, $login) { //Возвращает определенные данные пользователя, которые мы указали в $type
		global $config;		
		session_start();		
		
		if ((comp_date() - $_SESSION['update_time']) > $config['interval']) { //Если мы не обновляли данные пользователя больше, чем Х минут
			
			
			update_user_data($login); 	//Обновляем данные в БД
			get_some_user_data($type, $login); 	//Рекурсивно вызываем эту же функцию
		}elseif((comp_date() - $_SESSION['update_time']) < $config['interval'] && check_reg_user($login)){ //Если мы обновляли данные пользователя менее, чем Х минут назад
			
			$sq = "SELECT `".$type."` FROM `".$config['tables']['users']."` WHERE `".$config['fields']['login']."`='".$login."'";
			$sql = mysql_query($sq);
			$row = mysql_num_rows($sql);
			
			while ($row = mysql_fetch_assoc($sql)) {
				return trim($row[$type]); 	//Возвращаем значение. Без пробелов.
			}			
		}else{
			$ini = get_user_data($login, "");
			settype($ini, "string");
			settype($type, "string");
			settype($login, "string");
			
			$spaces = explode("\n", $ini);	//Разделение на пробелы. Содержимое каждой ячейки массива будет приблизительно такое: "Level=1"
			
			$i = 0; //Обнуляем счетчик
			while ($spaces[$i] == true) { //Выполняем цикл до тех пор, пока ячейки будут что-то содержать.
				$data[$i] = explode("=", $spaces[$i]); //Разделяем каждую ячейку на две части. В первой под-ячейке (0) будет наименование переменной, а во второй (1) - ее значение
				$i++;
			}
			
			$i = 0;
			while ($data[$i] == true) {
				if ($data[$i][0] == $type) { //Ищем ту переменную, которая нам нужна, затем возвращаем ее значение
					return trim($data[$i][1]);				
				}
				$i++;
			}
			
		}
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/	
	function update_user_data($login) {	//Обновляет MySQL данные пользователя
			/*
			Коды ошибок:
		---	1 - Обновление данных пользователей прошло успешно.
		---	2 - SQL-запрос завершился ошибкой.
		---	3 - Такого пользователя не существует.
			*/
	
		settype($login, "string");
		global $config;
		
		session_start();
		
			$ini = get_user_data($login, "");
			settype($ini, "string");
			$ex = explode("\n", $ini);
			trim($ex);
			$i = 0;		
	
			while ($ex[$i] == true) {
				$ch[$i] = explode("=", $ex[$i]);
				$i++;
			}
			
	
			/*Обновление данных*/
			
			$i = 0;
			$uret = "UPDATE ".$config['tables']['users']." SET ";
			
			while ($ch[$i]) {
				if (!$ch[$i+1]) {
					$uret = $uret.'`'.$ch[$i][0].'`'."="."'".$ch[$i][1]."'";
				}else{
					$uret = $uret.'`'.$ch[$i][0].'`'."="."'".$ch[$i][1]."'".", ";
				}
				$i++;
			}
			
			$uret = $uret." WHERE `login`='".$login."'";

			
			
			$check = check_reg_user($login);
			if ($check == true) {
				if (mysql_query($uret)) {
					$_SESSION['update_time'] = comp_date(); //Записываем, когда последний раз обновили инфу
					$_SESSION['update_time_f'] = date("H:i");
					return 1;					
					
				}else{
					return 2;
				}
			}else{
				return 3;
			}
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function authorize($login, $pass) {
		/*
			Перед выполнения вы должны открыть сессию. session_start();
		*/
		
		settype($login, "string");
		settype($pass, "string");
		global $config;
		
		
		$sq = "SELECT `".$config['fields']['login']."`, `".$config['fields']['pass']."` FROM ".$config['tables']['users']." WHERE `".$config['fields']['login']."`='".$login."'";
		$sql = mysql_query($sq);
		$row = mysql_num_rows($sql);
		
		while ($row = mysql_fetch_assoc($sql) or die('mysql_error')) {
			if (up_md5(get_some_user_data("Key", $login)) == $pass) {
				echo "<p>authorize - 1</p>";
				
				session_start();
				$_SESSION['login'] = $login;
				$_SESSION['hash_pass'] = hash_session($login);
				
				return true;
			}else{
				return false;
			}
		}
		
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function hash_text($text){ //Специальный метод хеширования
		global $config;
		
		$hash = $text.(sqrt($config['char'] - ($config['char'] * 45) / sqrt($config['char'] + 8)));
		return md5($hash);
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function pstv_int($int) { //Делаем число положительным
		
		settype($int, "integer");
		
		$int = $int * $int;
		$int = sqrt($int);
		return $int;
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function hash_session($login) { //Метод хеширования сессии
		return hash_text($login);
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/	
	function hash_server($text) { //Метод хеширования сервера
		return $text;
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function comp_date(){ //Дата, время в едином числе
		return date("Ymdhi");
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function get_all_user_data ($login) {
		$data = get_user_data($login, "");
		$data = explode("\n", $data);
		
			$i = 0;
			while ($data[$i] == true) {
				$data2 = explode("=", $data[$i]);
				$data3[$data2[0]] = trim($data2[1]);
				$i++;
			}
		$data = false;
		$data2 = false;
		return $data3;
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function count_stage($stage) { //Подсчет стажа. Перевод из часов в дни
		if ($stage > 24) {
			$days = number_format(($stage / 24), 0, ".", "");
			return $days." дней";
		}elseif($stage < 24){
			return $stage." часов";
		}elseif($stage == false || $stage == 0){
			return "0 часов";
		}
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function find_rank_str($rank, $member, $leader) {
		global $config;
		
		if ($leader > 0) {
			$fraction = $leader;
		}elseif($member > 0 && $leader <= 0){
			$fraction = $member;
		}else{
			$fraction = $member;
		}
		
		
		$sq = "SELECT * FROM ".$config['tables']['ranks']." WHERE `fraction`='".$fraction."' AND `rank`='".$rank."'";
		$sql = mysql_query($sq);
		$row = mysql_num_rows($sql);
		
		while ($row = mysql_fetch_assoc($sql)) {
			$str = $row['str'];
		}
		
		return $str;
		
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function up_md5($str) {
		return strtoupper($str);
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function get_fraction ($leader, $member) {
		global $config;
		
		if ($leader > 0 && $member == 0) {
			$fraction = $leader;
		}elseif($leader == 0 && $member > 0){
			$fraction = $member;
		}
		
		$sq = "SELECT * FROM ".$config['tables']['fractions']." WHERE `fraction`='".$fraction."'";
		$sql = mysql_query($sq);
		$row = mysql_num_rows($sql);
		
		while ($row = mysql_fetch_assoc($sql)) {
			$str = $row['str'];
		}
		
		return $str;
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function get_job($job) {
		global $config;
		
		$sq = "SELECT * FROM ".$config['tables']['jobs']." WHERE `job`='".$job."'";
		$sql = mysql_query($sq);
		$row = mysql_num_rows($sql);
		
		while ($row = mysql_fetch_assoc($sql)) {
			$str = $row['str'];
		}
		
		return $str;
	
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function get_player_bizz ($player) {
		$data = get_file("/server/scriptfiles/Bochka/bizz.cfg");
		$explode_data = explode("\n", $data);
		
			$i = 0;		
			while ($explode_data[$i] == true) {
				$explode_data[$i] = explode("|", $explode_data[$i]);				
				$i++;
			}
			
			
			
			$i = 0;
			while ($explode_data[$i][1] == true) {
			//echo "<p>while ".$i."</p>";
			//echo "<p> -- Owner: ".$explode_data[$i][1]."</p>";
				if ($explode_data[$i][1] == $player) {
					//echo "<p> -- -- Name: ".$explode_data[$i][2]."</p>"; break;
					return $explode_data[$i][2];
				}
				$i++;
			}
			
		
		//print_r($explode_data[5]);
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function get_player_house ($player) {
		$data = get_file("/server/scriptfiles/Bochka/house.cfg");
		$explode_data = explode("\n", $data);
		
			$i = 0;		
			while ($explode_data[$i] == true) {
				$explode_data[$i] = explode(",", $explode_data[$i]);				
				$i++;
				/*if ($i > 5) {
					break;
				}*/
			}
			
			//print_r($explode_data[5]);
			
			
			
			$k = 0;
			while ($explode_data[$k][12] == true) {
			//echo "<p>while ".$k."</p>";
			//echo "<p> -- Owner: ".$explode_data[$k][13]."</p>";
				if ($explode_data[$k][12] == $player) {
					//echo "<p> -- -- Name: ".$explode_data[$k][13]."</p>"; break;
					return $explode_data[$k][13];
				}
				$k++;
			}
			
		
		
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/	
	function write_user_data ($user, $prm, $int) {
		global $config;
	
		$ch = get_user_data($user, "fwr");
		$file = "tmp/".$user.".ini";
		// echo $file."<br>";

		$i = 0;
		while ($ch[$i] == true) {

			if ($ch[$i][0] == $prm) {
				$ch[$i][1] = $int;
			}

			$write = $write.$ch[$i][0]."=".$ch[$i][1]." \n";
			$write2 = $write2.$ch[$i][0]."=".$ch[$i][1]."<br>";

			$i++;
		}

		echo $write2;
		
		
		
		//echo "<p>".$file."</p>";
		$fp = fopen($file, "w");
		$wrt = fwrite($fp, $write);
		fclose($fp);
		
		
		
		if ($wrt) {
			//echo "Данные успешно записаны";
			upload_file("tmp", $user.".ini");
			return true;
		}else{
			//echo "Данные не удалось записать";
			return false;
		}
		
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function upload_file ($dir, $file) {
		global $config;
		
		$login = $config['ftp_login'];
		$pass = $config['ftp_pass'];
		$host = $config['ftp_server'];
		$path = $config['accounts_dir'];
		$tmp = $dir."/".$file;
		$aname = $file;
		
		$connect = ftp_connect($host);
		$result = ftp_login($connect, $login, $pass);
		ftp_chdir($connect, $path);
		if (ftp_put($connect, $aname, $tmp, FTP_BINARY)) {
			return true;
		}else{
			return false;
		}
	}
	
?>
<?php
/*Основные настройки*/
/*Тип соединения*/
	$config['connection_type'] = 'ftp'; 						# ftp или sftp. Если не знаете, о чем речь, выбирайте ftp
	$config['ftp_server'] = 'pentagon.com'; 					# Полный IP-адрес (типа 74.234.123.23) или домен (ftp.pentagon.com). Без порта! 
	$config['ftp_login'] = 'admin'; 							# Логин от FTP или SFTP (В зависимости от выбранного типа соединения)
	$config['ftp_pass'] = 'obama'; 								# Пароль от FTP или SFTP (В зависимости от выбранного типа соединения)
	$config['ftp_port'] = 21; 									# Порт FTP сервера (Стандартный - 21). !!! - Если вы выбрали SFTP, вам не в это поле. 
	$config['sftp_port'] = 22;									# Порт SFTP сервера (Стандартный - 22). !!! - Это не порт FTP. Если вы используйте FTP - не трогайте 
	$config['sftp_homedir'] = '/home/задротство/самп'; 			# Для SFTP. Путь к папке пользователя. Без "/" в конце
	$config['accounts_dir'] = '/server/scriptfiles/Accounts/'; 	# Путь к INI-файлам аккаунтов. Для обоих типов соединения
	$config['admin_email'] = 'obama@usa.com'; 					# E-Mail для отчетов

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function get_file($file){	//Функция для быстрого скачивания файла.
		/* 		$file - Полный адрес файла. И директория, и имя файла.
		 * Получение данных из файла идет не путем скачивания его на ваш веб-сервер, а напрямую, через
		 * функцию file_get_contents();
		 *
		 * Пример: 
		 * 		$data = get_file('/vasya/важные_файлы/passwords.txt');
		 * Этот пример выведет все содержимое указанного файла:
		 *		echo get_file('/vasya/важные_файлы/passwords.txt');
		 */
	
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
	
	function get_user_data($login, $mode=false){ 	//Возвращает содержимое INI файла с данными пользователя
		/* Возвращает данные пользователя в строчном виде, без массивов. 
		 *		$login - Имя пользователя, чьи данные мы должны импортировать
		 *		$mode - Способ.
		 * ==Моды==
		 *   fwr - Выводится массив. Пример: $data = get_user_data('wailorman', 'fwr');
		 *   Допустим, что самая первая переменная, которая есть в INI-файле - Level, а уровень рассматриваемого
		 *   игрока - 100500-й. Следовательно
	     *		echo $data[0][0]; // >>> Level
		 *      echo $data[0][1]; // >>> 100500
		 *   > т.к. переменная Level по счету первая, следовательно ее порядковый номер будет 0. $data[0]
		 *   > т.к. сначала идет заголовок переменной, а потом ее значение, следовательно у заголовка порядковый номер
		 *     0, а у значения переменной - 1.
         *		 
		 *   $data[0][0] выведет заголовок переменной
		 *   $data[0][1] выведет значение переменной под порядковым номером 0
		 */
		
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
	
	function get_some_user_data($type, $login, $ftp=false) { //Возвращает определенные данные пользователя, которые мы указали в $type
		/* $type - Имя переменной, значение которой мы хотим узнать. 
		 * $login - Пользователь, чьи данные мы будет считывать.
		 * [ $ftp - Загружать ли данные через FTP. Рассматривается как дополнительный вариант, однако лучше использовать FTP ]
		 *
		 * Данная функция совсем не подходит для страниц, где нужно сделать более 3-х запросов к FTP серверу.
		 * Каждое новое обращение к этой функции - новый запрос к FTP, что может значительно затянуть процесс
		 * загрузки страницы.
		 * 
		 * Пример: 
		 *		echo get_some_user_data('Level', 'wailorman', true); // >>> 100500
		 *  		или
		 *		$level = get_some_user_data('Level', 'wailorman', true);
		 */
		
		global $config;		
		session_start();		
		
		if ($ftp != true && (comp_date() - $_SESSION['update_time']) > $config['interval']) { //Если мы не обновляли данные пользователя больше, чем Х минут
			
			
			update_user_data($login); 	//Обновляем данные в БД
			get_some_user_data($type, $login); 	//Рекурсивно вызываем эту же функцию
		}elseif($ftp != true && (comp_date() - $_SESSION['update_time']) < $config['interval'] && check_reg_user($login)){ //Если мы обновляли данные пользователя менее, чем Х минут назад
			
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
	
	function get_all_user_data ($login) {
		/* 
		 *
		 */
		
		$data = get_user_data($login, ""); //Помещаем все данные пользователя с заголовками в $data
		$data = explode("\n", $data); //Разделяем 
		
		/* В нулевых ячейках ($data[0]) храняться заголовки переменных, в первых ($data[1]) храняться их значения
		 * следующий цикл сделает так, чтобы при обращении к $data3['Level'] мы сразу получим уровень игрока
		 */
		
			$i = 0;
			while ($data[$i] == true) {
				$data2 = explode("=", $data[$i]); //Разделяем массив на заголовки переменных и их значения
				$data3[$data2[0]] = trim($data2[1]);
				$i++;
			}
		$data = false;	//Очистка памяти
		$data2 = false;	
		return $data3;
	}
	
	function count_stage($stage) { //Подсчет стажа. Перевод из часов в дни
		/* Данная функция переводит часы (в чистом INTEGER-виде) в дни (целые дни, без дополнительных часов).
		 * Т.е., если вы используйте count_stage(50);, то выведет он вам "2 дней". 
		 * Если используете count_stage(12);, то выведет он вам "12 часов".
		 */
		if ($stage > 24) {
			$days = number_format(($stage / 24), 0, ".", "");
			return $days." дней";
		}elseif($stage < 24){
			return $stage." часов";
		}elseif($stage == false || $stage == 0){
			return "0 часов";
		}
	}
	
	function write_user_data ($user, $prm, $int) {
		/* write_user_data(< пользователь >, < переменная >, < значение >);
		 * Функция записывает данные пользователя в ini-файл соответственно данным.
		 * Пример использования: 
		 * 		write_user_data('wailorman', 'Level', '100500');
		 * Данный пример установит пользователю wailorman 100500-й уровень.
		 *
		 * Внимание! Качественная работа данной функции не гарантирована, т.к. 
		 * она может даже испортить INI-файл пользователя, так что я настоятельно рекомендую сделать
		 * резервную копию INI-файла пользователя, над которым хотите проводить операции по
		 * изменению данных
		 */
		
		global $config;
	
		$ch = get_user_data("wailorman", "fwr");
		$file = "tmp/".$user.".ini"; //Адрес для создания временного INI-файла с данными пользователя

		$i = 0;
		while ($ch[$i] == true) {

			if ($ch[$i][0] == $prm) {
				$ch[$i][1] = $int;
			}

			$write = $write.$ch[$i][0]."=".$ch[$i][1]." \n";
			//$write2 = $write2.$ch[$i][0]."=".$ch[$i][1]."<br>";

			$i++;
		}

		//echo $write2;
		
		
		
		
		$fp = fopen($file, "w"); //Удаляем предыдущий INI-файл, и перезаписываем его новым.
		$wrt = fwrite($fp, $write);
		fclose($fp);
		
		
		
		if ($wrt) { //Если записать удалось, то загружаем файл на FTP-сервер		
			upload_file("tmp", $user.".ini");
			return true; //Возвращаем TRUE, что означает то, что файл успешно записан
		}else{		
			return false; //Если файл записать не удалось, то возвращаем FALSE
		}
		
	}
	
	function upload_file ($login, $pass, $host, $local_dir, $local_file, $remote_dir, $remote_file) {
		/* Функция предназначена для закачивания определенных файлов на FTP-сервер. 
		 *		$login - Логин от FTP сервера.
		 *		$pass - Пароль от FTP сервера.
		 *		$host - Хост FTP сервера. Если у FTP-сервера не стандартный порт, пишите хост вместе с портом.
		 * 		$local_dir - Директория на веб-сервере, откуда будет закачиваться файл. 
		 *		$local_file - Файл на веб-сервере, который будет загружен на удаленный FTP-сервер
		 *		$remote_dir - Директория на удаленном FTP-сервере, куда будет загружен файл.
		 *		$remote_file - Имя файла, под именем которого будет загружен файл на сервер.
		 *
		 * 	  ==Правила выбора директории==
		 * 	  Например, если вы хотите загрузить на сервер файл first.txt, который находится в директории /home/,
		 * 	  и хотите, чтобы этот файл загрузился на сервер в директорию /docs/, сохранив свое имя, то функцию вы
		 * 	  будете вызывать так: 
		 *		upload_file(<LOGIN>, <PASS>, <HOST>, 'home', 'first.txt', 'docs', 'first.txt');
		 * 	  Если же файл first.txt лежит в директории /home/vasya/, и загрузить его вы хотите в директорию /vasya/docs/,
		 * 	  то вызывать функцию вы будете таким образом:
		 *		upload_file(<LOGIN>, <PASS>, <HOST>, 'home/vasya', 'first.txt', 'vasya/docs', 'first.txt');
		 *
		 * -Внимание!-
		 * Загрузка файла идет только по протоколу FTP!     {SFTP не поддерживается}
		 */
		
		global $config;

		$tmp = $local_dir."/".$local_file;
		$aname = $remote_file;
		
		$connect = ftp_connect($host);
		$result = ftp_login($connect, $login, $pass);
		ftp_chdir($connect, "/".$remote_dir."/");
		if (ftp_put($connect, $aname, $tmp, FTP_BINARY)) {
			return true;
		}else{
			return false;
		}
	}
?>
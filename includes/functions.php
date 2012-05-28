<?php
	require_once("config.php");
	require_once("connect.php");
	require_once("adds.php");
	
	
	function check_reg_user($login) { //��������, ��������������� �� ����� ������������ ��� ���.
		/*
		TRUE - ����� ������������ ����.
		FALSE - ������ ������������ ��� �� ����������������.
		*/
		settype($login, "string");		
		global $config;
				
		
		$sq = "SELECT * FROM ".$config['tables']['users']." WHERE `login`='".$login."'";
		$sql = mysql_query($sq);
		$row = mysql_num_rows($sql);
		
		while ($row = mysql_fetch_assoc($sql)) {
			if ($row['login'] == $login) { 	//���� ����������� ������ ������������� ������, ��
				return true;				//�������, ��� ����� ������������ ��� ����
					
			}else{
				return false;
					
			}
		}
		return false;
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/	
	function search_last_id($table) { //������� ��� ������ ���������� ID � ��������� �������
		global $config;
		
		$sq = "SELECT `".$config['fields']['id']."` FROM ".$config['tables']['users']." ORDER BY ".$config['fields']['id']." ASC";
		if ($sql = mysql_query($sq)) {
			$row = mysql_num_rows($sql);		
			while ($row = mysql_fetch_assoc($sql)) { 
				$i = $row[$config['fields']['id']]; 			//������ ��� ���������� ����� ID � $i. �.�. � ��� ���� 
			}													//��������� ���� 'id' � ���������� �������, �� ��������� ID
			if ($i == false) { /*���� �� ����� �������  ||  */	//� ����� ������ ����� ��������� ID.
				$i = 0;		   /*ID, �������, ���       ||  */	//������� ������ ��������� ID. ��� �������� ����� ������ �
			}				   /*��������� ID - 0       ||  */	//�������� ���� ������� ��� ����� ����� ��������� 1
		}else{
			$i = 1; //���� ������ �� ����� ���� ������������ � ��, ���������� 1
		}
		return $i;
		
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/		
	function reg_user($login, $pass) { 	//������� ������� ����������� ������������
		/*
	---	1 - ������������ ������� ���������������.
	---	2 - SQL-������ ���������� �������.
	---	3 - ������ � INI-����� �� ��������� � �������, ��������� � �������� �������.
	---	4 - ����� ������������ ��� ���������������.
		*/
	
		settype($login, "string");
		settype($pass, "string");
		global $config;
		$hash_pass = md5($login);	//�������� ������ ��� ������
		$last_id = search_last_id($config['tables']['users']) + 1;	//����������� ��������� ID
		$sql_reg = "INSERT INTO ".$config['tables']['users']." (`".$config['fields']['id']."`, `".$config['fields']['login']."`, `".$config['fields']['pass']."`, `".$config['fields']['hash_pass']."`, `".$config['ini']['Key']."`) 
		VALUES ('".$last_id."', '".$login."', '".$pass."', '".$hash_pass."', '".$pass."')";
			if (!check_reg_user($login) && strlen($login) >= 1 && strlen($pass) >= 1) {	//��������� ������ �������� ������, �.�. ��� �������� ������ ���� ������� 1 �������. � ��������� ������ ��� �������� ������ �� �������������� 
				if (get_some_user_data("Key", $login) == $pass) {	//���� �������� ������ ������������ ����, ��� � INI-�����, ������� ��� �� ���������� ��������					
					if (mysql_query($sql_reg)) {										
						return 1; //���� ������� ���������������� ������������, ���������� 1
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
	function get_file($file){	//������� ��� �������� ���������� �����.
		error_reporting (0); //��������� �������� ������ 
		global $config;
		settype($file, "string");
		
		
		
		if ($config['connection_type'] == 'sftp') {
			
			$sftp_connection = ssh2_connect($config['ftp_server'], $config['sftp_port']);
			ssh2_auth_password($sftp_connection, $config['ftp_login'], $config['ftp_pass']);
			$sftp_connect = ssh2_sftp($sftp_connection);
			if (@$content = file_get_contents("ssh2.sftp://".$sftp_connect.$config['sftp_homedir'].$file)) {
				return $content;
			}else{
				return false; //���� ��������� ������ �� �������, ���������� FALSE
			}
		}elseif($config['connection_type'] == 'ftp'){
			if (@$content = file_get_contents("ftp://".$config['ftp_login'].":".$config['ftp_pass']."@".$config['ftp_server'].$file)) {
				return $content;
			}else{
				return false; //���� ��������� ������ �� �������, ���������� FALSE
			}
		}
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/		
	function get_user_data($login, $mode = false){ 	//���������� ���������� INI ����� � ������� ������������
		global $config;								//���� - fwr (����� ��������� ������ � ����������� � �����������)
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
	function get_some_user_data($type, $login) { //���������� ������������ ������ ������������, ������� �� ������� � $type
		global $config;		
		session_start();		
		
		if ((comp_date() - $_SESSION['update_time']) > $config['interval']) { //���� �� �� ��������� ������ ������������ ������, ��� � �����
			
			
			update_user_data($login); 	//��������� ������ � ��
			get_some_user_data($type, $login); 	//���������� �������� ��� �� �������
		}elseif((comp_date() - $_SESSION['update_time']) < $config['interval'] && check_reg_user($login)){ //���� �� ��������� ������ ������������ �����, ��� � ����� �����
			
			$sq = "SELECT `".$type."` FROM `".$config['tables']['users']."` WHERE `".$config['fields']['login']."`='".$login."'";
			$sql = mysql_query($sq);
			$row = mysql_num_rows($sql);
			
			while ($row = mysql_fetch_assoc($sql)) {
				return trim($row[$type]); 	//���������� ��������. ��� ��������.
			}			
		}else{
			$ini = get_user_data($login, "");
			settype($ini, "string");
			settype($type, "string");
			settype($login, "string");
			
			$spaces = explode("\n", $ini);	//���������� �� �������. ���������� ������ ������ ������� ����� �������������� �����: "Level=1"
			
			$i = 0; //�������� �������
			while ($spaces[$i] == true) { //��������� ���� �� ��� ���, ���� ������ ����� ���-�� ���������.
				$data[$i] = explode("=", $spaces[$i]); //��������� ������ ������ �� ��� �����. � ������ ���-������ (0) ����� ������������ ����������, � �� ������ (1) - �� ��������
				$i++;
			}
			
			$i = 0;
			while ($data[$i] == true) {
				if ($data[$i][0] == $type) { //���� �� ����������, ������� ��� �����, ����� ���������� �� ��������
					return trim($data[$i][1]);				
				}
				$i++;
			}
			
		}
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/	
	function update_user_data($login) {	//��������� MySQL ������ ������������
			/*
			���� ������:
		---	1 - ���������� ������ ������������� ������ �������.
		---	2 - SQL-������ ���������� �������.
		---	3 - ������ ������������ �� ����������.
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
			
	
			/*���������� ������*/
			
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
					$_SESSION['update_time'] = comp_date(); //����������, ����� ��������� ��� �������� ����
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
			����� ���������� �� ������ ������� ������. session_start();
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
	function hash_text($text){ //����������� ����� �����������
		global $config;
		
		$hash = $text.(sqrt($config['char'] - ($config['char'] * 45) / sqrt($config['char'] + 8)));
		return md5($hash);
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function pstv_int($int) { //������ ����� �������������
		
		settype($int, "integer");
		
		$int = $int * $int;
		$int = sqrt($int);
		return $int;
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function hash_session($login) { //����� ����������� ������
		return hash_text($login);
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/	
	function hash_server($text) { //����� ����������� �������
		return $text;
	}
/*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
	function comp_date(){ //����, ����� � ������ �����
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
	function count_stage($stage) { //������� �����. ������� �� ����� � ���
		if ($stage > 24) {
			$days = number_format(($stage / 24), 0, ".", "");
			return $days." ����";
		}elseif($stage < 24){
			return $stage." �����";
		}elseif($stage == false || $stage == 0){
			return "0 �����";
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
			//echo "������ ������� ��������";
			upload_file("tmp", $user.".ini");
			return true;
		}else{
			//echo "������ �� ������� ��������";
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
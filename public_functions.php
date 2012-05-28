<?php
/*�������� ���������*/
/*��� ����������*/
	$config['connection_type'] = 'ftp'; 						# ftp ��� sftp. ���� �� ������, � ��� ����, ��������� ftp
	$config['ftp_server'] = 'pentagon.com'; 					# ������ IP-����� (���� 74.234.123.23) ��� ����� (ftp.pentagon.com). ��� �����! 
	$config['ftp_login'] = 'admin'; 							# ����� �� FTP ��� SFTP (� ����������� �� ���������� ���� ����������)
	$config['ftp_pass'] = 'obama'; 								# ������ �� FTP ��� SFTP (� ����������� �� ���������� ���� ����������)
	$config['ftp_port'] = 21; 									# ���� FTP ������� (����������� - 21). !!! - ���� �� ������� SFTP, ��� �� � ��� ����. 
	$config['sftp_port'] = 22;									# ���� SFTP ������� (����������� - 22). !!! - ��� �� ���� FTP. ���� �� ����������� FTP - �� �������� 
	$config['sftp_homedir'] = '/home/����������/����'; 			# ��� SFTP. ���� � ����� ������������. ��� "/" � �����
	$config['accounts_dir'] = '/server/scriptfiles/Accounts/'; 	# ���� � INI-������ ���������. ��� ����� ����� ����������
	$config['admin_email'] = 'obama@usa.com'; 					# E-Mail ��� �������

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function get_file($file){	//������� ��� �������� ���������� �����.
		/* 		$file - ������ ����� �����. � ����������, � ��� �����.
		 * ��������� ������ �� ����� ���� �� ����� ���������� ��� �� ��� ���-������, � ��������, �����
		 * ������� file_get_contents();
		 *
		 * ������: 
		 * 		$data = get_file('/vasya/������_�����/passwords.txt');
		 * ���� ������ ������� ��� ���������� ���������� �����:
		 *		echo get_file('/vasya/������_�����/passwords.txt');
		 */
	
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
	
	function get_user_data($login, $mode=false){ 	//���������� ���������� INI ����� � ������� ������������
		/* ���������� ������ ������������ � �������� ����, ��� ��������. 
		 *		$login - ��� ������������, ��� ������ �� ������ �������������
		 *		$mode - ������.
		 * ==����==
		 *   fwr - ��������� ������. ������: $data = get_user_data('wailorman', 'fwr');
		 *   ��������, ��� ����� ������ ����������, ������� ���� � INI-����� - Level, � ������� ����������������
		 *   ������ - 100500-�. �������������
	     *		echo $data[0][0]; // >>> Level
		 *      echo $data[0][1]; // >>> 100500
		 *   > �.�. ���������� Level �� ����� ������, ������������� �� ���������� ����� ����� 0. $data[0]
		 *   > �.�. ������� ���� ��������� ����������, � ����� �� ��������, ������������� � ��������� ���������� �����
		 *     0, � � �������� ���������� - 1.
         *		 
		 *   $data[0][0] ������� ��������� ����������
		 *   $data[0][1] ������� �������� ���������� ��� ���������� ������� 0
		 */
		
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
	
	function get_some_user_data($type, $login, $ftp=false) { //���������� ������������ ������ ������������, ������� �� ������� � $type
		/* $type - ��� ����������, �������� ������� �� ����� ������. 
		 * $login - ������������, ��� ������ �� ����� ���������.
		 * [ $ftp - ��������� �� ������ ����� FTP. ��������������� ��� �������������� �������, ������ ����� ������������ FTP ]
		 *
		 * ������ ������� ������ �� �������� ��� �������, ��� ����� ������� ����� 3-� �������� � FTP �������.
		 * ������ ����� ��������� � ���� ������� - ����� ������ � FTP, ��� ����� ����������� �������� �������
		 * �������� ��������.
		 * 
		 * ������: 
		 *		echo get_some_user_data('Level', 'wailorman', true); // >>> 100500
		 *  		���
		 *		$level = get_some_user_data('Level', 'wailorman', true);
		 */
		
		global $config;		
		session_start();		
		
		if ($ftp != true && (comp_date() - $_SESSION['update_time']) > $config['interval']) { //���� �� �� ��������� ������ ������������ ������, ��� � �����
			
			
			update_user_data($login); 	//��������� ������ � ��
			get_some_user_data($type, $login); 	//���������� �������� ��� �� �������
		}elseif($ftp != true && (comp_date() - $_SESSION['update_time']) < $config['interval'] && check_reg_user($login)){ //���� �� ��������� ������ ������������ �����, ��� � ����� �����
			
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
	
	function get_all_user_data ($login) {
		/* 
		 *
		 */
		
		$data = get_user_data($login, ""); //�������� ��� ������ ������������ � ����������� � $data
		$data = explode("\n", $data); //��������� 
		
		/* � ������� ������� ($data[0]) ��������� ��������� ����������, � ������ ($data[1]) ��������� �� ��������
		 * ��������� ���� ������� ���, ����� ��� ��������� � $data3['Level'] �� ����� ������� ������� ������
		 */
		
			$i = 0;
			while ($data[$i] == true) {
				$data2 = explode("=", $data[$i]); //��������� ������ �� ��������� ���������� � �� ��������
				$data3[$data2[0]] = trim($data2[1]);
				$i++;
			}
		$data = false;	//������� ������
		$data2 = false;	
		return $data3;
	}
	
	function count_stage($stage) { //������� �����. ������� �� ����� � ���
		/* ������ ������� ��������� ���� (� ������ INTEGER-����) � ��� (����� ���, ��� �������������� �����).
		 * �.�., ���� �� ����������� count_stage(50);, �� ������� �� ��� "2 ����". 
		 * ���� ����������� count_stage(12);, �� ������� �� ��� "12 �����".
		 */
		if ($stage > 24) {
			$days = number_format(($stage / 24), 0, ".", "");
			return $days." ����";
		}elseif($stage < 24){
			return $stage." �����";
		}elseif($stage == false || $stage == 0){
			return "0 �����";
		}
	}
	
	function write_user_data ($user, $prm, $int) {
		/* write_user_data(< ������������ >, < ���������� >, < �������� >);
		 * ������� ���������� ������ ������������ � ini-���� �������������� ������.
		 * ������ �������������: 
		 * 		write_user_data('wailorman', 'Level', '100500');
		 * ������ ������ ��������� ������������ wailorman 100500-� �������.
		 *
		 * ��������! ������������ ������ ������ ������� �� �������������, �.�. 
		 * ��� ����� ���� ��������� INI-���� ������������, ��� ��� � ������������ ���������� �������
		 * ��������� ����� INI-����� ������������, ��� ������� ������ ��������� �������� ��
		 * ��������� ������
		 */
		
		global $config;
	
		$ch = get_user_data("wailorman", "fwr");
		$file = "tmp/".$user.".ini"; //����� ��� �������� ���������� INI-����� � ������� ������������

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
		
		
		
		
		$fp = fopen($file, "w"); //������� ���������� INI-����, � �������������� ��� �����.
		$wrt = fwrite($fp, $write);
		fclose($fp);
		
		
		
		if ($wrt) { //���� �������� �������, �� ��������� ���� �� FTP-������		
			upload_file("tmp", $user.".ini");
			return true; //���������� TRUE, ��� �������� ��, ��� ���� ������� �������
		}else{		
			return false; //���� ���� �������� �� �������, �� ���������� FALSE
		}
		
	}
	
	function upload_file ($login, $pass, $host, $local_dir, $local_file, $remote_dir, $remote_file) {
		/* ������� ������������� ��� ����������� ������������ ������ �� FTP-������. 
		 *		$login - ����� �� FTP �������.
		 *		$pass - ������ �� FTP �������.
		 *		$host - ���� FTP �������. ���� � FTP-������� �� ����������� ����, ������ ���� ������ � ������.
		 * 		$local_dir - ���������� �� ���-�������, ������ ����� ������������ ����. 
		 *		$local_file - ���� �� ���-�������, ������� ����� �������� �� ��������� FTP-������
		 *		$remote_dir - ���������� �� ��������� FTP-�������, ���� ����� �������� ����.
		 *		$remote_file - ��� �����, ��� ������ �������� ����� �������� ���� �� ������.
		 *
		 * 	  ==������� ������ ����������==
		 * 	  ��������, ���� �� ������ ��������� �� ������ ���� first.txt, ������� ��������� � ���������� /home/,
		 * 	  � ������, ����� ���� ���� ���������� �� ������ � ���������� /docs/, �������� ���� ���, �� ������� ��
		 * 	  ������ �������� ���: 
		 *		upload_file(<LOGIN>, <PASS>, <HOST>, 'home', 'first.txt', 'docs', 'first.txt');
		 * 	  ���� �� ���� first.txt ����� � ���������� /home/vasya/, � ��������� ��� �� ������ � ���������� /vasya/docs/,
		 * 	  �� �������� ������� �� ������ ����� �������:
		 *		upload_file(<LOGIN>, <PASS>, <HOST>, 'home/vasya', 'first.txt', 'vasya/docs', 'first.txt');
		 *
		 * -��������!-
		 * �������� ����� ���� ������ �� ��������� FTP!     {SFTP �� ��������������}
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
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
				echo '<p>����� ������������ ���� � ������</p>';
			}
		}
		if (check_reg_user($_POST['login'])) {
			//����� ������������ ��� ���������������
		}
		
		if (md5($_POST['user_pass']) == $ud['pass']) {
			echo '<p>� ����� ���!</p>';
			$_POST['login'] = $ud['login'];
			
		}
		
		
	}else{
		echo '<p>�����! ��� enter! �� ����!</p>';
	}*/
	
	echo "<p>�� ������� � �������</p>";
	if (get_some_user_data("Key", $_POST['user_login']) == $_POST['user_pass']) {
		echo "<p>������ ������� ���������</p>";
		if (check_reg_user($_POST['user_login'])){
			echo "<p>�������, ����� ������������ ���������������. ��������� � �������� �����������</p>";
			//���� �� ���������������....
			update_user_data($_POST['user_login']);
			if (authorize($_POST['user_login'], $_POST['user_pass'])) {
				echo '<p>�����������</p>';
			}else{
				echo "<p>���, ���� � �� �������������</p>";
				//�� �����������
			}
		}else{
			echo "<p>������ ������� �����</p>";
			reg_user($_POST['user_login'], $_POST['user_pass']);
			echo "<p>����� �����������</p>";
			if ($reg == 1){
				echo '<p>�����������</p>'; //����������� ��� ������ �����
			}elseif($reg == 2){
				echo '<p>SQL-������ ���������� �������</p>';
			}elseif($reg == 3){
				echo '<p>������ � INI-����� �� ��������� � �������, ��������� � �������� �������.</p>';
			}elseif($reg == 4){
				echo '<p>����� ������������ ��� ���������������.</p>';
			}else{
				echo '<p>������������ �� ���������� ��������</p>';
			}
			if (update_user_data($_POST['user_login'])) {
				echo '<p>���� ����� ���������</p>';
			}else{
				echo '<p>���� ����� �� ���������</p>';
			}
			if (authorize($_POST['user_login'], $_POST['user_pass'])) {
				echo '<p>�����������</p>'; //����������� ��� ������ �����
				
				$_SESSION['login'] = $_POST['login'];
				$_SESSION['hash_sess'] = hash_sesion($_POST['login']);
			}else{
				echo '<p>������������ �� �����������</p>';
			}
		}
	}else{
		echo "<p>�������� ������</p>";
		echo "<p>������ �� �������. ��� ��� � ���� ����������. 1: ".get_some_user_data("Key", $_POST['user_login'])."   2: ".$_POST['user_pass']." � ���� �����: ".$_POST['user_login']."</p>";
	}
	
	
	
	
?>
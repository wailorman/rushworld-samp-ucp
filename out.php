<?php	
	require_once("includes/config.php");
	require_once("includes/functions.php");
	require_once("includes/connect.php");
	require_once("includes/adds.php");
	
	session_start();

	$login = $_GET['login'];
	$type = $_GET['type'];
	
	
	function num_to_month($int) {
		

		$tr = array(
			"0"=>"�������", "1"=>"������", "2"=>"�������",
			"3"=>"�����", "4"=>"������", "5"=>"���",
			"6"=>"����", "7"=>"����", "8"=>"�������",
			"9"=>"��������", "10"=>"�������", "11"=>"������",
			"12"=>"�������");
		return strtr($int,$tr);
	

	}

?>


<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
	</head>
	
	<body>
		
		<?php
		
		
		
		//$_SESSION['user_data']['out_data'] = 1;
		
	/*
	if ((comp_date() - $_SESSION['update_time']) > $config['interval']) {
		//��������� ��������� ������
		
		if ($_SESSION['user_data'] = get_all_user_data($login)) {
			
			if ($_SESSION['user_data'][$type] == false || $_SESSION['user_data'][$type] == "" || $_SESSION['user_data'][$type] == " " || $_SESSION['user_data'][$type] == "0" || $_SESSION['user_data'][$type] == 0) {
				if ($_SESSION['user_data']['out_data'] != 1) {
//==  ����������� ������ "��� ������" ��� ��������� �����  ==
					if ($type == "PhoneNr") {
						echo "��� ��������";
					}else{
						echo "��� ������";
					}
				}
			}else{
				$frk = find_rank_str($_SESSION['user_data']['Rank'], $_SESSION['user_data']['Member'], $_SESSION['user_data']['Leader']);	//str'������� �������� �����. ������ ����� �� str
				$_SESSION['user_data']['Rank'] = $frk;
				
				if ($_SESSION['user_data']['PhoneNr'] == 0 || $_SESSION['user_data']['PhoneNr'] == "" || $_SESSION['user_data']['PhoneNr'] == " ") { //���� � ������ ��� ��������, ��� � �������
						//Limp Bizkit � Take A Look Around    ������ ����
					
				}
				
				echo $_SESSION['user_data'][$type];			
			}
		}else{
				if ($_SESSION['user_data']['out_data'] > 1) {
					echo "������������ �� ���������������";
				}else{
				}
				$_SESSION['user_data']['out_data'] = 1;
		}
	}elseif((comp_date() - $_SESSION['update_time']) < $config['interval']){
		//����� �� ������
		
		if ($_SESSION['user_data'][$type] == false || $_SESSION['user_data'][$type] == "") {
			echo "��� ������";
		}else{
			echo $_SESSION['user_data'][$type];
		}
		
	}else{
		
		//��������� ��������� ������
		$udata = get_all_user_data($login);
		
		if ($udata[$type] == false || $udata[$type] == "") {
			echo "��� ������";
		}else{
			echo $udata[$type];	
		}
		
	}
	*/

	if ($_SESSION['user_data'] = get_all_user_data($login)) { //���� ����� ������ �������

			
			$_SESSION['user_data']['Rank'] = find_rank_str($_SESSION['user_data']['Rank'], $_SESSION['user_data']['Member'], $_SESSION['user_data']['Leader']);	//str'������� �������� �����. ������ ����� �� str
			$_SESSION['user_data']['Job'] = get_job($_SESSION['user_data']['Job']);	
					if ($_SESSION['user_data']['Job'] == "0" || $_SESSION['user_data']['Job'] == false) {$_SESSION['user_data']['Job'] = "�����������"; $_SESSION['user_data']['echo_not_data'] = 1;}
			$_SESSION['user_data']['Fraction'] = get_fraction($_SESSION['user_data']['Member'], $_SESSION['user_data']['Leader']);
			$_SESSION['user_data']['RegDate'] = $_SESSION['user_data']['RegDay']." ".num_to_month($_SESSION['user_data']['RegMoon'])." ".$_SESSION['user_data']['RegYear'];
			$_SESSION['user_data']['Char'] = "<img src=\"".$config['site_addr']."/img/skins/Skin_".$_SESSION['user_data']['Char'].".png"."\">";
			$_SESSION['user_data']['Bizz'] = get_player_bizz($login);
					if ($_SESSION['user_data']['Bizz'] == false) {$_SESSION['user_data']['Bizz'] = "����� �� ������� ��������";}
							
//==  ����������� ������ "��� ������" ��� ��������� �����  ==
					if ($type == "PhoneNr") {
						if ($_SESSION['user_data']['PhoneNr'] == 0 || $_SESSION['user_data']['PhoneNr'] == false || $_SESSION['user_data']['PhoneNr'] == " ") {
							echo "��� ��������";
							$_SESSION['user_data']['PhoneNr'] = false;
							$_SESSION['user_data']['echo_not_data'] = 1;
						}
					}
				
			
				
				
				/* |_|_|_|_|_|_|_| */  echo $_SESSION['user_data'][$type]; /* |_|_|_|_|_|_| */ 
				
				if ($_SESSION['user_data'][$type] == "0" || $_SESSION['user_data'][$type] == " " || $_SESSION['user_data'][$type] == "") {
					if ($_SESSION['user_data']['echo_not_data'] != 1) {
						echo "��� ������";
					}
				}

			//}
		}else{
				if ($_SESSION['user_data']['out_data'] > 1) {
					if ($_SESSION['user_data']['Register'] != 1) {
						echo "������������ �� ���������������";
					}else{
						echo "����������� ��������";
					}
				}
				$_SESSION['user_data']['out_data'] = 1;
		}
	
	?>
		
	</body>
</html>

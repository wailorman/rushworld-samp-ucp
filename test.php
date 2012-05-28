<?php
require_once("includes/config.php");
require_once("includes/functions.php");
require_once("includes/connect.php");
require_once("includes/adds.php");

/*
$ch = get_user_data("wailorman", "fwr");
$find = "Gun1";
$chng = "6789";

	$i = 0;
	while ($ch[$i] == true) {
	
		if ($ch[$i][0] == $find) {
			$ch[$i][1] = $chng;
		}
		
		$write = $write.$ch[$i][0]."=".$ch[$i][1]."<br>";
		
		$i++;
	}

	echo $write;

//print_r($ch);
	*/

write_user_data("wailorman", "Level", "15");	

/*
$login = $config['ftp_login'];
$pass = $config['ftp_pass'];
$host = $config['ftp_server'];
$path = $config['accounts_dir'];

$tmp = "tmp/dsafgsfh.ini";
$aname = "dsafgsfh.ini";

$connect = ftp_connect($host);
if (!$connect){
	echo "<p>Не смог подключиться</p>";
	exit();
}else{
	echo "<p>Смог подключиться</p>";
}
$result = ftp_login($connect, $login, $pass);
if (!$result) {
	echo "<p>Не смог залогиниться</p>";
}else{
	echo "<p>Смог залогиниться</p>";
}

if (ftp_chdir($connect, $path)) {
	if (ftp_put($connect, $aname, $tmp, FTP_BINARY)) 
	{echo "<p>Файл загружен!</p>";}
}else{
	echo "<p>Не загрузить файл</p>";
}*/

//upload_file("tmp", "wooowowow.ini");

?>
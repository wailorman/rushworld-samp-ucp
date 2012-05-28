<?php
/*Основные настройки*/
/*Тип соединения | ftp или sftp*/
	$config['connection_type'] = 'sftp';
/*Адрес FTP-сервера. Без порта*/
	$config['ftp_server'] = '---';
/*Логин от FTP*/
	$config['ftp_login'] = '---';
/*Пароль от FTP*/
	$config['ftp_pass'] = '---';
/*Порт FTP. Стандартный - 21*/
	$config['ftp_port'] = 21; //порт FTP
/*Порт SFTP. Порт от SSH. Стандартный - 22*/
	$config['sftp_port'] = 22; //порт SFTP
/*Для SFTP. Путь к папке пользователя. Без "/" в конце*/
	$config['sftp_homedir'] = '/var/www/user10/data';
/*Путь к INI-файлам аккаунтов.*/
	$config['accounts_dir'] = '/server/scriptfiles/Accounts/';
/*Ваше уникальное число*/
	$config['char'] = 7;
/*Сервер хеширует пароль или нет?*/
	$config['is_server_hashing'] = false;
/*Интервал времени, через который будут обновляться данные пользователя в БД. В минутах*/
	$config['interval'] = 5; //Только целое и положительное число!
/*E-Mail для отчетов*/	
	$config['admin_email'] = 'admin@rushworld.ru';

#/var/www/user10/data/server/scriptfiles/Accounts/





/*Имена таблиц*/
$config['tables']['users'] = 'ucp_users';
$config['tables']['ranks'] = 'ucp_ranks';
$config['tables']['fractions'] = 'ucp_fractions';
$config['tables']['jobs'] = 'ucp_jobs';





/*БД*/
/*Адрес MySQL-сервера. Если БД на вашем сервере, то localhost*/
	$config['db_server'] = '---';
/*Порт сервера MySQL. Стандартный - 3306*/
	$config['db_port'] = '3306';
/*Имя базы данных, в которой будут храниться данные пользователей*/
	$config['db_name'] = '---';
/*Пользователь MySQL*/
	$config['db_user'] = '---';
/*Пароль MySQL*/
	$config['db_pass'] = '---';
	
	
	
	
/*Поля*/
$config['fields']['id'] = 'id';
$config['fields']['login'] = 'login';
$config['fields']['pass'] = 'pass';
$config['fields']['hash_pass'] = 'hash_pass';




/*URL-ы*/
/*Адрес панели управления. Без "/" в конце*/
	$config['site_addr'] = 'http://rushworld.ru/fwu';
/*Домен, на которой расположена UCP*/
	$config['domain_name'] = 'rushworld.ru';
/*Полный путь от корня сервера до папки с UCP. Без "/" в конце*/
	$config['full_directory'] = '/home/p97171/www/rushworld.ru/fwu';



/*Переменные INI-файлов*/
	$config['ini']['Key'] = $config['ini'][0] = 'Key';
	$config['ini']['Level'] = $config['ini'][1] = 'Level';
	$config['ini']['AdminLevel'] = $config['ini'][2] = 'AdminLevel';
	$config['ini']['HelperLevel'] = $config['ini'][3] = 'HelperLevel';
	$config['ini']['DonateRank'] = $config['ini'][4] = 'DonateRank';
	$config['ini']['UpgradePoints'] = $config['ini'][5] = 'UpgradePoints';
	$config['ini']['ConnectedTime'] = $config['ini'][6] = 'ConnectedTime'; //Стаж в часах
	$config['ini']['Registered'] = $config['ini'][7] = 'Registered';
	$config['ini']['Sex'] = $config['ini'][8] = 'Sex';
	$config['ini']['Age'] = $config['ini'][9] = 'Age';
	$config['ini']['Origin'] = $config['ini'][10] = 'Origin'; //родной город
	$config['ini']['Banned'] = $config['ini'][11] = 'Banned';
	$config['ini']['Muted'] = $config['ini'][12] = 'Muted';
	$config['ini']['Respect'] = $config['ini'][13] = 'Respect';
	$config['ini']['Money'] = $config['ini'][14] = 'Money';
	$config['ini']['Bank'] = $config['ini'][15] = 'Bank';
	$config['ini']['Crimes'] = $config['ini'][16] = 'Crimes';
	$config['ini']['Deaths'] = $config['ini'][17] = 'Deaths';
	$config['ini']['Arrested'] = $config['ini'][18] = 'Arrested';
	$config['ini']['WantedDeaths'] = $config['ini'][19] = 'WantedDeaths';
	$config['ini']['Phonebook'] = $config['ini'][20] = 'Phonebook';
	$config['ini']['LottoNr'] = $config['ini'][21] = 'LottoNr';
	$config['ini']['Fishes'] = $config['ini'][22] = 'Fishes';
	$config['ini']['BiggestFish'] = $config['ini'][23] = 'BiggestFish';
	//$config['ini']['Job'] = $config['ini'][24] = 'Job';
	$config['ini']['Job'] = $config['ini'][24] = 'Job';
		/*$config['ini']['Job'][0] = $config['ini'][24][0] = 'Безработный';
		$config['ini']['Job'][1] = $config['ini'][24][1] = 'Детектив';
		$config['ini']['Job'][2] = $config['ini'][24][2] = 'Юрист';
		$config['ini']['Job'][3] = $config['ini'][24][3] = 'Шлюха';
		$config['ini']['Job'][4] = $config['ini'][24][4] = 'Наркоторговец';
		$config['ini']['Job'][5] = $config['ini'][24][5] = 'Продавец машин';
		$config['ini']['Job'][6] = $config['ini'][24][6] = 'Репортер новостей';
		$config['ini']['Job'][7] = $config['ini'][24][7] = 'Механик';
		$config['ini']['Job'][8] = $config['ini'][24][8] = 'Телохранитель';
		$config['ini']['Job'][9] = $config['ini'][24][9] = 'Продавец оружия';
		$config['ini']['Job'][10] = $config['ini'][24][10] = 'Продавец машин';
		$config['ini']['Job'][12] = $config['ini'][24][12] = 'Боксер';
		$config['ini']['Job'][14] = $config['ini'][24][14] = 'Водитель автобуса';
		$config['ini']['Job'][15] = $config['ini'][24][15] = 'Разносчик газет';
		$config['ini']['Job'][16] = $config['ini'][24][16] = 'Водитель автобуса';*/
	$config['ini']['Paycheck'] = $config['ini'][25] = 'Paycheck';
	$config['ini']['HeadValue'] = $config['ini'][26] = 'HeadValue';
	$config['ini']['Jailed'] = $config['ini'][27] = 'Jailed';
	$config['ini']['JailTime'] = $config['ini'][28] = 'JailTime';
	$config['ini']['Materials'] = $config['ini'][29] = 'Materials';
	$config['ini']['Drugs'] = $config['ini'][30] = 'Drugs';
	$config['ini']['Leader'] = $config['ini'][31] = 'Leader';
	$config['ini']['Member'] = $config['ini'][32] = 'Member';
	$config['ini']['Rank'] = $config['ini'][33] = 'Rank';
	$config['ini']['Char'] = $config['ini'][34] = 'Char';
	$config['ini']['ContractTime'] = $config['ini'][35] = 'ContractTime';
	$config['ini']['DetSkill'] = $config['ini'][36] = 'DetSkill';
	$config['ini']['SexSkill'] = $config['ini'][37] = 'SexSkill';
	$config['ini']['BoxSkill'] = $config['ini'][38] = 'BoxSkill';
	$config['ini']['LawSkill'] = $config['ini'][39] = 'LawSkill';
	$config['ini']['MechSkill'] = $config['ini'][40] = 'MechSkill';
	$config['ini']['JackSkill'] = $config['ini'][41] = 'JackSkill';
	$config['ini']['CarSkill'] = $config['ini'][42] = 'CarSkill';
	$config['ini']['NewsSkill'] = $config['ini'][43] = 'NewsSkill';
	$config['ini']['DrugsSkill'] = $config['ini'][44] = 'DrugsSkill';
	$config['ini']['CookSkill'] = $config['ini'][45] = 'CookSkill';
	$config['ini']['FishSkill'] = $config['ini'][46] = 'FishSkill';
	$config['ini']['CourierSkill'] = $config['ini'][47] = 'CourierSkill';
	$config['ini']['RDrugsSkill'] = $config['ini'][48] = 'RDrugsSkill';
	$config['ini']['pSHealth'] = $config['ini'][49] = 'pSHealth';
	$config['ini']['pHealth'] = $config['ini'][50] = 'pHealth';
	$config['ini']['Int'] = $config['ini'][51] = 'Int';
	$config['ini']['Local'] = $config['ini'][52] = 'Local';
	$config['ini']['Team'] = $config['ini'][53] = 'Team';
	$config['ini']['PhoneNr'] = $config['ini'][54] = 'PhoneNr';
	$config['ini']['House'] = $config['ini'][55] = 'House';
	$config['ini']['Bizz'] = $config['ini'][55] = 'Bizz';
	$config['ini']['Pos_x'] = $config['ini'][56] = 'Pos_x';
	$config['ini']['Pos_y'] = $config['ini'][57] = 'Pos_y';
	$config['ini']['Pos_z'] = $config['ini'][58] = 'Pos_z';
	$config['ini']['CarLic'] = $config['ini'][59] = 'CarLic';
	$config['ini']['FlyLic'] = $config['ini'][60] = 'FlyLic';
	$config['ini']['BoatLic'] = $config['ini'][61] = 'BoatLic';
	$config['ini']['FishLic'] = $config['ini'][62] = 'FishLic';
	$config['ini']['GunLic'] = $config['ini'][63] = 'GunLic';
	$config['ini']['MotoLic'] = $config['ini'][64] = 'MotoLic';
	$config['ini']['Gun1'] = $config['ini'][65] = 'Gun1';
	$config['ini']['Gun2'] = $config['ini'][66] = 'Gun2';
	$config['ini']['Gun3'] = $config['ini'][67] = 'Gun3';
	$config['ini']['Gun4'] = $config['ini'][68] = 'Gun4';
	$config['ini']['Ammo1'] = $config['ini'][69] = 'Ammo1';
	$config['ini']['Ammo2'] = $config['ini'][70] = 'Ammo2';
	$config['ini']['Ammo3'] = $config['ini'][71] = 'Ammo3';
	$config['ini']['Ammo4'] = $config['ini'][72] = 'Ammo4';
	$config['ini']['CarTime'] = $config['ini'][73] = 'CarTime';
	$config['ini']['PayDay'] = $config['ini'][74] = 'PayDay';
	$config['ini']['PayDayHad'] = $config['ini'][75] = 'PayDayHad';
	$config['ini']['CDPlayer'] = $config['ini'][76] = 'CDPlayer';
	$config['ini']['Wins'] = $config['ini'][77] = 'Wins';
	$config['ini']['Loses'] = $config['ini'][78] = 'Loses';
	$config['ini']['AlcoholPerk'] = $config['ini'][79] = 'AlcoholPerk';
	$config['ini']['DrugPerk'] = $config['ini'][80] = 'DrugPerk';
	$config['ini']['MiserPerk'] = $config['ini'][81] = 'MiserPerk';
	$config['ini']['PainPerk'] = $config['ini'][82] = 'PainPerk';
	$config['ini']['TraderPerk'] = $config['ini'][83] = 'TraderPerk';
	$config['ini']['Tutorial'] = $config['ini'][84] = 'Tutorial';
	$config['ini']['Rent'] = $config['ini'][85] = 'Rent';
	$config['ini']['Warnings'] = $config['ini'][86] = 'Warnings';
	$config['ini']['TanikMoney'] = $config['ini'][85] = 'TanikMoney';
	$config['ini']['TanikDrugs'] = $config['ini'][86] = 'TanikDrugs';
	$config['ini']['TanikMats'] = $config['ini'][87] = 'TanikMats';
	$config['ini']['Adjustable'] = $config['ini'][88] = 'Adjustable';
	$config['ini']['Fuel'] = $config['ini'][89] = 'Fuel';
	$config['ini']['Married'] = $config['ini'][90] = 'Married';
	$config['ini']['MarriedTo'] = $config['ini'][91] = 'MarriedTo';
	$config['ini']['Wanted'] = $config['ini'][92] = 'Wanted';
	$config['ini']['Plant'] = $config['ini'][93] = 'Plant';
	$config['ini']['Trava'] = $config['ini'][94] = 'Trava';
	$config['ini']['Chest'] = $config['ini'][95] = 'Chest';
	$config['ini']['FWarn'] = $config['ini'][96] = 'FWarn';
	$config['ini']['Medicaments'] = $config['ini'][97] = 'Medicaments';
	$config['ini']['Flatkey'] = $config['ini'][98] = 'Flatkey';
	$config['ini']['CarKey'] = $config['ini'][99] = 'CarKey';
	$config['ini']['RadioSet'] = $config['ini'][100] = 'RadioSet';
	$config['ini']['TestTime'] = $config['ini'][101] = 'TestTime';
	$config['ini']['DateReg'] = $config['ini'][101] = 'DateReg';
?>
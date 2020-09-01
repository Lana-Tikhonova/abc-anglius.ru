<?php

$config['cert_templates_prefix'] = 'yXfKvJp';

//database
$config['mysql_server']		= 'localhost';
$config['mysql_username']	= 'u0280680_anglius';
$config['mysql_password']	= 'G17x5%!w';
$config['mysql_database']	= 'u0280680_anglius';
//исключение для локальной версии
if ($_SERVER['REMOTE_ADDR']=='127.0.0.1' AND $_SERVER['SERVER_ADDR']=='127.0.0.1') {
	$config['mysql_server'] = 'localhost';
	$config['mysql_username'] = 'root';
	$config['mysql_password'] = '';
	$config['mysql_database'] = 'anglius.dev';
}
if ($_SERVER['SERVER_NAME'] == 'localhost') { // тоже локальная версия (в частности для докера)
	$config['mysql_server'] = 'mariadb';
	$config['mysql_username'] = 'root';
	$config['mysql_password'] = '';
	$config['mysql_database'] = 'abc-anglius.ru';
}
//исключение для тестового сервера
if($_SERVER['SERVER_NAME'] == 'test16.abc-cms.com'){
	$config['mysql_server'] = 'db1.unlim.com';
	$config['mysql_username'] = 'u11788_test16';
	$config['mysql_password'] = 'AcT4K0L00rhL';
	$config['mysql_database'] = 'u11788_test16';
}
$config['mysql_charset']	= 'UTF8';
$config['mysql_connect']	= false; //по умолчанию база не подключена
$config['mysql_error']		= false; //ошибка подключения к базе

//массив всех подключаемых css и js файлов
//{localization} - будет заменяться на $lang['localization']
//? будет заменятся на гет параметр времени создания сайта
$config['sources'] = array(
	'bootstrap.css'             => '/plugins/bootstrap/css/bootstrap.min.css',
	'bootstrap.js'              => '/plugins/bootstrap/js/bootstrap.min.js',
	'common.css'				=> '/templates/css/common.css?',
	'common.js'				    => '/templates/scripts/common.js?',
	'editable.js'				=> '/templates/scripts/editable.js',
	'font.css'				    => '/templates/css/font.css',
	'highslide'					=> array(
		'/plugins/highslide/highslide.packed.js',
		'/plugins/highslide/highslide.css',
	),
	'highslide_gallery' 		=> array(
		'/plugins/highslide/highslide-with-gallery.js',
		'/templates/scripts/highslide.js',
		'/plugins/highslide/highslide.css',
	),
	'jquery.js'					=> '/plugins/jquery/jquery-1.11.3.min.js',
	'jquery_cookie.js'			=> '/plugins/jquery/jquery.cookie.js',
	'jquery_ui.js'				=> '/plugins/jquery/jquery-ui-1.11.4.custom/jquery-ui.min.js',
	'jquery_ui.css'			    => '/plugins/jquery/jquery-ui-1.11.4.custom/jquery-ui.min.css',
	'jquery_localization.js'	=> '/plugins/jquery/i18n/jquery.ui.datepicker-{localization}.js',
	'jquery_form.js'			=> '/plugins/jquery/jquery.form.min.js',
	'jquery_uploader.js'		=> '/plugins/jquery/jquery.uploader.js',
	'jquery_validate.js'		=> array(
		'/plugins/jquery/jquery-validation-1.8.1/jquery.validate.min.js',
		'/plugins/jquery/jquery-validation-1.8.1/additional-methods.min.js',
		'/plugins/jquery/jquery-validation-1.8.1/localization/messages_{localization}.js',
	),
	'jquery_multidatespicker.js'=> '/plugins/jquery/jquery-ui.multidatespicker.js',
	'reset.css'					=> '/templates/css/reset.css',
	'tinymce.js'				=> '/plugins/tinymce/tinymce.min.js',
        'sisyphus.js'				=> '/plugins/sisyphus/sisyphus.js',
);

//timezone
$config['timezone']			= 'Europe/Moscow';

//папка со стилями
$config['style'] = 'templates';

//charset
$config['charset']			= 'UTF-8';

error_reporting(E_ALL);
//error_reporting(0);
set_error_handler('error_handler');

date_default_timezone_set($config['timezone']);
ini_set('session.cookie_lifetime', 0);
ini_set('magic_quotes_gpc', 0);

header('Content-type: text/html; charset='.$config['charset']);
header('X-UA-Compatible: IE=edge,chrome=1');

//обработчик ошибок
function error_handler($errno,$errmsg,$file,$line) {
	// Этот код ошибки не включен в error_reporting
	if (!(error_reporting() & $errno)) return;
	//не фиксируем простые ошибки
	if ($errno==E_USER_NOTICE) return true;
	//запись в файл
	$log_file_name = 'error_'.date('Y-m').'.txt';
	$err_str = date('d H:i');
	$err_str.= "\tfile://".$file;
	$err_str.= "\t".$line;
	$err_str.= "\thttp://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$err_str.= "\t".$errmsg;
	$err_str.= "\r\n";
	$fp = fopen($log_file_name, 'a');
	fwrite($fp,$err_str);
	fclose($fp);
	//фатальная ошибка
	if ($errno==E_USER_ERROR) exit(1);
	//не запускаем внутренний обработчик ошибок PHP
	return true;
}

$config['types']=array(
	1=>'Олимпиада',
	8=>'Оффлайн-олимпиада',
        //9=>'Онлайн-олимпиада',
        91=>'Онлайн-олимпиада (педагоги)',
        92=>'Онлайн-олимпиада (дошкольники)',
        93=>'Онлайн-олимпиада (школьники)',
        94=>'Онлайн-олимпиада (студенты)',
	2=>'Творческий конкурс',
	3=>'Публикация',
	4=>'Конкурс учителей',
	5=>'Сертификат',
	6=>'Конференция',
	7=>'Рецензия',
);
$config['types_modules']=array(
	1=>'olympiads',
	2=>'contests',
	3=>'publications',
	4=>'tests',
	8=>'olympiads',
        //9=>'online_olympiads',
        91=>'online_olympiads91_tests',
        92=>'online_olympiads92_tests',
        93=>'online_olympiads93_tests',
        94=>'online_olympiads94_tests',
);
$config['paid']=array(
	0=>'неоплаченные',
	1=>'ожидают подтверждения',
	2=>'оплаченные'
);

$config['online_types']=array(
	91=>'Онлайн для педагогов',
	92=>'Онлайн для дошкольников',
	93=>'Онлайн для школьников',
	94=>'Онлайн для студентов'
);
?>

<?php

// загрузка настроек *********************************************************
define('ROOT_DIR', dirname(__FILE__).'/../../');
require_once(ROOT_DIR.'_config.php');	//динамические настройки
require_once(ROOT_DIR.'_config2.php');	//установка настроек

// загрузка функций **********************************************************
//require_once(ROOT_DIR.'functions/admin_func.php');	//функции админки
//require_once(ROOT_DIR.'functions/auth_func.php');	//функции авторизации
require_once(ROOT_DIR.'functions/common_func.php');	//общие функции
//require_once(ROOT_DIR.'functions/file_func.php');	//функции для работы с файлами
//require_once(ROOT_DIR.'functions/html_func.php');	//функции для работы нтмл кодом
require_once(ROOT_DIR.'functions/form_func.php');	//функции для работы со формами
//require_once(ROOT_DIR.'functions/image_func.php');	//функции для работы с картинками
//require_once(ROOT_DIR.'functions/lang_func.php');	//функции словаря
//require_once(ROOT_DIR.'functions/mail_func.php');	//функции почты
require_once(ROOT_DIR.'functions/mysql_func.php');	//функции для работы с БД
//require_once(ROOT_DIR.'functions/string_func.php');	//функции для работы со строками

//пароль магазина
$password = @$config['yandex_shopPassword'];

// чтение параметров
//определение значений формы
$fields = array(
	'action'		=>	'text',
	'md5'			=>	'text',
	'orderNumber'	=>	'text',
	'orderSumAmount'=>	'text',
	'orderSumCurrencyPaycash'   =>	'text',
	'orderSumBankPaycash'       =>	'text',
	'shopId'        =>	'text',
	'invoiceId'     =>	'text',
	'customerNumber'=>	'text',

);
//создание массива $post
$post = form_smart($fields,stripslashes_smart($_POST)); //print_r($post);

$hash = md5(
	$post['action'].';'.
	$post['orderSumAmount'].';'.
	$post['orderSumCurrencyPaycash'].';'.
	$post['orderSumBankPaycash'].';'.
	$post['shopId'].';'.
	$post['invoiceId'].';'.
	$post['customerNumber'].';'.
	$password
);

//тестирование, должно быть закоментировано
/* *
$hash = $post['md5'];
$post['orderNumber'] = 1;
$post['orderSumAmount'] = 100;
$post['invoiceId'] = 2;
$post['shopId'] = 3;
/* */

$file = 'error_'.date('Y-m').'.txt';
$date = date('Y-m-d H:i:s');
$str = "$date; id:".$post['orderNumber']."; total:".$post['orderSumAmount'].";";

$error = '';

// проверка корректности подписи
// check signature
if (strtolower($post['md5'])!=strtolower($hash)) {
	$error = "Значение параметра md5 не совпадает с результатом расчета хэш-функции";
}
else {
	// признак успешно проведенной операции
	// success
	// запись в файл информации о проведенной операции
	// save order info to file
	$query = "SELECT * FROM orders WHERE id=".intval($post['orderNumber']);
	if ($order = mysql_select($query,'row')) {
		if ($order['paid']==1) {
			$str.= '; PAID';
		}
		else {
			mysql_fn('update','orders',array(
				'id'		=> $post['orderNumber'],
				'paid'		=> 1,
				'date_paid'	=> $date,
//				'payment'	=> 3 //admin/config.php $config['payments']
			));
			$file = 'success_'.date('Y-m').'.txt';
		}
	}
	else {
		$error = "В магазине нет заказа с номером ".$post['orderNumber'];
	}
}
$str.= PHP_EOL;

//запись лога
$path = ROOT_DIR.'plugins/yandex_kassa/logs/';
if (is_dir($path) || mkdir ($path,0755,true)) {
	$fp = fopen($path.$file, 'a');
	fwrite($fp,$str);
	fclose($fp);
}

header('Content-type: text/xml; charset=UTF-8');
if ($error=='') {
	?>
<paymentAvisoResponse
	performedDatetime="<?=date('Y-m-d')?>T<?=date('H:i:s')?>.000+04:00"
	code="0"
	invoiceId="<?=$post['invoiceId']?>"
	shopId="<?=$post['shopId']?>"
	/>
<?php
} else {
	?>
<paymentAvisoResponse
	performedDatetime="<?=date('Y-m-d')?>T<?=date('H:i:s')?>.000+04:00"
	code="1"
	message="<?=$error?>"
	/>
<?php
}
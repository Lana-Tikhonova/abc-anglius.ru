<?php

	define('ROOT_DIR', dirname(__FILE__).'/../../');
	require_once(ROOT_DIR.'_config.php');	//динамические настройки
	require_once(ROOT_DIR.'_config2.php');	//установка настроек
	require_once(ROOT_DIR.'functions/common_func.php');	//общие функции
	require_once(ROOT_DIR.'functions/form_func.php');	//функции для работы со формами
	require_once(ROOT_DIR.'functions/mysql_func.php');	//функции для работы с БД

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

	$order = mysql_select("SELECT * FROM orders WHERE id=".intval($post['orderNumber']),'row');
	if($order) {
		$str=	$post['action'].';'.$order['total'].';'.$post['orderSumCurrencyPaycash'].';'
			.$post['orderSumBankPaycash'].';'.$config['yandex_shopId'].';'.$post['invoiceId'].';'
			.$post['customerNumber'].';'.$config['yandex_shopPassword'];
		$hash = md5($str);
		if (strtoupper($hash) != strtoupper($post['md5'])){
			$code = 1;
		}
		else {
			$code = 0;
		}
	}
	else {
		$code=100;
	}
	print '<?xml version="1.0" encoding="UTF-8"?>';
	print '<checkOrderResponse performedDatetime="'.date('Y-m-d').'T'.date('H:i:s').'.000+04:00" code="'.$code.'"'. ' invoiceId="'. $post['invoiceId'] .'" shopId="'. $config['yandex_shopId'] .'"/>';
?>

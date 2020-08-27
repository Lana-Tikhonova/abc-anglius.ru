<?php

//обрезание обратных слешев в $_REQUEST данных
function stripslashes_smart($post) {
	if (get_magic_quotes_gpc()) {
		if (is_array($post)) {
			foreach ($post as $k=>$v) {
				$q[$k] = stripslashes_smart($v);
			}
		}
		else $q = stripslashes($post);
	}
	else $q = $post;
	return $q;
}

//создание урл из $_GET
function build_query($key = '') {
	$get = $_GET;
	if ($key) {
		$array = explode(',',$key);
		foreach ($array as $k=>$v) unset($get[$v]);
	}
	return http_build_query($get);
}

function isOnlineOlympiads($type){
    global $config;
    return in_array($type, array_keys($config['online_types']));
}

function htmlchars($str, $double_encoding = false, $encoding = NULL){
    return htmlspecialchars((string)$str, ENT_QUOTES, ($encoding ? $encoding :ini_get("default_charset")), $double_encoding);
}

function getOnlineReceipt($order) {
	
	if (empty($order['email'])) {
		$u = mysql_select("SELECT * FROM users WHERE id = {$order['user']} LIMIT 1", 'row');
		$order['email'] = empty($u['email']) ? 'admin@intel-academy.ru' : $u['email'];
	}
	
	
	$receipt = [
		'customerContact' => $order['email'],
		'taxSystem' => 6,
		'items' => [
			[				
				'quantity' => 1.000,
				'price' => [
					'amount' => number_format($order['total'], 2,'.', ''),					
					//'currency' => 'RUB',					
				], 	
				'tax' => 1,
				'text' => 'Оплата услуги по заявке №'. $order['id'],
				'paymentMethodType' => 'full_prepayment',
				'paymentSubjectType' => 'another'
			]
		],		
	];
	
	return json_encode($receipt, JSON_UNESCAPED_UNICODE);	
}

?>
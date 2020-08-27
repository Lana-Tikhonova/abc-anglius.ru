<?php
define('ROOT_DIR', dirname(__FILE__).'/');
require_once(ROOT_DIR.'_config.php');	//динамические настройки
require_once(ROOT_DIR.'_config2.php');	//установка настроек
require_once(ROOT_DIR.'functions/mysql_func.php');	//функции для работы с БД
require_once(ROOT_DIR.'functions/string_func.php');
require_once(ROOT_DIR.'functions/lang_func.php');	//функции словаря
require_once(ROOT_DIR.'functions/html_func.php');
require_once(ROOT_DIR.'functions/auth_func.php');

//основной язык
$lang = lang(1);

$error=0;
$filename = "./temp.zip";
list($orderid,$number)=explode('.',base64_decode($_GET['key']));

$order=mysql_select('
	select o.* from orders o
	left join olympiads ol on ol.id=o.parent
	where o.id='.($orderid+0).' and (o.type=1 or o.type=8) and o.paid=1','row');
if($order) {
	$basket=unserialize($order['basket']);
	$zip = new ZipArchive();
	unlink($filename);
	
	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		$error++;
	}
	else {
		foreach($basket['results'] as $k=>$v) {
		    $fname = 'https://'.$_SERVER['HTTP_HOST'].'/certificate.php?key='.base64_encode($orderid.'.'.$k);
		    //echo $fname . '<br>'; 
		    $zip->addFromString($k.'.jpg',file_get_contents($fname, false, stream_context_create(
				    array("ssl"=>array(
					"verify_peer"=>false,
					"verify_peer_name"=>false,
					)
				    )
				)));
		}
		if($zip->status!=0||$zip->numFiles==0) $error++;
	}
	$zip->close();
}
else {
	$error++;
}
if($error){
	header("HTTP/1.0 404 Not Found");
	$page['title'] = $page['name'] = i18n('common|str_no_page_name');
	$html['module'] = 'error';
	require_once(ROOT_DIR.$config['style'].'/includes/common/template.php');
}
else {
    //die();
	header('Content-Type: application/zip');
	header("Content-Disposition: attachment; filename=\"diploms.zip\"");
	header("Cache-Control: max-age=0");
	readfile($filename);
}
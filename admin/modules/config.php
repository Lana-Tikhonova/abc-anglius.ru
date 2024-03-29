<?php

/**
 * динамические настройки сайта
 * хранятся в /config.php
 */

$pattern = 'one_form';

$get['id']='config';
if ($get['u']!='edit') $post = $config;
if ($get['u']=='edit') {
	$content = "<?php\r\n";
	foreach($post as $k=>$v)
		$content.= '$config[\''.$k.'\']=\''.str_replace("'","\'",$v).'\';'."\r\n";
	$content.= "?>";
	$fp = fopen(ROOT_DIR.'_config.php', 'w');
	fwrite($fp,$content);
	fclose($fp);
	if($post['cache']==0) {
		delete_all('cache',true);
	}
	$data['error']	= '';
	//print_r($data);
	echo '<textarea>'.htmlspecialchars(json_encode($data)).'</textarea>';
	die();
}

$tabs = array(
	1=>'Общее',
	2=>'Платежные агрегаторы',
//	3=>'Индексация',
//	4=>'Sitemap',
	5=>'Призовые места',
	7=>'Призовые места онлайн',
	6=>'Цены онлайн теста',        
);


$form[1][] = array('input td4','sender',true,array('name'=>'глобальный email отправителя письма'));
$form[1][] = array('input td4','receiver',true,array('name'=>'глобальный email получателя письма'));
$form[1][] = '<br /><a href="/admin.php?m=letter_templates">настроить</a>';
$form[1][] = array('input td12','conferences',true,array('name'=>'url для конференций'));
$form[1][] = array('checkbox td12','cache',true,array('name'=>'включить кеширование сайта'));
$form[1][] = array('checkbox td12','redirects',true,array('name'=>'включить редиректы <a target="_blank" href="/admin.php?m=redirects">настроить пути</a>'));
$form[1][] = array('checkbox td12','editable',true,array('name'=>'включить быстрое редактирование с сайта <a target="_blank" href="/admin.php?m=user_types">настроить права доступа</a>'));
$form[1][] = array('checkbox td12','dummy',true,array('name'=>'включить заглушку для сайта (доступ на сайт будут иметь только администраторы)'));
$form[1][] = array('checkbox td12','uploader',true,array('name'=>'включить загрузку файлов через html5'));

//$form[2][] = '<div style="clear:both"><br /><b>ROBOKASSA</b> <a href="http://robokassa.ru/" target="_blank">http://robokassa.ru/</a></div>';
//$form[2][] = array('input td4','robokassa_login',true,array('name'=>'логин'));
//$form[2][] = array('input td4','robokassa_password1',true,array('name'=>'пароль1'));
//$form[2][] = array('input td4','robokassa_password2',true,array('name'=>'пароль2'));
//$modules['basket'] = mysql_select("SELECT url FROM pages WHERE module='basket'",'string');
//$form[2][] = '<br /><b>Result URL:</b> http://'.$_SERVER['HTTP_HOST'].'/plugins/robokassa/result.php';
//$form[2][] = '<br /><b>Success URL:</b> http://'.$_SERVER['HTTP_HOST'].'/'.$modules['basket'].'/success/';
//$form[2][] = '<br /><b>Fail URL:</b> http://'.$_SERVER['HTTP_HOST'].'/'.$modules['basket'].'/fail/';

$form[2][] = '<div style="clear:both"><br /><br /><b>YANDEX</b> <a href="https://kassa.yandex.ru/" target="_blank">https://kassa.yandex.ru/</a></div>';
$form[2][] = array('input td2','yandex_shopId',true,array('name'=>'идентификатор магазина'));
$form[2][] = array('input td2','yandex_scid',true,array('name'=>'идентификатор витрины'));
$form[2][] = array('input td3','yandex_customerNumber',true,array('name'=>'идентификатор плательщика'));
$form[2][] = array('input td2','yandex_shopPassword',true,array('name'=>'пароль'));
$form[2][] = array('input td3','yandex_paymethods',true,array('name'=>'методы оплаты через запятую'));
//$modules['basket'] = mysql_select("SELECT url FROM pages WHERE module='basket'",'string');
//$modules['basket']='plugins/yandex_kassa';
$form[2][] = '<br /><b>Check URL:</b> http://'.$_SERVER['HTTP_HOST'].'/plugins/yandex_kassa/check.php';
$form[2][] = '<br /><b>Result URL:</b> http://'.$_SERVER['HTTP_HOST'].'/plugins/yandex_kassa/result.php';
//$form[2][] = '<br /><b>Success URL:</b> http://'.$_SERVER['HTTP_HOST'].'/'.$modules['basket'].'/success/';
//$form[2][] = '<br /><b>Fail URL:</b> http://'.$_SERVER['HTTP_HOST'].'/'.$modules['basket'].'/fail/';
$form[2][] = '<br /><a href="https://tech.yandex.ru/money/doc/payment-solution/reference/payment-type-codes-docpage/" target="_blank"><b>Документация по методам оплаты</b></a>';

$form[3][] = '<div style="clear:both"><br /><b>ЯНДЕКС</b> <a href="https://xml.yandex.ru/settings/" target="_blank">https://xml.yandex.ru/settings/</a></div>';
$form[3][] = array('input td4','yandex_user',true,array('name'=>'логин яндекс для xmlsearch'));
$form[3][] = array('input td8','yandex_key',true,array('name'=>'ключ яндекс для xmlsearch'));
$form[3][] = '<br />Для автоматической проверки страниц в индексе яндекса, нужно включить задачу cron: <a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'/cron.php?file=yandex_index">http://'.$_SERVER['HTTP_HOST'].'/cron.php?file=yandex_index</a>';

$form[4][] = array('select td4','sitemap_generation',array(true,
	array(0=>'не генерировать',1=>'все страницы',2=>'только не проиндексированные')),
	array('name'=>'генерация sitemap.xml')
);
$form[4][] = '
<div class="clear"></div>
<b>1. не генерировать</b> - статический файл <a target="_blank" href="/sitemap.xml">sitemap.xml</a>
<br /><b>2. все страницы</b> - файл <a target="_blank" href="/sitemap.xml">sitemap.xml</a> будет генерироваться автоматически с url всех страниц
<br /><b>3. только не проиндексированные</b> - файл <a target="_blank" href="/sitemap.xml">sitemap.xml</a> будет генерироваться автоматически с урл не проиндексированных страниц
(<a target="_blank" href="/admin.php?m=config#3">настроить индексацию</a>)
';

$form[5][] = array('input td4','percent1',true,array('name'=>'1 место'));
$form[5][] = array('input td4','percent2',true,array('name'=>'2 место'));
$form[5][] = array('input td4','percent3',true,array('name'=>'3 место'));
$form[5][] = '
<div class="clear"></div>
Указывается количество правильных ответов необходимое для получения диплома соответствующей степени.<br>Количество ответов указывается в процентах (без знака %, разделитель дробной части точка).
';

$form[7][] = array('input td4','percent_online1',true,array('name'=>'1 место'));
$form[7][] = array('input td4','percent_online2',true,array('name'=>'2 место'));
$form[7][] = array('input td4','percent_online3',true,array('name'=>'3 место'));
$form[7][] = '
<div class="clear"></div>
Указывается количество правильных ответов необходимое для получения диплома соответствующей степени.<br>Количество ответов указывается в процентах (без знака %, разделитель дробной части точка).
';

for($i=91;$i<=94;$i++)
  $form[6][] = array('input td6',"price$i",true,array('name'=>$config['types'][$i].', руб.'));

<?php

define('ROOT_DIR', dirname(__FILE__).'/../');
include_once (ROOT_DIR.'_config2.php');
include_once (ROOT_DIR.'functions/image_func.php');
include_once (ROOT_DIR.'functions/common_func.php');
include_once (ROOT_DIR.'functions/mysql_func.php');

//echo ROOT_DIR;

mysql_connect_db();

//список скл запросов
$queries = array(
	/*
	"CREATE TABLE IF NOT EXISTS `online_olympiads` (
		`id` int(10) unsigned NOT NULL,
		`category` int(11) NOT NULL,
		`type` tinyint(3) unsigned NOT NULL DEFAULT '1',
		`rank` smallint(5) unsigned NOT NULL DEFAULT '1',
		`display` tinyint(1) unsigned NOT NULL DEFAULT '1',
		`img` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`text` text COLLATE utf8_unicode_ci NOT NULL,
		`url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`kl1` tinyint(1) unsigned DEFAULT '0',
		`kl2` tinyint(1) unsigned DEFAULT '0',
		`kl3` tinyint(1) unsigned DEFAULT '0'
	  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
	"ALTER TABLE `online_olympiads`
		ADD PRIMARY KEY (`id`),
		ADD KEY `display` (`display`),
		ADD KEY `rank` (`rank`),
		ADD KEY `type` (`type`)",
	"ALTER TABLE `online_olympiads`
		MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1",
	
	"CREATE TABLE IF NOT EXISTS `online_olympiads_categories` (
		`id` int(10) unsigned NOT NULL,
		`rank` smallint(5) unsigned NOT NULL DEFAULT '1',
		`teacher` tinyint(1) unsigned NOT NULL,
		`display` tinyint(1) unsigned NOT NULL DEFAULT '1',
		`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`price` int(10) unsigned NOT NULL DEFAULT '0',
		`price2` int(10) unsigned NOT NULL DEFAULT '0',
		`price3` int(10) unsigned NOT NULL DEFAULT '0',
		`date1` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`date2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`summarizing` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`text` text COLLATE utf8_unicode_ci NOT NULL,
		`img` varchar(255) COLLATE utf8_unicode_ci NOT NULL
	  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
	"ALTER TABLE `online_olympiads_categories`
		ADD PRIMARY KEY (`id`),
		ADD KEY `rank` (`rank`),
		ADD KEY `display` (`display`)",
	"ALTER TABLE `online_olympiads_categories`
		MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1",
	
	"CREATE TABLE IF NOT EXISTS `online_olympiads_tests` (
		`id` int(10) unsigned NOT NULL,
		`olympiad` int(10) unsigned NOT NULL,
		`rank` smallint(5) unsigned NOT NULL DEFAULT '1',
		`display` tinyint(1) unsigned NOT NULL DEFAULT '1',
		`klass` tinyint(1) unsigned NOT NULL DEFAULT '0',
		`qa` text COLLATE utf8_unicode_ci NOT NULL
	  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
	"ALTER TABLE `online_olympiads_tests`
		ADD PRIMARY KEY (`id`),
		ADD KEY `olympiad` (`olympiad`),
		ADD KEY `rank` (`rank`),
		ADD KEY `display` (`display`)",
	"ALTER TABLE `online_olympiads_tests`
		MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1",
	*/
	"ALTER TABLE `pages` ADD `menu3` TINYINT(1) NOT NULL DEFAULT '0' AFTER `menu2`"
);
foreach ($queries as $query) {
	if ($query) {
		if (mysql_query($query)) echo '<div style="color:#00f">'.$query.'</div>';
		else echo '<div style="color:#f00">'.$query.' - '.mysql_error().'</div>';
	}
}/**/



die();
/*
//добавление слов
$langs = array(
	'shop' =>	array(
		'qwert' =>	'йцуке'

	),
	'common' =>	array(
		'qwert' =>	'йцуке',
	)
);
$id = 1;
foreach ($langs as $key=>$val) {
	echo '<b>'.$key.'</b><br />';
	print_r($val);
	echo '<br /><br />';
	$lang = array();
	include(ROOT_DIR.'files/languages/'.$id.'/dictionary/'.$key.'.php');
	$lang[$key] = array_merge($lang[$key],$val);
	//print_r($lang[$key]);
	$str = '<?php'.PHP_EOL;
	$str.= '$lang[\''.$key.'\'] = array('.PHP_EOL;
	foreach ($lang[$key] as $k=>$v) {
		$str.= "	'".$k."'=>'".str_replace("'","\'",$v)."',".PHP_EOL;
	}
	$str.= ');';
	$str.= '?>';
	$fp = fopen(ROOT_DIR.'files/languages/'.$id.'/dictionary/'.$key.'.php', 'w');
	fwrite($fp,$str);
	fclose($fp);
}
/**/

//ресайз картинок

$table = 'gallery';
$param = array('p-'=>'cut 250x175');
$img = 'img';
$images = 'images';

$query = "SELECT * FROM $table ORDER BY id";
$result = mysql_query($query);
while ($q=mysql_fetch_assoc($result)) {
	echo $q['id'].'<br />';
	if ($q['img']) {
		$path = $table.'/'.$q['id'].'/img';
		$root = ROOT_DIR.'files/'.$path.'/';
		if (is_file($root.$q['img'])) {

			foreach ($param as $k=>$v) {
				$prm = explode(' ',$v);
				img_process($prm[0],$root.$q['img'],$prm[1],$root.$k.$q['img']);
				//если есть водяной знак
				//if (isset($prm[2])) img_watermark($root.$k.$q['img'],ROOT_DIR.'templates/images/'.$prm[2],$root.$k.$q['img'],@$prm[3]);
			}
		}
	}
	if ($q[$images]) {
		$imgs = unserialize($q[$images]);
		$path = $table.'/'.$q['id'].'/'.$images;
		echo '<br />'.$path;
		$root = ROOT_DIR.'files/'.$path.'/';
		//$param = array('p-'=>'cut 360x270');
		if (is_dir($root) AND $handle = opendir($root)) {
			while (false !== ($file = readdir($handle))) {
				if (isset($imgs[$file])) {
					$v1 = $root.$file.'/'.$imgs[$file]['file'];
					echo '<br />'.$v1;
					foreach ($param as $k=>$v) {
						$prm = explode(' ',$v);
						echo '<br />'.$v1;
						img_process($prm[0],$root.$file.'/'.$imgs[$file]['file'],$prm[1],$root.$file.'/'.$k.$imgs[$file]['file']);
						//если есть водяной знак
						//if (isset($prm[2])) img_watermark($root.$file.'/'.$k.$imgs[$file]['file'],ROOT_DIR.'templates/images/'.$prm[2],$root.$file.'/'.$k.$imgs[$file]['file'],@$prm[3]);
					}
				}
			}
			closedir($handle);
		}
	}
}

/**/


/*
//из jsona c serilize
$data = mysql_select("SELECT id,dictionary FROM languages",'rows');
foreach ($data as $k=>$v) {
	$dictionary = json_decode($v['dictionary'],true);
	$v['dictionary'] = serialize($dictionary);
	mysql_fn('update','languages',$v);
}
$data = mysql_select("SELECT id,images FROM shop_products",'rows');
$data = mysql_select("SELECT id,`values` FROM shop_parameters",'rows');
$data = mysql_select("SELECT id,basket FROM orders",'rows');
$data = mysql_select("SELECT id,parameters FROM shop_categories",'rows');
$data = mysql_select("SELECT id,fields FROM users",'rows');
$data = mysql_select("SELECT id,access_admin FROM user_types",'rows');
*/


//обновление слов
/*
$data = mysql_select("SELECT id,dictionary FROM languages WHERE id=1",'row');
$dictionary = unserialize($data['dictionary'],true);
$dictionary['wrd_tutor_found']='Ученик нашел репетитора';
$data['dictionary'] = serialize($dictionary);
mysql_fn('update','languages',$data);
/**/

/*
//пересохранение словаря с массива в файлы
$data = mysql_select("SELECT id,dictionary FROM languages WHERE id=1",'row');
$dictionary = unserialize($data['dictionary']);
//print_r($dictionary);
$lang = array();
foreach ($dictionary as $k=>$v) {
	//echo '-'.$k.'-'.substr($k,5).'<br />';
	$str = substr($k,0,4);
	if ($str=='shop') {
		$lang['shop'][substr($k,5)] = $v;
	}
	elseif ($str=='revi') {
		$lang['shop'][$k] = $v;
	}
	elseif ($str=='prof') {
		$lang['profile'][substr($k,8)] = $v;
	}
	elseif ($str=='bask') {
		$lang['basket'][substr($k,7)] = $v;
	}
	elseif ($str=='subs') {
		$lang['subscribe'][substr($k,10)] = $v;
	}
	elseif ($str=='mark') {
		$lang['market'][substr($k,7)] = $v;
	}
	elseif ($str=='feed') {
		$lang['feedback'][substr($k,9)] = $v;
	}
	elseif ($str=='msg_') {
		$lang['validate'][substr($k,4)] = $v;
	}
	else $lang['common'][$k] = $v;

}
foreach ($lang as $key=>$val) {
	$str = '<?php'.PHP_EOL;
	$str.= '$lang[\''.$key.'\'] = array('.PHP_EOL;
	foreach ($val as $k=>$v) {
		$str.= "	'".$k."'=>'".str_replace("'","\'",$v)."',".PHP_EOL;
	}
	$str.= ');';
	$str.= '?>';
	$fp = fopen(ROOT_DIR.'files/languages/1/dictionary/'.$key.'.php', 'w');
	fwrite($fp,$str);
	fclose($fp);
}
print_r($lang);
/**/


?>

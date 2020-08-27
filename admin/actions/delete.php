<?php

if (access('admin delete')==false) die('у вас нет доступа к удалению!');

$message = '';
//типы удаления
$array = array(
	//'file',	//удаление файла из подпапки (загрузка при помощи simple)
	'key',	//удаление ключа и файла  (загрузка при помощи mysql)
	'id'	//удаление записи и всех файлов
);

$get['type']	= isset($_GET['type']) ? $_GET['type'] : '';			//вид удаления
//$get['m'] = isset($_GET['m']) ? $_GET['m'] : '';					//модуль или папка
$get['id']		= isset($_GET['id']) ? abs(intval($_GET['id'])) : 0;	//индекс
$get['key'] 	= isset($_GET['key']) ? $_GET['key'] : '';				//ключ в БД
$file	= isset($_GET['file']) ? $_GET['file'] : '';			//имя файла

if (!in_array($get['type'],$array)) 			die ('ошибка типа удаления!');
if ($get['id']==0)								die ('ошибка индекса!');
if (!preg_match("/^[a-z0-9_]+$/",$get['m']))	die ('ошибка модуля!');
if (!preg_match("/^[a-z0-9_]*$/",$get['key']))	die ('ошибка ключа!');
//if (!preg_match("/^[a-z0-9_.]*$/",$file))	die ('ошибка имени файла!');

if (file_exists(ROOT_DIR.'admin/modules/'.$get['m'].'.php')) {
	require_once(ROOT_DIR.'admin/modules/'.$get['m'].'.php');
}
//удаление файла из подпапки (загрузка при помощи simple) - похоже уже не используется
/*if ($get['type']=='file') {
	$dir	= 'files/'.$module['table'].'/'.$get['id'].'/'.$get['key'].'/';
	$path	= ROOT_DIR.$dir.$file;
	echo $path;
	if (is_file($path)) {
		if (unlink($path)) {
			$message = '1';
			if (is_dir(ROOT_DIR.$dir) && $handle = opendir(ROOT_DIR.$dir)) {
				while (false !== ($folder = readdir($handle))) {
					if ($folder!='.' && $folder!='..') {
						if (is_dir(ROOT_DIR.$dir.$folder))
							if (is_file(ROOT_DIR.$dir.$folder.'/'.$file))
								unlink(ROOT_DIR.$dir.$folder.'/'.$file);
					}
				}
				closedir($handle);
			}
		}
		else $message = "не удалось удалить файл!";
	}
	else $message = "нет такого файла!";//."files/$get['m']/$get['id']/$get['key']/$file";
}*/
//удаление ключа и файла  (загрузка при помощи mysql)
if ($get['type']=='key') {
	$path = ROOT_DIR.'files/'.$module['table'].'/'.$get['id'].'/'.$get['key'].'/';
	if (is_dir($path)) {
		delete_all($path);
		if (is_dir($path)) $message = 'файл удален, но запись о файле осталась!';
		else $message = "не удалось удалить файл!";
	}
	if (!is_dir($path)) {
		if (mysql_fn('update',$module['table'],array($get['key']=>'','id'=>$get['id']))) $message = '1';
		else $message = 'не удалось удалить файл';
	}
}
//удаление записи и всех файлов
elseif ($get['type']=='id') {
	if (is_array($delete)) {
		if ($content = html_delete($delete)) die(strip_tags($content));
	}
	if (strlen($module['table'])<3 OR $get['id']==0) die ('удаление невозможно!');
	$item =  mysql_select("SELECT * FROM `".$module['table']."` WHERE id = '".$get['id']."'",'row');
	if ($item==false) die('нет такой записи');
	mysql_fn('delete',$module['table'],$get['id']);
	//nested sets - пересортировка
	if (array_key_exists('level',$q)) {
		$where = '';
		if (isset($filter) && is_array($filter)) foreach ($filter as $k=>$v) {
			$where.= " AND `".$v[0]."` = ".intval($q[$v[0]]);
		}
	 	mysql_query("
	 		UPDATE `".$module['table']."`
			SET left_key = CASE WHEN left_key > ".$q['left_key']."
								THEN left_key - 2
								ELSE left_key END,
				right_key = right_key-2
			WHERE right_key > ".$q['right_key']." AND level>0".$where
		);
	}
	//depend - удаление связей
	if (isset($config['depend'][$module['table']])) {
		foreach ($config['depend'][$module['table']] as $k=>$v) {
			mysql_fn('delete',$v,false," AND child = '".intval($get['id'])."'");
		}
	}
	//проверка удаления
	$num_rows = mysql_select("SELECT id FROM `".$module['table']."` WHERE `id` = ".$get['id']." LIMIT 1",'num_rows');
	if ($num_rows==0) {
		//$message = 'запись удалена!';
		$path = ROOT_DIR.'files/'.$module['table'].'/'.$get['id'];
		delete_all($path);
		if (is_dir($path)) $message = 'не удалось удалить папку';//$message = "все картинки, связанные с записью, удалены! [files/$get['m']/$get['id']]";
		//удаление из связных таблиц
		if (isset($delete['delete'])) {
			if (is_array($delete['delete'])) foreach ($delete['delete'] as $k=>$v) mysql_query($v);
			else mysql_query($delete['delete']);
		}
		//удаление file_multi_db
		if (count($tabs) > 0) {
			foreach ($form as $k=>$v) {
				foreach ($v as $k1=>$v1) {
					if (is_array($v1) && $v1[0]=='file_multi_db') {
						if ($file_multi_db = mysql_select("SELECT id FROM `".$v1[1]."` WHERE parent=".$get['id'],'rows')) {
							mysql_fn('delete',$v1[1],$get['id']);
							foreach($file_multi_db as $row) {
								delete_all(ROOT_DIR.'files/'.$v1[1].'/'.$row['id'],true);
							}
						}
					}
				}
			}
		}
		else {
			foreach ($form as $k=>$v) {
				if (is_array($v) && $v[0]=='file_multi_db') {
					if ($file_multi_db = mysql_select("SELECT id FROM `".$v[1]."` WHERE parent=".$get['id'],'rows')) {
						mysql_fn('delete',$v[1],$get['id']);
						foreach($file_multi_db as $row) {
							delete_all(ROOT_DIR.'files/'.$v[1].'/'.$row['id'],true);
						}
					}
				}
			}
		}
		//логирование
		$logs = array(
			'user'		=> $user['id'],
			'date'		=> date('Y-m-d H:i:s'),
			'parent'	=> $get['id'],
			'module'	=> $module['table'],
			'type'		=> 3
		);
		mysql_fn('insert','logs',$logs);
		$message = 1;
	}
	else $message = 'удаление невозможно!';
}
else $message = 'ошибка!';

echo $message;
die();

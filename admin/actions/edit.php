<?php

// EDIT - РЕДАКТИРОВАНИЕ ЗАПИСИ
//создание массива post и его бработка
$post = stripslashes_smart($_POST); //error_handler(1,serialize($post),1,1);
$data = array();
//генерация SEO-полей
if (isset($post['seo'])) {
	if($post['seo']==1) {
		$data['seo'] = array();
		$data['seo']['url'] = $post['url'] = trunslit($post['name']);
		if (isset($post['title'])) $data['seo']['title'] = $post['title'] = $post['name'];
		if (isset($post['description'])) $data['seo']['description'] = $post['description'] = description((isset($post['about']) ? $post['about'].' ' : '').(isset($post['text']) ? $post['text'].' ' : '').$post['name']);
		if (isset($post['keywords'])) $data['seo']['keywords'] = $post['keywords'] = keywords($post['name'].' '.(isset($post['description']) ? $post['description'].' ' : '').(isset($post['about']) ? $post['about'].' ' : '').(isset($post['text']) ? $post['text'] : ''));
	        
	        // if ($config['multilingual']) {
	        //     $config['languages'] = mysql_select("SELECT id,name FROM languages ORDER BY display DESC, rank DESC", 'rows');
	        //     foreach ($config['languages'] as $k => $v) if ($k == 0) {
	        //         if (isset($post['title'])) $post['title' . $v['id']] = $data['seo']['title'];
	        //         if (isset($post['description'])) $post['description' . $v['id']] = $data['seo']['description'];
	        //         if (isset($post['keywords'])) $post['keywords' . $v['id']] = $data['seo']['keywords'];
	        //     } else {
	        //         if (isset($post['title' . $v['id']])) $data['seo']['title' . $v['id']] = $post['title' . $v['id']] = $post['name' . $v['id']];
	        //         if (isset($post['description' . $v['id']])) $data['seo']['description' . $v['id']] = $post['description' . $v['id']] = description((isset($post['about' . $v['id']]) ? $post['about'] . ' ' : '') . (isset($post['text' . $v['id']]) ? $post['text' . $v['id']] . ' ' : '') . $post['name' . $v['id']]);
	        //         if (isset($post['keywords' . $v['id']])) $data['seo']['keywords' . $v['id']] = $post['keywords' . $v['id']] = keywords($post['name' . $v['id']] . ' ' . (isset($post['description' . $v['id']]) ? $post['description' . $v['id']] . ' ' : '') . (isset($post['about' . $v['id']]) ? $post['about' . $v['id']] . ' ' : '') . (isset($post['text' . $v['id']]) ? $post['text' . $v['id']] . ' ' : '') . (isset($post['text_two' . $v['id']]) ? $post['text_two' . $v['id']] : ''));
	        //     }
	        // }

	}
	unset($post['seo']);
}
//дерево сложенности
if (isset($post['nested_sets'])) unset($post['nested_sets']);
//депенды
if (isset($config['depend'][$module['table']])) foreach ($config['depend'][$module['table']] as $key=>$value)
	$post[$key] = isset($post[$key]) ? implode(',',$post[$key]) : '';

//загрузка модуля
require_once(ROOT_DIR.'admin/modules/'.$get['m'].'.php');
//если дерево то удаляем родителя и предыдущего
if (is_array($form)) {
	if (count($tabs)>0) {
		foreach ($form as $k=>$v)
			foreach ($v as $k1=>$v1) {
				if (is_array($v1) && preg_match('/simple|file_multi/',$v1[0])) $post[$v1[1]] = isset($post[$v1[1]]) ? serialize($post[$v1[1]]) : '';
				//удаляем данные о file_multi_db
				if (is_array($v1) && $v1[0]=='file_multi_db' AND isset($post[$v1[1]])) unset($post[$v1[1]]);
			}
	} else {
		foreach ($form as $k => $v) {
			if (is_array($v) && preg_match('/simple|file_multi/', $v[0])) $post[$v[1]] = isset($post[$v[1]]) ? serialize($post[$v[1]]) : '';
			//удаляем данные о file_multi_db
			if (is_array($v) && $v[0]=='file_multi_db' AND isset($post[$v[1]])) unset($post[$v[1]]);
		}
	}
}

//редактирование текущей записи
if ($get['id']>0) {
	$post['id']=$get['id'];
	mysql_fn('update',$module['table'],$post);
	$logs['type'] = 2;
//создание новой записи
} else {
	$post['id'] = $get['id'] = mysql_fn('insert',$module['table'],$post);
	$logs['type'] = 1;
}
$error = mysql_affected_rows()==1 ? 0 : mysql_error();

//функция после сохранения
if ($error==0 AND function_exists('after_save')) {
	after_save();
}

//логирование действия
//if ($error===0) {
	mysql_fn('insert','logs',array(
		'user'		=> $user['id'],
		'date'		=> date('Y-m-d H:i:s'),
		'parent'	=> $get['id'],
		'module'	=> $module['table'],
		'type'		=> $logs['type']
	));
//}

//обработка депендов
if (isset($config['depend'][$module['table']])) foreach ($config['depend'][$module['table']] as $key=>$value) {
	$depend = mysql_select("SELECT id,parent name FROM `".$value."` WHERE child = '".intval($get['id'])."'",'array');
	if ($depend==false) $depend = array();
	if ($post[$key]=='' AND count($depend)>0) mysql_query("DELETE FROM `$value` WHERE child = '".intval($get['id'])."'");
	elseif ($post[$key]) {
		$depend2 = explode(',',$post[$key]);
		foreach ($depend2 as $k=>$v) {
			if (!in_array($v,$depend))
				mysql_query("INSERT INTO `$value` SET child = '".intval($get['id'])."',parent = '".intval($v)."'");
		}
		foreach ($depend as $k=>$v)
			if (is_array($depend2) AND !in_array($v,$depend2))
				mysql_query("DELETE FROM `$value` WHERE id = '".$k."' LIMIT 1");
	}
}

//копирование всех файлов когда сохранить как
if (@$_GET['save_as']>0) {
	rcopy(ROOT_DIR.'files/'.$module['table'].'/'.intval($_GET['save_as']).'/', ROOT_DIR.'files/'.$module['table'].'/'.intval($get['id']).'/');
}

//загрузка файлов
if (is_array($form)) {
	if (count($tabs) > 0) {
		foreach ($form as $k=>$v) {
			foreach ($v as $k1=>$v1) {
				if (is_array($v1) && preg_match('/mysql|simple|file|file_multi|file_multi_db/',$v1[0])) {
					//копирование папок file_multi_db
					if ($v1[0]=='file_multi_db' AND @$_GET['save_as']>0) {
						$file_multi_db = mysql_select("SELECT * FROM `".$v1[1]."` WHERE parent=".intval($_GET['save_as']),'rows');
						foreach($file_multi_db as $row) {
							//старая папка
							$old = ROOT_DIR."files/".$v1[1]."/".$row['id']."/";
							//добавляем запись в БД
							unset($row['id']);
							$row['parent'] = $get['id'];
							$row['id'] = mysql_fn('insert',$v1[1],$row);
							//новая папка
							$new = ROOT_DIR."files/".$v1[1]."/".$row['id']."/";
							if(is_dir($new)||mkdir($new,0755,true)) {
								rcopy($old, $new);
							}
						}
					}

					$data['files'][$v1[1]] = call_user_func_array('form_file', $v1);
					//обновление картинки file в ряде
					if (current(explode(' ',$v1[0]))=='file') $q[$v1[1]] = $post[$v1[1]];
				}
			}
		}
	}
	else {
		foreach ($form as $k=>$v) {
			if (is_array($v) && preg_match('/mysql|simple|file|file_multi|file_multi_db/',$v[0])) {
				$data['files'][$v[1]] = call_user_func_array('form_file', $v);
				//обновление картинки file в ряде
				if (current(explode(' ',$v[0]))=='file') $q[$v[1]] = $post[$v[1]];
			}
		}
	}
}

//запрос на ряд для одной записи
$query_row = $query ? $query." AND ".$module['table'].".id = '".$get['id']."'" : "SELECT * FROM ".$module['table']." WHERE id = '".$get['id']."'";
$q = mysql_select($query_row,'row');
//для nested_sets при создании новой записи
$data['table'] = '';
if (array_key_exists('level',$q)) {
	if ($_GET['id']=='new') {
		$q['level'] = 1;
		$where = '';
		//если есть фильтр (например, для языка)
		if (isset($filter) && is_array($filter)) foreach ($filter as $k=>$v) {
			$where.= " AND `".$v[0]."` = ".intval($q[$v[0]]);
		}
		$max = mysql_select("SELECT IFNULL(MAX(right_key),0) FROM ".$module['table']." WHERE 1 ".$where,'string'); echo mysql_error();
		mysql_query("UPDATE ".$module['table']." SET level=1,left_key=".$max."+1,right_key=".$max."+2 WHERE id = ".$get['id']); echo mysql_error();
	}
	//перемещение дерева
	if (isset($_POST['nested_sets']['on']) AND $_POST['nested_sets']['on']==1) {
		if ($_POST['nested_sets']['previous']) nested_sets($module['table'],$_POST['nested_sets']['previous'],$q['id'],'prev',$filter);
		else nested_sets($module['table'],@$_POST['nested_sets']['parent'],$q['id'],'parent',$filter);
		if (isset($table) AND is_array($table)) {
			$where = '';
			if (isset($filter) && is_array($filter)) foreach ($filter as $k=>$v) {
				$where.= " AND ".$module['table'].".".$v[0]." = '".$q[$v[0]]."'";
			}
			$query = $query ? $query.$where : "SELECT ".$module['table'].".* FROM ".$module['table']." WHERE 1 ".$where;
			$data['table'] = table($table,$query);
		}
	}
}

//создание ряда
$data['tr'] = (is_array($table) AND $data['table']=='') ? table_row($table,$q) : '';
if ($_GET['id']=='new') $data['tr'] = (isset($q['parent']) ? '<tr data-id="'.$q['id'].'" data-parent="'.$q['parent'].'" data-level="'.$q['level'].'" class="a">' : '<tr data-id="'.$q['id'].'" data-parent="0" data-level="1" class="a">').$data['tr'].'</tr>';
$data['error']	= $error;
$data['id']		= $get['id'];
//print_r($data);
echo '<textarea>'.json_encode($data).'</textarea>';

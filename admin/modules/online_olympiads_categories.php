<?php

$a18n['teacher']='тип';

//$teacher = $config['teacher'];

$table = array(
	'id'		=>	'rank:desc id',
	//'img'		=>	'img',
	'name'		=>	'',
	//'teacher'	=>	$teacher,
	//'price'		=>	'right',
	//'price2'	=>	'right',
	//'price3'	=>	'right',
	'rank'		=>	'',
	'display'	=>	'boolean'
);

$delete['confirm'] = array('online_olympiads'=>'category');

$form[] = array('input td6','name',true);
//$form[] = array('select td2','teacher',array(true,$teacher),array('name'=>'тип'));
$form[] = array('input td2','rank',true);
$form[] = array('checkbox','display',true);
// убираем отсюда http://workspace.abc-cms.com/proekty/anglius.ru/3/9/ от 28.04.17 08:07 в online_olympiads тоже закоментировано
//$form[] = array('tinymce td12','text',true);
//$form[] = array('file td6','img','Картинка',array(''=>'resize 600x600','p-'=>'cut 250x200'));

//$form[] = array('input td2','date1',true);
//$form[] = array('input td2','date2',true);
//$form[] = array('input td2','summarizing',true);
//$form[] = array('input td2 right','price',true);
//$form[] = array('input td2 right','price2',true);
//$form[] = array('input td2 right','price3',true);

?>
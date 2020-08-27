<?php

$a18n['teacher']='тип';
$teacher = array(0=>'для учащихся',1=>'для педагогов');

$save_as = true;

if ($get['u']=='form'&&$get['id']=='new') {
	$post['datesof']='постоянно';
	$post['summarizing']='ежедневно';
}

$table = array(
	'id'		=>	'rank:desc id',
	'name'		=>	'',
	'teacher'	=>	$teacher,
	'price'		=>	'right',
	'rank'		=>	'',
	'display'	=>	'boolean'
);

$form[] = array('input td8','name',true);
$form[] = array('input td2','rank',true);
$form[] = array('checkbox','display',true);
$form[] = array('select td6','teacher',array(true,$teacher),array('name'=>'тип'));
$form[] = array('input td2 right','price',true);
$form[] = array('input td2','datesof',true);
$form[] = array('input td2','summarizing',true);
$form[] = array('file td12','img','основное фото',array(''=>'','m-'=>'cut 380x380','p-'=>'cut 270x270'));
$form[] = array('textarea td12','shortdesc',true);
$form[] = array('tinymce td12','text',true,array('name'=>'описание'));
$form[] = array('seo','seo url title keywords description',true);

?>
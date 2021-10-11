<?php

//$a18n['teacher']='тип';

//$teacher = array(0=>'для учащихся',1=>'для студентов',2=>'для педагогов');

$table = array(
	'id'		=>	'rank:desc id',
	'name'		=>	'',
//	'teacher'	=>	$teacher,
	'rank'		=>	'',
	'display'	=>	'boolean'
);

$delete['confirm'] = array('olympiads'=>'category');

$form[] = array('input td6','name',true);
//$form[] = array('select td2','teacher',array(true,$teacher),array('name'=>'тип'));
$form[] = array('input td2','rank',true);
$form[] = array('checkbox','display',true);

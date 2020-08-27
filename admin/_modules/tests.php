<?php

$save_as = true;

$table = array(
	'id'		=>	'rank:desc id',
	'name'		=>	'',
	'price'		=>	'right',
	'rank'		=>	'',
	'display'	=>	'boolean'
);

$form[] = array('input td8','name',true);
$form[] = array('input td2','rank',true);
$form[] = array('checkbox','display',true);
$form[] = array('input td10','shortdesc',true);
$form[] = array('input td2 right','price',true);
$form[] = array('tinymce td12','text',true,array('name'=>'описание'));
$form[] = array('file td6','img','основное фото',array(''=>'','m-'=>'cut 380x380','p-'=>'cut 270x270'));
$form[] = array('seo','seo url title keywords description',true);
?>
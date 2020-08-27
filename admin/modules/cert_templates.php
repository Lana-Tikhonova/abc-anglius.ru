<?php
// пока уберем их из списка, т.к. логика координально отличается
unset($config['types'][91],$config['types'][92],$config['types'][93],$config['types'][94]);
//unset($config['types'][92]);
$config['types'][9] = 'Онлайн тестирование';

$a18n['type'] = 'тип';
$a18n['diplom'] = 'диплом';
$a18n['letter'] = 'письмо';

$table = array(
    'id'		=>	'type sort:desc name id',
    'diplom'		=>	'img',
    'type'		=>	$config['types'],	    
    'name'		=>	'',	    
    'display'           =>	'boolean'
);

$filter[] = array('type',$config['types'],'тип');

$where='';
if(isset($get['type'])&&$get['type']) $where.=' AND '.$get['m'].'.type='.$get['type'];

$query = "
	SELECT {$get['m']}.* 
	FROM {$get['m']}  	
	WHERE 1 {$where} 
";

$form[] = array('select td3','type',[true, $config['types'], '- тип шаблона-']);        
$form[] = array('input td6','name',true);
$form[] = array('checkbox','display',true);
//$form[] = array('file td6','diplom','Шаблон диплома',array($config['cert_templates_prefix'].'-'=>'resize 2480x3508','p-'=>'resize 200x283'));
//$form[] = array('file td6','letter','Шаблон письма',array($config['cert_templates_prefix'].'-'=>'resize 2480x3508','p-'=>'resize 200x283'));
$form[] = array('file td6','diplom','Шаблон диплома',array($config['cert_templates_prefix'].'-'=>'','p-'=>'resize 200x283'));
$form[] = array('file td6','letter','Шаблон письма',array($config['cert_templates_prefix'].'-'=>'','p-'=>'resize 200x283'));

$form[] = array('file td6','diplom_1','Шаблон 1 степени',array($config['cert_templates_prefix'].'-'=>'','p-'=>'resize 200x283'));
$form[] = array('file td6','diplom_2','Шаблон 2 степени',array($config['cert_templates_prefix'].'-'=>'','p-'=>'resize 200x283'));
$form[] = array('file td6','diplom_3','Шаблон 3 степени',array($config['cert_templates_prefix'].'-'=>'','p-'=>'resize 200x283'));
$form[] = array('file td6','diplom_4','Шаблон 4 степени',array($config['cert_templates_prefix'].'-'=>'','p-'=>'resize 200x283'));

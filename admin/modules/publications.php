<?php

if ($get['u']=='edit') {
  if(isset($post['date'])) $post['date']=date('Y-m-d H:i:s',strtotime($post['date']));
}
if($get['u']=='form') {
  if(isset($post['date'])) $post['date']=date('d-m-Y H:i:s',strtotime($post['date']));
}

$table = array(
	'id'		=>	'id:desc',
	'fio'		=>	'',
	'name'		=>	'',
//	'date'		=>	'',
	'display'	=>	'boolean'
);

$filter[] = array('display',array(1=>'опубликованные',2=>'неопубликованные'),'все');

if(isset($get['display'])){
  if($get['display']==1) $where=" AND display=1 ";
  elseif($get['display']==2) $where=" AND display=0 ";
}

$query = "
	SELECT * FROM publications
	WHERE 1 $where
";

$form[] = array('input td6','name',true);
$form[] = array('input td4','fio',true);
$form[] = array('checkbox','display',true);
$form[] = array('input td6','workplace',true);
$form[] = array('input td4','date',true);
$form[] = array('file','file','архив с публикацией');

?>
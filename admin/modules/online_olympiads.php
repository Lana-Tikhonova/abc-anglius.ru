<?php

$isnew=0;
$klass=0;

//исключение при редактировании модуля
if ($get['u']=='edit'&&isset($mytype)) {
	$post['type'] = $mytype;
	if($get['id']=='new') {
          $isnew=1;
          if(isset($post['klass'])) {$klass=$post['klass'];unset($post['klass']);}
        }
}

function after_save(){
	global $get,$isnew,$klass;
	if($isnew) {
		mysql_fn('insert','online_olympiads_tests',array('olympiad'=>$get['id'],'klass'=>$klass));
	}
}



//if ($get['u']=='edit') {
//  if(isset($post['date1'])) $post['date1']=date('Y-m-d 00:00:00',strtotime($post['date1']));
//  if(isset($post['date2'])) $post['date2']=date('Y-m-d 00:00:00',strtotime($post['date2']));
//}
//if($get['u']=='form') {
//  if(isset($post['date1'])) $post['date1']=date('d-m-Y',strtotime($post['date1']));
//  if(isset($post['date2'])) $post['date2']=date('d-m-Y',strtotime($post['date2']));
//}

//$save_as = true;

$delete['confirm'] = array('online_olympiads_tests'=>'olympiad');

//$categories = mysql_select("SELECT id,name FROM olympiads_categories order by name asc",'array');
//$filter[] = array('category',$categories,'категории олимпиад');
//$where = (isset($get['category']) && $get['category']>0) ? " AND category = '".intval($get['category'])."' " : "";

//$query = "
//	SELECT
//		olympiads.*,
//		oc.name oc_name
//	FROM olympiads
//	LEFT JOIN olympiads_categories oc ON oc.id=category
//	WHERE 1 $where
//";

//if(!isset($type)) $type=1;

$query = "
	SELECT online_olympiads.*, oc.name oc_name 
        FROM online_olympiads 
        LEFT JOIN online_olympiads_categories AS oc ON oc.id = online_olympiads.category 
	WHERE online_olympiads.type=$mytype
";

if(empty($table)) {
    $table = array(
            'id'		=>	'rank:desc id',
            'name'		=>	'',
    //	'category'	=>	'<a href="/admin.php?m=olympiads_categories&id={category}">{oc_name}</a>',
    //	'price'		=>	'right',
    //	'price2'	=>	'right',
    //	'price3'	=>	'right',
            'rank'		=>	'',
            'display'	=>	'boolean'
    );
}

if($get['id']=='new'&&isset($mytype)&&$mytype>92) $form[] = array('input td7','name',true);
                                            else $form[] = array('input td9','name',true);

if($get['id']=='new'&&isset($mytype)) {
  if($mytype==93) $form[] = array('select td2','klass',
	array(true,array(1=>'1 класс',2=>'2 класс',3=>'3 класс',4=>'4 класс',5=>'5 класс',6=>'6 класс',7=>'7 класс',8=>'8 класс',9=>'9 класс',10=>'10 класс',11=>'11 класс')));
  elseif($mytype==94) $form[] = array('select td2','klass',
	array(true,array(1=>'1 курс',2=>'2 курс',3=>'3 курс',4=>'4 курс',5=>'5 курс')));
}

//$form[] = array('input td1 right','price',true);
$form[] = array('input td1','rank',true);
$form[] = array('checkbox','display',true);

//$form[] = array('input td2','date1',true);
//$form[] = array('input td2','date2',true);

//$form[] = array('input td3 right','price2',true);
//$form[] = array('input td3 right','price3',true);
//$form[] = array('input td4','summarizing',true);
//$form[] = array('file','file','файл с вопросами');

if($mytype==93) {
  $form[] = array('checkbox td3','kl1',true,array('name'=>'Олимпиада для 1-4 класса'));
  $form[] = array('checkbox td3','kl2',true,array('name'=>'Олимпиада для 5-9 класса'));
  $form[] = array('checkbox td3','kl3',true,array('name'=>'Олимпиада для 10-11 класса'));
}

if($mytype==91) {
    $form[] = array('select td4','category',array(true, $categories, '- не назначено -'),array('name'=>'категория'));
}
// убираем http://workspace.abc-cms.com/proekty/anglius.ru/3/9/ от 28.04.17 08:07
//if($mytype!=91) {   
    $form[] = array('tinymce td12','text',true,array('name'=>'описание'));
    $form[] = array('file','img','основное фото',array(''=>'','p-'=>'cut 250x200'));
//}

$form[] = array('seo','seo url title keywords description',true);

?>
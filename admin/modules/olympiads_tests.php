<?php

$a18n['name']='класс';

//исключение при редактировании модуля
if ($get['u']=='edit'&&isset($post['answers'])) {
	$post['answers'] = serialize($post['answers']);
}

//if($get['u']=='form'&&$get['id']=='new'){
//	$post['options']=4;
//}

$save_as = true;

$categories = mysql_select("SELECT id,name FROM olympiads_categories order by name asc",'array');
$where = (isset($get['category']) && $get['category']>0) ? " AND category = '".intval($get['category'])."' " : "";
$olympiads = mysql_select("SELECT id,name FROM olympiads WHERE 1 $where order by name asc",'array');
$filter[] = array('category',$categories,'категории олимпиад');
$filter[] = array('olympiad',$olympiads,'олимпиады');

$where = (isset($get['category']) && $get['category']>0) ? " AND o.category = '".intval($get['category'])."' " : "";
$where.= (isset($get['olympiad']) && $get['olympiad']>0) ? " AND o.id = '".intval($get['olympiad'])."' " : "";

$query = "
	SELECT
		olympiads_tests.*,
		o.name o_name
	FROM olympiads_tests
	LEFT JOIN olympiads o ON olympiad=o.id
	WHERE 1 $where
";

$grades=array(0=>'-',31=>'дошкольник',1=>'1',2=>'2',3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',9=>'9',10=>'10',11=>'11',21=>'1 курс',22=>'2 курс',23=>'3 курс',24=>'4 курс',25=>'5 курс');

$table = array(
	'id'		=>	'rank:desc,id',
	'name'		=>	$grades,
	'olympiad'	=>	'<a href="/admin.php?m=olympiads&id={olympiad}">{o_name}</a>',
	'rank'		=>	'',
	'display'	=>	'boolean'
);

$tabs = array(
	1=>'Общее',
	2=>'Ответы'
);

$form[1][] = array('select td6','olympiad',array(true,"SELECT id,name FROM olympiads ORDER BY rank DESC"));
$form[1][] = array('select td1','name',array(true,$grades),array('name'=>'класс'));
//$form[1][] = array('input td2','options',true,array('name'=>'вариантов ответов'));
$form[1][] = array('input td1','rank',true);
$form[1][] = array('checkbox','display',true);
//$form[1][] = array('file','file','файл с вопросами');

$template['qa']='
<div class="qa" data-i="{i}">
<div class="field select td1"><label><span>№{i}</span></label><div><select name="answers[{i}]">';
for($i=0;$i<26;$i++){
  $template['qa'].='<option value="'.chr(65+$i).'">'.chr(65+$i).'</option>';
}
$template['qa'].='</select></div></div></div>';

$form[2][] = '<div class="qas"><div class="qa" data-i="0" style="display:none;"></div>';
if (isset($post['answers'])&&$answers = unserialize($post['answers'])){
	foreach ($answers as $key=>$val) {
		$temp=template($template['qa'],array('i'=>$key));
		$temp=str_replace("value=\"$val\">","value=\"$val\" selected>",$temp);
		$form[2][] = $temp;
	}
}
$form[2][]='</div><div class="clear"></div>';
$form[2][]='<div class="input field lbl"><div>Добавить 5 вопросов</div></div><div class="input field td1 addq"><a href="#" class="spriteplus"><span class="sprite plus"></span></a></div>';
$form[2][]='<div class="input field lbl"><div>Удалить вопрос</div></div><div class="input field td1 delq"><a href="#" class="sprite boolean_0"></a></div>';

//шаблоны товара используются для js
$content = '<textarea id="template_qa">'.htmlspecialchars($template['qa']).'</textarea>';
$content.= '<style type="text/css">
#template_qa {display:none;}
.spriteplus {background:#35B374;display:inline-block;padding:3px;border-radius:11px;}
.lbl {padding-top:3px;}
</style>';
$content.= '<script type="text/javascript">
$(document).ready(function(){
	function addq() {
		var i = $(".qa:last").data("i");
		i++;
		var content = $("#template_qa").val();
		content = content.replace(/{i}/g,i);
		content = content.replace(/{[\w]*}/g,"");
		$(".qas").append(content);
	}
	function delq() {
		$(".qa:last").remove();
	}
	$(document).on("click",".addq a",function(){
		for(var i=1;i<=5;i++) {addq();}
		return false;
	});
	$(document).on("click",".delq a",function(){
		delq();
		return false;
	});
//	function noq() {
//		var olympiad=$(".form select[name=olympiad]").val();
//		$.get(
//			"/ajax.php?file=olympiad_questions",
//			{"id":olympiad},
//			function(data){
//				var qs=parseInt(data);
//				var curqs=parseInt($(".qa:last").data("i"));
//				if(curqs>qs){for(i=0;i<curqs-qs;i++) delq();}
//				if(qs>curqs){for(i=0;i<qs-curqs;i++) addq();}
//			}
//		);
//	}
//	$(document).on("click",".bookmarks a",function(){
//		if($(this).data("i")==2) {noq();}
//	});
//	$(document).on("change","select[name=olympiad]",function(){noq();});
});
</script>';

?>

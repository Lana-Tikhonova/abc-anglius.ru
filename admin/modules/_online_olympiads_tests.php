<?php

$a18n['name']='класс';

//исключение при редактировании модуля
if ($get['u']=='edit'&&isset($post['qa'])) {
	$post['qa'] = serialize($post['qa']);
}

//if($get['u']=='form'&&$get['id']=='new'){
//	$post['options']=4;
//}

//$save_as = true;

//$categories = mysql_select("SELECT id,name FROM olympiads_categories order by name asc",'array');
$where = (isset($get['category']) && $get['category']>0) ? " AND category = '".intval($get['category'])."' " : "";
$olympiads = mysql_select("SELECT id,name FROM online_olympiads WHERE type=$mytype $where order by rank desc",'array');
//$filter[] = array('category',$categories,'категории олимпиад');
$filter[] = array('olympiad',$olympiads,'олимпиады');

$where = (isset($get['category']) && $get['category']>0) ? " AND o.category = '".intval($get['category'])."' " : "";
$where.= (isset($get['olympiad']) && $get['olympiad']>0) ? " AND o.id = '".intval($get['olympiad'])."' " : "";

$query = "
	SELECT
		online_olympiads_tests.*,
		o.name o_name 
	FROM online_olympiads_tests 
	LEFT JOIN online_olympiads o ON olympiad=o.id 
	WHERE o.type=$mytype $where
";

if($mytype==93) $grades=array(1=>'1 класс',2=>'2 класс',3=>'3 класс',4=>'4 класс',5=>'5 класс',6=>'6 класс',7=>'7 класс',8=>'8 класс',9=>'9 класс',10=>'10 класс',11=>'11 класс');
elseif($mytype==94) $grades=array(1=>'1 курс',2=>'2 курс',3=>'3 курс',4=>'4 курс',5=>'5 курс');

$table = array(
	'id' =>	'rank:desc id'
);

if(isset($grades)) $table['klass']=$grades;

$table['olympiad']	=	'<a href="/admin.php?m=online_olympiads'.$mytype.'&id={olympiad}">{o_name}</a>';
$table['rank']		=	'';
$table['display']	=	'boolean';


$tabs = array(
	1=>'Общее',
	2=>'Ответы',
//	3=>'Олимпиада'
);

if(isset($grades)) {
  $form[1][] = array('select td7','olympiad',array(true,$olympiads));
  $form[1][] = array('select td2','klass',array(true,$grades),array('name'=>'класс'));
} else {
  $form[1][] = array('select td9','olympiad',array(true,$olympiads));
}
$form[1][] = array('input td1','rank',true);
$form[1][] = array('checkbox','display',true);


$template['qa']='
<div class="qa" data-i="{i}">
<div class="field input td11"><label><span>Вопрос №{i}</span></label><div><input name="qa[{i}][q]" value=""></div></div>
<div class="field select td1"><label><span>Ответ</span></label><div>
<select name="qa[{i}][a]">
<option value="1">а</option>
<option value="2">б</option>
<option value="3">в</option>
<option value="4">г</option>
</select></div></div> 
<div class="field input td4"><label><span>Ссылка на изображение к вопросу №{i}</span></label><div><input name="qa[{i}][img]" value=""></div></div>
<div class="field input td4"><label><span>Ссылка на аудио к вопросу №{i}</span></label><div><input name="qa[{i}][audio]" value=""></div></div>
<div class="field input td4"><label><span>Ссылка на видео к вопросу №{i}</span></label><div><input name="qa[{i}][video]" value=""></div></div>';
$template['qa'].='
<div class="field text" style="min-height:0px;width:15px;padding-top:5px;">а</div><div class="field input" style="min-height:30px;width:873px;"><div><input name="qa[{i}][a1]" value=""></div></div>
<div class="field text" style="min-height:0px;width:15px;padding-top:5px;">б</div><div class="field input" style="min-height:30px;width:873px;"><div><input name="qa[{i}][a2]" value=""></div></div>
<div class="field text" style="min-height:0px;width:15px;padding-top:5px;">в</div><div class="field input" style="min-height:30px;width:873px;"><div><input name="qa[{i}][a3]" value=""></div></div>
<div class="field text" style="min-height:0px;width:15px;padding-top:5px;">г</div><div class="field input" style="min-height:30px;width:873px;"><div><input name="qa[{i}][a4]" value=""></div></div>
</div>';

$form[2][] = '<div class="qas"><div class="qa" data-i="0" style="display:none;"></div>';
if (isset($post['qa'])&&$answers = unserialize($post['qa'])){
	foreach ($answers as $key=>$val) {
		$temp=str_replace('qa[{i}][q]" value="','qa[{i}][q]" value="'.$val['q'],$template['qa']);
                $temp=str_replace('qa[{i}][img]" value="','qa[{i}][img]" value="'.$val['img'],$temp);
                $temp=str_replace('qa[{i}][audio]" value="','qa[{i}][audio]" value="'.$val['audio'],$temp);
                $temp=str_replace('qa[{i}][video]" value="','qa[{i}][video]" value="'.$val['video'],$temp);
		$temp=str_replace('option value="'.$val['a'].'"','option value="'.$val['a'].'" selected',$temp);
		for($i=1;$i<=4;$i++) {
			$temp=str_replace('qa[{i}][a'.$i.']" value="','qa[{i}][a'.$i.']" value="'.$val['a'.$i],$temp);
		}
		$temp=str_replace('{i}',$key,$temp);
		$form[2][] = $temp;
	}
}
$form[2][]='</div><div class="clear"></div>';
$form[2][]='<div class="input field lbl"><div>Новый вопрос</div></div><div class="input field td1 addq"><a href="#" class="spriteplus"><span class="sprite plus"></span></a></div>';
$form[2][]='<div class="input field lbl"><div>Удалить вопрос</div></div><div class="input field td1 delq"><a href="#" class="sprite boolean_0"></a></div>';

//$form[3][]=


//шаблоны товара используются для js
$content = '<textarea id="template_qa">'.$template['qa'].'</textarea>';
$content.= '<style type="text/css">
#filter .filter select[name=olympiad] {width:700px;}
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
		addq();
		return false;
	});
	$(document).on("click",".delq a",function(){
		delq();
		return false;
	});
//	function noq() {
//		var olympiad=$(".form select[name=olympiad]").val();
//		$.get(
//			"/ajax.php?file=online_olympiad_questions",
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
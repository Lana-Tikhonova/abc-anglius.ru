<?php


$a18n['teacher']='тип';
$teacher = array(0=>'для учащихся',1=>'для педагогов');
$cert_templates = mysql_select("SELECT id,name FROM cert_templates WHERE type IN (1,8) ORDER BY sort DESC, name ASC",'array');

/*
//исключение при редактировании модуля
if ($get['u']=='edit') {
	foreach($post['qa'] as $k=>$v) {
          $post['qa'][$k]['a']=$post['qa'][$k]['v'][$post['qa'][$k]['a']];
          $post['qa'][$k]['v']=implode('|',$post['qa'][$k]['v']);
        }
	$post['qa'] = serialize($post['qa']);

}
*/

if ($get['u']=='edit') {
  if(isset($post['date1'])) $post['date1']=date('Y-m-d 00:00:00',strtotime($post['date1']));
  if(isset($post['date2'])) $post['date2']=date('Y-m-d 00:00:00',strtotime($post['date2']));
}
if($get['u']=='form') {
  if(isset($post['date1'])) $post['date1']=date('d-m-Y',strtotime($post['date1']));
  if(isset($post['date2'])) $post['date2']=date('d-m-Y',strtotime($post['date2']));
}

$save_as = true;

$delete['confirm'] = array('olympiads_test'=>'olympiad');

$offline=array(0=>'нет',1=>'да');

$table = array(
	'id'		=>	'rank:desc id offline',
	'name'		=>	'',
	'offline'	=>	$offline,
	'teacher'	=>	$teacher,
	'price'		=>	'right',
	'price2'	=>	'right',
	'rank'		=>	'',
	'display'	=>	'boolean'
);

$form[] = array('input td6','name',true);
$form[] = array('select td2','offline',array(true,$offline),array('name'=>'оффлайн'));
$form[] = array('input td2','rank',true);
$form[] = array('checkbox','display',true);
$form[] = array('select td2','teacher',array(true,$teacher),array('name'=>'тип'));
$form[] = array('input td2','date1',true);
$form[] = array('input td2','date2',true);
$form[] = array('input td2','summarizing',true);
$form[] = array('input td2 right','price',true);
$form[] = array('input td2 right','price2',true);

$form[] = array('select td3','cert_template',array(true,$cert_templates,'- по умолчанию -'),array('name'=>'Шаблон диплома'));
$form[] = array('checkbox','cert_change',true,array('name'=>'разрешить выбор диплома'));
$form[] = '<div class="clear"></div>';

$form[] = array('file td6','img','основное фото',array(''=>'','m-'=>'cut 380x380','p-'=>'cut 270x270'));
$form[] = array('file td6','file','файл с вопросами');
$form[] = array('textarea td12','shortdesc',true);
$form[] = array('tinymce td12','text',true,array('name'=>'описание'));
$form[] = array('seo','seo url title keywords description',true);

/*
$template['answer'] = '
<div class="answer" data-i="{j}"><div class="field input td1"><div><input name="qa[{i}][a]" type="radio" value="{j}"></div></div>
<div class="field input td4"><div><input name="qa[{i}][v][{j}]" value=""></div></div>
<div class="field input td1"><div><a href="#" class="sprite boolean_0"></a></div></div></div>';

$template['qa'] = '
<div class="qa">
<div class="field input td1"><label><span>№</span></label><div><input name="qa[{i}][id]" value=""></div></div>
<div class="field input td10"><label><span>Вопрос</span></label><div><input name="qa[{i}][q]" value=""></div></div>
<div class="field input td1 q"><label><span></span></label><div><a href="#" class="sprite boolean_0"></a></div></div>
<div class="field td12" style="margin-top:1em;">Ответы:</div><div class="answers">';
for($i=1;$i<=4;$i++){
  $template['qa'].=str_replace('{j}',$i,$template['answer']);
}
$template['qa'].= '</div><div class="clear"></div><div class="input field td11"><div></div></div><div class="input field td1"><div><a href="#" class="spriteplus"><span class="sprite plus"></span></a></div></div>
<div class="clear"></div></div>';

$form[3][] = '<div class="qas"><div class="qa" data-i="0" style="display:none;"></div>';
$di=1;
if (isset($post['qa'])) {
	$qa = unserialize($post['qa']);
	foreach ($qa as $key=>$val) {
                $val['v']=explode('|',$val['v']);
		$form[3][] = '<div class="qa" data-i="'.$di.'">';
		$form[3][] = '<div class="field input td1"><label><span>№</span></label><div><input name="qa['.$key.'][id]" value="'.$val['id'].'"></div></div>';
		$form[3][] = '<div class="field input td10"><label><span>Вопрос</span></label><div><input name="qa['.$key.'][q]" value="'.$val['q'].'"></div></div>';
		$form[3][] = '<div class="field input td1 q"><label><span></span></label><div><a href="#" class="sprite boolean_0"></a></div></div>';
		$form[3][] = '<div class="field td12" style="margin-top:1em;">Ответы:</div><div class="answers">';
for($i=0;$i<count($val['v']);$i++){
		$form[3][] = '<div class="answer" data-i="'.$i.'"><div class="field input td1"><div><input name="qa['.$key.'][a]" type="radio" value="'.$i.'"';
		if($val['a']==$val['v'][$i]){$form[3][]=' checked';}
		$form[3][] = '></div></div><div class="field input td4"><div><input name="qa['.$key.'][v]['.$i.']" value="'.$val['v'][$i].'"></div></div>';
		$form[3][] = '<div class="field input td1"><div><a href="#" class="sprite boolean_0"></a></div></div></div>';
}
		$form[3][] = '</div><div class="clear"></div><div class="input field td11"><div></div></div><div class="input field td1"><div><a href="#" class="spriteplus"><span class="sprite plus"></span></a></div></div>';
		$form[3][] = '<div class="clear"></div></div>';
		$di++;
	}
}
$form[3][]='</div><div class="input field"><div>Новый вопрос</div></div><div class="input field td1 addq"><div><a href="#" class="spriteplus"><span class="sprite plus"></span></a></div></div>';

//шаблоны товара используются для js
$content = '<div style="display:none">';
$content.= '<textarea id="template_qa">'.htmlspecialchars($template['qa']).'</textarea>';
$content.= '<textarea id="template_answer">'.htmlspecialchars($template['answer']).'</textarea>';
$content.= '</div>';
$content.= '<style type="text/css">
.qa {clear:both;background:#E9E9E9;padding-top:10px;margin:-10px 0 20px -20px;border-left:20px #E9E9E9 solid;}
.qa .field.input input {height:20px;border:1px solid gray;margin:0;padding:0px;}
.qa div.field {min-height:28px;}
.spriteplus {background:#35B374;display:inline-block;padding:3px;border-radius:11px;}
</style>';
$content.= '<script type="text/javascript">
$(document).ready(function(){
	$(document).on("click",".q a",function(){
		if(confirm("Вы действительно хотите удалить весь блок с вопросом и ответами?")) {
			$(this).parents(".qa").remove();
		}
		return false;
	});

	$(document).on("click",".addq a",function(){
		var i = $(".qa:last").data("i");
		i++;
		var content = $("#template_qa").val();
		content = content.replace(/{i}/g,i);
//		content = content.replace(/{[\w]*}/g,"");
		$(".qas").append(content);
		return false;
	});

	$(document).on("click",".answer a",function(){
		$(this).parents(".answer").remove();
		return false;
	});

	$(document).on("click",".answer a",function(){
		$(this).parents(".answer").remove();
		return false;
	});

	$(document).on("click",".qa .spriteplus",function(){
		var i = $(this).parents(".qa").data("i");
		var j = $(this).parents(".qa").find(".answer:last").data("i");j++;
		var content = $("#template_answer").val();
		content = content.replace(/{i}/g,i);
		content = content.replace(/{j}/g,j);
		$(this).parents(".qa").find(".answers").append(content);
		return false;
	});

});
</script>';

*/

?>
<?php
$userfields=unserialize($user['fields']);
//$answers=unserialize($q['more']['answers']);
//$temp=($q['more']['name']>20)?($q['more']['name']-20).' курс':$q['more']['name'].' класс';
//if(!isset($q['basket']['numberofq'])&&isset($q['numberofq'])){$q['basket']['numberofq']=$q['numberofq'];}
//print_r($q);
?>

<div class='about' style='margin-top:0'><?=$q['more']['name']?></div>
<div class='form7'>
<?=html_sources('return','jquery_validate.js')?>
<?=isset($q['message']) ? html_array('form/message',$q['message']) : ''?>
<form method="post" class="form validate" enctype="multipart/form-data" style="width:100%">
<?php
if($q['more']['teacher']==0) {
        echo '<label>'.i18n('olympiads|fio1').'</label>';
	$template_fio ='<div class="form-group form_input teacher" id="teacher{i}" data-id="{i}">';
	$template_fio.='<input name="fio[{i}]" value="{fio}" class="form-control required float-left" placeholder="Иванов Иван" />';
	$template_fio.='<a class="ansclose" style="margin-left:10px;padding-top:5px;" href="javascript:fclose({i})"><img src="/templates/images/close.png" alt="удалить" title="удалить"></a>';
	$template_fio.='<div class="clear-both"></div>';
	$template_fio.='</div>';
	if(isset($q['basket']['fio'])) {
		$fio=explode('|',$q['basket']['fio']);
		foreach($fio as $key=>$val) {
			$temp=str_replace(array('{i}','{fio}'),array($key,$val),$template_fio);
			if($key==0){$temp=preg_replace('/<a class="ansclose".*?<\/a>/','',$temp);}
			echo $temp;
		}
	}
	else {
		echo str_replace(array('{i}','{fio}'),array(0,$userfields[3][0].' '.$userfields[1][0]),preg_replace('/<a class="ansclose".*?<\/a>/','',$template_fio));
	}
	echo "<div class='reg2 gradient1 border5' id='addf' style='margin:0;margin-left:170px;margin-top:6px;'>".i18n('olympiads|add_teacher')."</div>";
}

echo html_array('form/input',array(
	'caption'	=>	i18n('olympiads|workplace'),
	'name'	=>	'workplace',
	'value'	=>	isset($q['basket']['workplace']) ? $q['basket']['workplace'] : $userfields[4][0].' '.$userfields[2][0],
	'attr'	=>	' required',
));

echo '<div class="allanswers">';
$template='<div id="ansblock{i}" class="ansblock" data-id="{i}">';
$template.='<div class="anstbl" style="width:468px;"><div style="float:left;width:298px;margin:5px">'.i18n('olympiads|fio2').'<input class="form-control required valid" placeholder="Иванов Иван" name="answers[{i}][fio]" value="{fio}"></div><div style="float:left;width:150px;margin:5">';
$template.='Класс / курс / педагог<select name="answers[{i}][klass]" style="margin-top:0">';

for($i=1;$i<=11;$i++) $template.='<option value="'.$i.'">'.($i>20?($i-20).' курс':$i.' класс').'</option>';
for($i=21;$i<=24;$i++) $template.='<option value="'.$i.'">'.($i>20?($i-20).' курс':$i.' класс').'</option>';
$template.='<option value="0">педагог</option>';
$template.='</select></div></div>';
$template.='<a class="ansclose" href="javascript:ansclose({i})"><img src="/templates/images/close.png" alt="удалить результат" title="удалить результат"></a>';
$template.='<div class="clear-both"></div></div>';

if(isset($q['basket']['results'])){
  $firstelem=key($q['basket']['results']);
  foreach($q['basket']['results'] as $k1=>$v1) {
    $fio=(isset($v1['fio']))?$v1['fio']:'';
    $temp=str_replace('{fio}',$fio,$template);
    $temp=str_replace('<option value="'.$v1['klass'].'">','<option value="'.$v1['klass'].'" selected>',$temp);
    if($k1==$firstelem){$temp=preg_replace('/<a class="ansclose".*?<\/a>/','',$temp);}
    echo str_replace('{i}',$k1,$temp);
  }
  $kolvo=count($q['basket']['results']);
  if($kolvo>=5) {$sum=$kolvo*$q['more']['price2'];}
  else $sum=$q['more']['price'];
}
else {
  //учителя
  echo str_replace(array('{i}','{fio}'),array('0',''),preg_replace('/<a class="ansclose".*?<\/a>/','',$template));
  $sum=$q['more']['price'];
}
echo '</div>';
echo "<div class='reg2 gradient1 border5' id='add' style='margin:0;margin-left:170px;margin-top:6px;'>".i18n('olympiads|add_result')."</div>";

echo html_array('form/file',array(
	'caption'	=>	i18n('olympiads|upload'),
	'name'	=>	'attaches',
	'attr'	=>	$fileattr,
));
echo i18n('olympiads|upload_txt');

echo "<div class='summ'><b>Сумма к оплате <span>".$sum."</span> руб.</b></div>";

echo html_array('form/button',array(
	'name'	=>	i18n('olympiads|send'),
	'class'	=>	' reg2 gradient1'
));
?>

</form>
</div>

<div id="progress"><div><img src="/templates/images/loading.gif"><br>ОЖИДАНИЕ ЗАГРУЗКИ ФАЙЛА</div></div>

<script>
<?php if($q['more']['teacher']==0) { ?>
var tmplf='<?=$template_fio?>';
<?php } ?>
var tmpl='<?=$template?>';
function showprice() {
	var count=$(".anstbl").length;
	if(count>=5) {var price=count*<?=$q['more']['price2']?>;}
	else {var price=count*<?=$q['more']['price']?>;}
	$('.summ span').text(price);
}
$('#add').click(function(){
        var content=tmpl.replace(/{i}/g,$('.ansblock:last').data('id')+1);
	content = content.replace(/{[\w]*}/g,"");
	$(content).insertBefore($(this));
	showprice();
	return false;
});
function ansclose(i){
	$("#ansblock"+i).remove();
	showprice();
}
$('#addf').click(function(){
        var content=tmplf.replace(/{i}/g,$('.teacher:last').data('id')+1);
	content = content.replace(/{[\w]*}/g,"");
	$(content).insertBefore($(this));
	return false;
});
function fclose(i){
	$("#teacher"+i).remove();
}
$('.form').submit(function() {
	if($('.form').valid()&&$('input[type=file]').val()) $('#progress').show();
});

</script>

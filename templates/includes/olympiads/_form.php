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
<form method="post" class="form validate" enctype="multipart/form-data" style="width:100%;">
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

/*
echo html_array('form/input',array(
	'caption'	=>	i18n('olympiads|fio'),
	'name'	=>	'fio',
	'value'	=>	isset($q['fio']) ? $q['fio'] : '',
	'attr'	=>	' required',
	'placeholder'	=>	'Иванов Иван'
));

/*
if($q['more']['options']) {
	foreach($answers as $k=>$v){
		echo '<div class="float-left">'.$k.'<br>';
		for($i=1;$i<=$q['more']['options'];$i++) {
			echo chr($i+64).'<br>';
		}
		echo '</div>';
	}
	echo '<div class="clear-both"></div>';
}
*/

//if($q['more']['options']) {
/*	
	echo '<label>'.i18n('olympiads|answers').'</label>';
	echo '<table class="anstbl">';

	echo '<tr><th>'.i18n('olympiads|fio').'</th>';foreach($answers as $k=>$v) echo '<th>'.$k.'</th>';echo '</tr>';
	for($i=0;$i<$q['more']['options'];$i++) {
		echo '<tr>';		
		if($i==0) {echo '<td rowspan='.$q['more']['options'].' valign=top><input type='name'>
		</td>';}
		foreach($answers as $k=>$v) {
		  echo '<td><input type="radio" id="'.$k.'_'.chr($i+65).'" name="answers['.$k.']" value="'
		  .chr($i+65).'"'.(($i==0)?' checked':'').'><label for="'.$k.'_'.chr($i+65).'">'.chr($i+65).'</label></td>';
		}
		echo '</tr>';
        }
	echo '</table>';
*/

$template='<div id="ansblock{i}" data-id="{i}"><table class="anstbl">';
//$template.='<tr><th>'.i18n('olympiads|fio').'</th>';
$template.='<tr><th rowspan=5 valign=top class="fio">'.i18n('olympiads|fio2').'<input class="form-control required valid" placeholder="Иванов Иван" name="answers[{i}][fio]" value="{fio}">';

$temp=current($q['tests']);$count=count(unserialize($temp['answers']));

if(count($q['tests'])>1) {
  $template.='<select name="answers[{i}][test]">';
  foreach($q['tests'] as $k=>$v) $template.='<option value="'.$k.'">'.($v['name']>20?($v['name']-20).' курс':$v['name'].' класс').'</option>';
  $template.='</select>';
}
else {
  $template.='<input type="hidden" name="answers[{i}][test]" value="'.$temp['id'].'">';
}
$template.='</th>';
//foreach($answers as $k=>$v) $template.='<th>'.$k.'</th>';

for($k=1;$k<=$count;$k++) $template.='<th>'.$k.'</th>';
$template.='</tr>';
for($i=0;$i<4;$i++) {
	$template.='<tr>';
//	foreach($answers as $k=>$v) {
        for($k=1;$k<=$count;$k++) {
	  $template.='<td><input type="radio" id="{i}_'.$k.'_'.chr($i+65).'" name="answers[{i}]['.$k.']" value="'
	  .chr($i+65).'"'.(($i==0)?' checked':'').'><label for="{i}_'.$k.'_'.chr($i+65).'">'.chr($i+65).'</label></td>';
	}
	$template.='</tr>';
}
$template.='</table>';
//if(!$q['basket']['workplace'])
$template.='<a class="ansclose" href="javascript:ansclose({i})"><img src="/templates/images/close.png" alt="удалить результат" title="удалить результат"></a>';
$template.='<div class="clear-both"></div></div>';
echo '<div class="allanswers">';
if(isset($q['basket']['results'])){
  $firstelem=key($q['basket']['results']);
  foreach($q['basket']['results'] as $k1=>$v1) {
    $fio=(isset($v1['fio']))?$v1['fio']:'';
    $temp=str_replace('{fio}',$fio,$template);
    $temp=str_replace('<option value="'.$v1['test'].'">','<option value="'.$v1['test'].'" selected>',$temp);
    for($i=1;$i<=$count;$i++) {
      if(isset($v1[$i])) {
        $temp=preg_replace('/(name=\"answers\[{i}\]\['.$i.'\]\" value=\"[^\"]*\") checked/',"$1",$temp);
        $temp=preg_replace('/(name=\"answers\[{i}\]\['.$i.'\]\" value=\"'.$v1[$i].'\")/i',"$1 checked",$temp);
      }
    }
    if($k1==$firstelem){$temp=preg_replace('/<a class="ansclose".*?<\/a>/','',$temp);}
    echo str_replace('{i}',$k1,$temp);
  }
  $kolvo=count($q['basket']['results']);
  if($kolvo>=5) {$sum=$kolvo*$q['more']['price2'];}
  else $sum=$kolvo*$q['more']['price'];
}
else {
  //учителя
  echo str_replace(array('{i}','{fio}'),array('0',''),preg_replace('/<a class="ansclose".*?<\/a>/','',$template));
  $sum=$q['more']['price'];
}
echo '</div>';
//}
?>

<div class='summ'><b>Сумма к оплате <span><?=$sum?></span> руб.</b></div>
<?php //if(!$q['basket']['workplace']) { ?>
<div class='reg2 gradient1 border5' id='add'><?=i18n('olympiads|add_result')?></div>
<?php //} ?>

<?php
echo html_array('form/button',array(
	'name'	=>	i18n('olympiads|send'),
	'class'	=>	' reg2 gradient1'
));
?>
</form>

<?php 
//if($q['basket']['workplace'])
//echo "<div class='list curorder'>";include($ROOT_DIR.'templates/includes/order/buttons.php');echo "</div>";
?>

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
        var content=tmpl.replace(/{i}/g,$('.anstbl:last').parent().data('id')+1);
	content = content.replace(/{[\w]*}/g,"");
	$(content).insertBefore($(".summ"));
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
</script>
</div>
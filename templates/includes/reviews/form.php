<?php $userfields=unserialize($user['fields']); ?>

<center>
<div class='about' style='margin-top:0;margin-bottom:10px;'><?=i18n('reviews|filltheform')?></div>
<div style='width:600px;'><?=i18n('reviews|text_form')?></div>
</center>
<div class='form7'>
<?=html_sources('return','jquery_validate.js')?>
<?=isset($q['message']) ? html_array('form/message',$q['message']) : ''?>
<form method="post" class="form validate" enctype="multipart/form-data">
<?php
echo html_array('form/input',array(
	'caption'	=>	i18n('reviews|fio'),
	'name'	=>	'fio',
	'value'	=>	isset($q['fio']) ? $q['fio'] : $userfields[3][0].' '.$userfields[1][0],
	'attr'	=>	' required',
	'placeholder'	=>	'Иванов Иван'
));
echo html_array('form/input',array(
	'caption'	=>	i18n('reviews|workplace'),
	'name'	=>	'workplace',
	'value'	=>	isset($q['workplace']) ? $q['workplace'] : $userfields[4][0].' '.$userfields[2][0],
	'attr'	=>	' required',
));
echo html_array('form/input',array(
	'caption'	=>	i18n('reviews|position'),
	'name'	=>	'position',
	'value'	=>	isset($q['position']) ? $q['position'] : '',
	'attr'	=>	' required',
));
echo html_array('form/input',array(
	'caption'	=>	i18n('reviews|name'),
	'name'	=>	'name',
	'value'	=>	isset($q['name']) ? $q['name'] : '',
	'attr'	=>	' required',
));
echo html_array('form/file',array(
	'caption'	=>	i18n('reviews|upload'),
	'name'	=>	'attaches',
//class
	'attr'	=>	' required',
));
echo i18n('reviews|upload_txt');
?>

<div style='margin-top:30px;'><input type="radio" name="type" value="1"<?=(!isset($q['type'])||$q['type']==1)?' checked':''?>>рецензия на методическую разработку урока (мероприятия) - <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;стоимость <?=i18n('reviews|price1')?> руб.</div>
<div><input type="radio" name="type" value="2"<?=(isset($q['type'])&&$q['type']==2)?' checked':''?>>рецензия на рабочую программу - стоимость <?=i18n('reviews|price2')?> руб.</div>

<?php
echo html_array('form/captcha2');
echo html_array('form/button',array(
	'name'	=>	i18n('reviews|send'),
	'class'	=>	' reg2 gradient1'
));
?>
</form>
</div>

<div id="progress"><div><img src="/templates/images/loading.gif"><br>ОЖИДАНИЕ ЗАГРУЗКИ ФАЙЛА</div></div>
<script>
$('.form').submit(function() {
	if($('.form').valid()) $('#progress').show();
});
</script>

<?php $userfields=unserialize($user['fields']);?>

<div class='container form7' style='margin:40px auto'>
  <center>
  <div class='head'><?=i18n('publications|filltheform')?></div>
  </center>
<?=html_sources('return','jquery_validate.js')?>
<?=isset($q['message']) ? html_array('form/message',$q['message']) : ''?>
<form method="post" class="form validate" enctype="multipart/form-data">
<?php
echo html_array('form/input',array(
	'caption'	=>	i18n('publications|fio'),
	'name'	=>	'fio',
	'value'	=>	isset($q['fio']) ? $q['fio'] : $userfields[3][0].' '.$userfields[1][0],
	'attr'	=>	' required',
	'placeholder'	=>	'Иванов Иван'
));
echo html_array('form/input',array(
	'caption'	=>	i18n('publications|workplace'),
	'name'	=>	'workplace',
	'value'	=>	isset($q['workplace']) ? $q['workplace'] : $userfields[4][0].' '.$userfields[2][0],
	'attr'	=>	' required',
));
echo html_array('form/input',array(
	'caption'	=>	i18n('publications|name'),
	'name'	=>	'name',
	'value'	=>	isset($q['name']) ? $q['name'] : '',
	'attr'	=>	' required',
));

echo html_array('form/file',array(
	'caption'	=>	i18n('publications|upload'),
	'name'	=>	'attaches',
//class
	'attr'	=>	' required',
));
echo i18n('publications|upload_txt');
echo html_array('form/captcha2');
echo html_array('form/button',array(
	'name'	=>	i18n('publications|send'),
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

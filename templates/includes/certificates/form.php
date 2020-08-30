<?php $userfields=unserialize($user['fields']); ?>

<center>
<div class='about' style='margin-top:0;margin-bottom:10px;'><?=i18n('certificates|filltheform')?></div>
<div  class="order-profile"><?=i18n('certificates|text_form')?></div>
</center>
<div class='form7'>
<?=html_sources('return','jquery_validate.js')?>
<?=isset($q['message']) ? html_array('form/message',$q['message']) : ''?>
<form method="post" class="form validate" enctype="multipart/form-data">
<?php
echo html_array('form/input',array(
	'caption'	=>	i18n('certificates|fio'),
	'name'	=>	'fio',
	'value'	=>	isset($q['fio']) ? $q['fio'] : $userfields[3][0].' '.$userfields[1][0],
	'attr'	=>	' required',
	'placeholder'	=>	'Иванов Иван'
));
echo html_array('form/input',array(
	'caption'	=>	i18n('certificates|workplace'),
	'name'	=>	'workplace',
	'value'	=>	isset($q['workplace']) ? $q['workplace'] : $userfields[4][0].' '.$userfields[2][0],
	'attr'	=>	' required',
));
echo html_array('form/textarea',array(
	'caption'	=>	i18n('certificates|comment'),
	'name'	=>	'comment',
	'value'	=>	isset($q['comment']) ? $q['comment'] : '',
	'attr'	=>	' required',
));
echo html_array('form/captcha2');
echo html_array('form/button',array(
	'name'	=>	i18n('certificates|send'),
	'class'	=>	' reg2 gradient1'
));
?>
</form>
</div>

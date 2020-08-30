<?php $userfields=unserialize($user['fields']); ?>

<center>
<div class='head order-profile' style='margin-top:0;margin-bottom:10px;'><?=i18n('conferences|confname')?></div>
<div  class="order-profile"><?=i18n('conferences|text_form')?></div>
</center>
<div class='form7'>
<?=html_sources('return','jquery_validate.js')?>
<?=isset($q['message']) ? html_array('form/message',$q['message']) : ''?>
<form method="post" class="form validate" enctype="multipart/form-data">
<?php
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|fio'),
	'name'	=>	'fio',
	'value'	=>	isset($q['fio']) ? $q['fio'] : $userfields[3][0].' '.$userfields[1][0],
	'attr'	=>	' required',
	'placeholder'	=>	'Иванов Иван'
));
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|workplace'),
	'name'	=>	'workplace',
	'value'	=>	isset($q['workplace']) ? $q['workplace'] : $userfields[4][0].' '.$userfields[2][0],
	'attr'	=>	' required',
));
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|position'),
	'name'	=>	'position',
	'value'	=>	isset($q['position']) ? $q['position'] : '',
	'attr'	=>	' required',
));
//echo html_array('form/input',array(
//	'caption'	=>	i18n('conferences|city'),
//	'name'	=>	'city',
//	'value'	=>	isset($q['city']) ? $q['city'] : '',
//	'attr'	=>	' required',
//));
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|section'),
	'name'	=>	'section',
	'value'	=>	isset($q['section']) ? $q['section'] : '',
	'attr'	=>	' required',
));
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|name'),
	'name'	=>	'name',
	'value'	=>	isset($q['name']) ? $q['name'] : '',
	'attr'	=>	' required',
));
//echo html_array('form/input',array(
//	'caption'	=>	i18n('conferences|email'),
//	'name'	=>	'email',
//	'value'	=>	isset($q['email']) ? $q['email'] : $user['email'],
//	'attr'	=>	' required email',
//));
echo html_array('form/file',array(
	'caption'	=>	i18n('conferences|upload'),
	'name'	=>	'attaches',
//class
	'attr'	=>	' required',
));
echo i18n('conferences|upload_txt');
echo html_array('form/captcha2');
echo html_array('form/button',array(
	'name'	=>	i18n('conferences|send'),
	'class'	=>	' reg2 gradient1'
));
?>
</form>
</div>

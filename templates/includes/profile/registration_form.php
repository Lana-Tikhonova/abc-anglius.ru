<?php
//если авторизирован
if (access('user auth',$user)) {
//echo html_array('form/message',array(i18n('profile|successful_registration',true)));
//echo '<a href="/'.$modules['profile'].'/" title="'.i18n('profile|go_to_profile').'">'.i18n('profile|go_to_profile').'</a>';
  header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.$modules['profile'].'/');
} else {
?>
<?=$page['text']?>
<?=html_sources('return','jquery_validate.js')?>
<?=isset($q['message']) ? html_array('form/message',$q['message']) : ''?>
<form method="post" class="form validate" enctype="multipart/form-value">
<?php

	if(isset($q['birthday']))
		list($q['byear'],$q['bmonth'],$q['bday'])=explode('-',$q['birthday']);
		
	echo html_array('form/input',array(
		'name'	=>	'email',
		'caption'	=>	i18n('profile|email',true),
		'value'	=>	isset($q['email']) ? $q['email'] : '',
		'attr'	=>	' required email',
		'placeholder'	=>	'mail@gmail.com'
	));
	echo html_array('form/input',array(
		'name'	=>	'password',
		'caption'	=>	i18n('profile|password',true),
		'value'	=>	isset($q['password']) ? $q['password'] : '',
		'attr'	=>	' required" id="password" type="password" autocomplete="off" minlength="8',
		'placeholder'	=>	'Минимум 8 символов'
	));
	echo html_array('form/input',array(
		'name'	=>	'password2',
		'caption'	=>	i18n('profile|password2',true),
		'value'	=>	isset($q['password2']) ? $q['password2'] : '',
		'attr'	=>	' required confirm_password" type="password" autocomplete="off',
		'placeholder'	=>	'Повторите пароль'
	));
	/*
	echo '<div class="onestring"><div style="margin-top:11px;">Дата рождения</div>';
	echo html_array('form/input',array(
		'name'	=>	'bday',
		'value'	=>	(isset($q['bday'])&&$q['bday']) ? $q['bday'] : '',
		'attr'	=>	' bday required',
		'placeholder'	=>	'00'
	));
	echo html_array('form/input',array(
		'name'	=>	'bmonth',
		'value'	=>	(isset($q['bmonth'])&&$q['bmonth']) ? $q['bmonth'] : '',
		'attr'	=>	' bmonth required',
		'placeholder'	=>	'00'
	));
	echo html_array('form/input',array(
		'name'	=>	'byear',
		'value'	=>	(isset($q['byear'])&&$q['byear']) ? $q['byear'] : '',
		'attr'	=>	' byear required',
		'placeholder'	=>	'0000'
	));
	echo '</div>';	
	*/
	echo html_array('profile/fields',isset($q['fields']) ? $q['fields'] : array());


	echo html_array('form/checkbox2',array(
		'name'	=>	'checkbox',
		'caption'	=>	'Нажимая кнопку зарегистрироваться, вы соглашаетесь с <a href="#" class="terms">правилами</a>',
		'value'	=>	1,
		'attr'	=>	' required',
	));
	echo html_array('form/captcha2');//скрытая капча
	echo html_array('form/button',array(
		'name'	=>	i18n('profile|registration'),
		'class'	=>	' regbtn gradient1'
	));
	?>
</form>
<div class="modal_form" id="terms_form">
  <span id="modal_close"></span>
  <?=str_replace("\n",'<br>',i18n('profile|terms'))?>
</div>
<div id="overlay"></div>
<?php } ?>
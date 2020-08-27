<?php
//если письмо отправилось
if (isset($q['success'])) {
	echo html_array('form/message',i18n('profile|successful_remind',true));
} else {
?>
<?=html_sources('return','jquery_validate.js')?>
<?=isset($q['message']) ? html_array('form/message',$q['message']) : ''?>
<form method="post" class="form validate">
<?php
	echo html_array('form/input',array(
		'caption'	=>	i18n('profile|email',true),
		'name'		=>	'email',
		'value'		=>	isset($q['email']) ? $q['email'] : '',
		'attr'		=>	' required email',
		'placeholder'	=>	'mail@gmail.com'
	));
	echo html_array('form/captcha2');//скрытая капча
	echo html_array('form/button',array(
		'name'=>i18n('profile|remind_button'),
		'class'=>' regbtn'
	));
?>
</form>
<?php } ?>

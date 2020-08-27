<?php
if (access('user auth')) {
//
//	echo html_array('form/message',array(i18n('profile|successful_auth',true)));
//	echo '<a href="/'.$modules['profile'].'/" title="'.i18n('profile|go_to_profile').'">'.i18n('profile|go_to_profile').'</a>';
	if(isset($q['returl'])&&$q['returl']){
//		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$q['returl']);
	}
	else {header('Location: /'.$modules['profile'].'/');}
} else {
	echo isset($q['message']) ? html_array('form/message',$q['message']) : '';
?>
<?=html_sources('return','jquery_validate.js')?>
<form method="post" class="form validate" action="/<?=$modules['login']?>/enter/" >
<?php
echo html_array('form/input',array(
	'caption'	=>	i18n('profile|email',true),
	'name'	=>	'email',
	'data'	=>	isset($q['email']) ? $q['email'] : '',
	'attr'	=>	' required text',
	'placeholder'	=>	'mail@gmail.com'
));
echo html_array('form/input',array(
	'caption'	=>	i18n('profile|password',true),
	'name'	=>	'password',
	'attr'	=>	' required" type="password" autocomplete="off',
	'placeholder'	=>	'Минимум 8 символов'
));
echo html_array('form/checkbox2',array(
	'caption'	=>	i18n('profile|remember_me',true),
	'name'	=>	'remember_me',
));
echo html_array('form/captcha2');
if(isset($_GET['returl'])) echo '<input name="returl" type="hidden" value="'.$_GET['returl'].'" />';
echo html_array('form/button',array(
	'name'	=>	i18n('profile|enter'),
	'class'	=>	' enterbtn gradient1'
));

if (isset($modules['remind'])) {
	echo '<a class="remind" href="/'.$modules['remind'].'/">'.i18n('profile|remind').'</a>';
}
?>
<hr>
<a href="/<?=$modules['registration']?>/" class="reg2 gradient1 border5">Пройти регистрацию</a>
</form>

<?php } ?>

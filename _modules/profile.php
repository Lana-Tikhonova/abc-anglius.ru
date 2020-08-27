<?php

//востановление пароля
if (isset($_GET['email'])) $user = user('remind');

if (access('user auth')==false) {
	die(header('location:/'.$modules['login'].'/'));
}
$config['profile'] = array(
	'user_edit'	=>	i18n('profile|user_edit'),
	'orders'	=>	i18n('profile|orders'),
	'certificates'	=>	i18n('profile|certificates'),
	'reviews'	=>	i18n('profile|reviews'),
	'conferences'	=>	i18n('profile|conferences'),
	'help'		=>	i18n('profile|help')
);
//if(access('user admin')) {
//	$config['profile']['reviews']	=	i18n('profile|reviews');
//}

$html['module2'] = '';
if (array_key_exists($u[2],$config['profile']) && file_exists('modules/profile/'.$u[2].'.php')) {	$html['module2'] = $u[2];
	$page['name'] = $config['profile'][$u[2]];
	require_once('modules/profile/'.$u[2].'.php');
	$breadcrumb['module'][] = array($config['profile'][$u[2]],'/'.$modules['profile'].'/'.$u[2].'/');
}
$html['profile_menu'] = html_array('profile/menu',$config['profile']);

?>

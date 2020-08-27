<?php
//обрабока формы
if (count($_POST)>0) {
	//загрузка функций для формы
	require_once(ROOT_DIR.'functions/form_func.php');
	//определение значений формы
	$fields = array(
		'password'	=> 'required password',
		'password2'	=> 'required password2',
		'email'		=> 'required email',
		'captcha'	=> 'required captcha2',
		'bday'		=> 'required int',
		'bmonth'	=> 'required int',
		'byear'		=> 'required int',
		'checkbox'	=> 'required int'
	);
	//создание массива $post
	$post = form_smart($fields,stripslashes_smart($_POST));// print_r($post);
	$post['fields'] = isset($_POST['fields']) ? serialize(stripslashes_smart(str_replace('"','&quot;',$_POST['fields']))) : array();//дополнительные поля
	//сообщения с ошибкой заполнения
	$message = form_validate($fields,$post);
	//проверка существования мыла
	$result = mysql_query("SELECT id FROM users WHERE email = '".mysql_real_escape_string(strtolower($post['email']))."'  LIMIT 1");
	if (mysql_num_rows($result)==1)
		$message[] =  i18n('validate|duplicate_email',true);
	//проверка пароля
	if ($post['password']!==$post['password2'])
		$message[] = i18n('validate|not_match_passwords',true);
	if ($post['checkbox']!=1)
		$message[] = i18n('validate|unapproved_terms',true);
	//регистарация
	if (count($message)==0) {
		$post['email']	= strtolower($post['email']);
		$post['date']	= $post['last_visit'] = date('Y-m-d H:i:s');
		$post['hash']	= md5($post['email'].md5($post['password']));
		$post['type']	= 2;
		$post['birthday'] = strtotime($post['byear'].'-'.$post['bmonth'].'-'.$post['bday']);
	
		$password	= $post['password']; //пароль будет удален потому что такого поля нет в БД, но значение будет нужно при отправке сообщения пользователю
		unset($post['password'],$post['password2'],$post['captcha'],$post['byear'],$post['bmonth'],$post['bday'],$post['checkbox']);
		if ($post['id'] = mysql_fn('insert','users',$post)) {
			//$post['avatar'] = file_upload ('users',$post['id'],'avatar',array('size'=>'100*100'));
			$_SESSION['user'] = $user = $post;
			$post['password'] = $password;
			require_once(ROOT_DIR.'functions/mail_func.php');
			mailer('registration',$lang['id'],$post,$post['email']);
			/*email(
				$config['email'],
				$post['email'],
				'Регистрация на сайте '.$_SERVER['SERVER_NAME'],
				html_array('profile/registration_letter',$post)
			);*/
		}
		else $message[] = i18n('validate|error',true);
	}
	if (count($message)>0) $post['message'] = $message;
}
else $post=array();

//вывод шаблона
$html['content'] = html_array('profile/registration_form',$post);
?>

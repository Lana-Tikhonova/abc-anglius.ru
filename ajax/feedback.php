<?php
require_once(ROOT_DIR.'functions/mysql_func.php');
require_once(ROOT_DIR.'functions/lang_func.php');
$lang = lang(1);

if($_SESSION['captcha']!=$_POST['captcha']) {echo i18n('validate|not_valid_captcha2');}
else {
	$p['date']=date('Y-m-d H:i:s');
	$p['name']=$_POST['name'];
	$p['email']=$_POST['email'];
	$p['text']=$_POST['text'];
	if(mysql_fn('insert','feedback',$p)) {
		echo i18n('feedback|message_is_sent');

		require_once(ROOT_DIR.'functions/mail_func.php');	//функции почты
//		mailer('feedback',$lang['id'],$p,false,$p['email'],$p['email']);
		mailer('feedback',$lang['id'],$p,false,false,$p['email']);
	}
	else {
		echo i18n('feedback|error');
	}
}
?>
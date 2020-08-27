<?php

if($u[2]=='add'){
	if (!access('user auth')) {
//		header('HTTP/1.1 301 Moved Permanently');
		header('Location: /'.$modules['login'].'/?returl='.urlencode($_SERVER['REQUEST_URI']));
	}
	else {
		//обрабока формы
		if (count($_POST)>0) {
			//загрузка функций для формы
			require_once(ROOT_DIR.'functions/form_func.php');
			//определение значений формы
			$fields = array(
				'fio'		=>	'required text',
				'workplace'	=>	'required text',
				'name'		=>	'required text',
				'captcha'	=>	'required captcha2'
			);
			//создание массива $post
			$post = form_smart($fields,stripslashes_smart($_POST));
			//сообщения с ошибкой заполнения
			$message = form_validate($fields,$post);

			if (count($message)==0) {
				//прикрепленный файл
//				if(!isset($_FILES['attaches'])||strpos($_FILES['attaches']['type'],'zip')===false)
//					$message[]=i18n('validate|wrong_file',true).' ('.$_FILES['attaches']['type'].')';
//				else {
					unset($_SESSION['captcha'],$post['captcha']);
					$post['date'] = date('Y-m-d H:i:s');
					$post['file'] = trunslit($_FILES['attaches']['name']);
					//запись в таблицу
					$post['id'] = mysql_fn('insert','publications',$post);
					//копирование файла
					$path = ROOT_DIR.'files/publications/'.$post['id'].'/file/';
					mkdir($path,0755,true);
					copy($_FILES['attaches']['tmp_name'],$path.$post['file']);
					//запись в таблицу заказов
					$order=array(
						'date'=>$post['date'],
						'type'=>3,
						'user'=>isset($user['id']) ? $user['id'] : 0,
						'total'=>i18n('common|publications_price'),
						'paid'=>0,
                                                //'display'=>1,
						'parent'=>$post['id']
					);
					if($order['id']=mysql_fn('insert','orders',$order)){
						mysql_fn('update', 'publications', ['id'=>$post['id'], 'display'=>1]);
						require_once(ROOT_DIR.'functions/mail_func.php');	//функции почты
						mailer('basket',$lang['id'],$order,$user['email']);						
						header('Location: /'.$modules['profile']."/orders/".$order['id']."/");
					}
//				}
			}
			if (count($message)>0) $post['message'] = $message;
		}
		//вывод шаблона формы
		$breadcrumb=array();
		$html['content'] = html_array('publications/form',@$post);
	}
}
elseif($data=mysql_select('select * from publications where display=1 and id='.intval($u[2]),'row')) {
	$breadcrumb['module'][] = array($data['name'],'/'.$modules['publications'].'/'.$data['id'].'/');
	$html['content'] = html_array('publications/current',@$data);
}
elseif(!$u[2]) {
//	if($page['text']!='') $html['condition']=true;
	$breadcrumb=array();
	$html['content'] = html_array('publications/normal');
}
else $error++;
?>
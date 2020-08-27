<?php
if($u[3]=='add'){
	//обрабока формы
	if (count($_POST)>0) {
		//загрузка функций для формы
		require_once(ROOT_DIR.'functions/form_func.php');
		//определение значений формы
		$fields = array(
			'fio'		=>	'required text',
			'workplace'	=>	'required text',
			'position'	=>	'required text',
			'name'		=>	'required text',
			'type'		=>	'required int',
			'captcha'	=>	'required captcha2'
		);
		//создание массива $post
		$post = form_smart($fields,stripslashes_smart($_POST));
		$post['review']=trunslit($_FILES['attaches']['name']);
		//сообщения с ошибкой заполнения
		$message = form_validate($fields,$post);
		if (count($message)==0) {
			unset($_SESSION['captcha'],$post['captcha']);
			//запись в таблицу заказов
			$order=array(
				'date'=>date('Y-m-d H:i:s'),
				'type'=>7,
				'user'=>isset($user['id']) ? $user['id'] : 0,
				'total'=>i18n('reviews|price'.$post['type']),
				'parent'=>0,
				'paid'=>0,
				'basket'=>serialize($post)
			);
			if($order['id']=mysql_fn('insert','orders',$order)){
				$path = ROOT_DIR.'files/orders/'.$order['id'].'/review/';
				mkdir($path,0755,true);
				copy($_FILES['attaches']['tmp_name'],$path.$post['review']);

				require_once(ROOT_DIR.'functions/mail_func.php');	//функции почты
				mailer('basket',$lang['id'],$order,$user['email']);

				header('Location: /'.$modules['profile']."/reviews/");
			}
		}
		if (count($message)>0) $post['message'] = $message;
	}
	//вывод шаблона формы
	$html['content'] = html_array('reviews/form',@$post);
}
else {
  $result=mysql_select("select * from orders where user = '".$user['id']."' and type=7",'rows_id');
  if(!$result){
    header('location: /'.$modules['profile'].'/reviews/add/');
  }
  else {
    $html['content'] = html_array('reviews/list',$result);
  }
}

?>
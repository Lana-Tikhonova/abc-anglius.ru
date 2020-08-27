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
//			'city'		=>	'required text',
			'section'	=>	'required text',
			'name'		=>	'required text',
//			'email'		=>	'required email',
			'captcha'	=>	'required captcha2'
		);
		//создание массива $post
		$post = form_smart($fields,stripslashes_smart($_POST));
		//сообщения с ошибкой заполнения
		$message = form_validate($fields,$post);
		if (count($message)==0) {
			unset($_SESSION['captcha'],$post['captcha']);
			$post['confname']=i18n('conferences|confname');
//			$post['file'] = trunslit($_FILES['attaches']['name']);
			//запись в таблицу заказов
			$order=array(
				'date'=>date('Y-m-d H:i:s'),
				'type'=>6,
				'user'=>isset($user['id']) ? $user['id'] : 0,
				'total'=>i18n('conferences|price'),
				'parent'=>0,
				'paid'=>0,
				'file'=>trunslit($_FILES['attaches']['name']),
				'basket'=>serialize($post)
			);
			if($order['id']=mysql_fn('insert','orders',$order)){
				//копирование файла
				$path = ROOT_DIR."files/orders/".$order['id']."/file/";
				mkdir($path,0755,true);
				copy($_FILES['attaches']['tmp_name'],$path.$order['file']);

				header('Location: /'.$modules['profile']."/conferences/");
			}
		}
		if (count($message)>0) $post['message'] = $message;
	}
	//вывод шаблона формы
	$html['content'] = html_array('conferences/form',@$post);
}
else {
  $result=mysql_select("select * from orders where user = '".$user['id']."' and type=6",'rows_id');
  if(!$result){
    header('location: /'.$modules['profile'].'/conferences/add/');
  }
  else {
    $html['content'] = html_array('conferences/list',$result);
  }
}

?>
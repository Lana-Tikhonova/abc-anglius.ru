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
			'comment'	=>	'required text',
			'captcha'	=>	'required captcha2'
		);
		//создание массива $post
		$post = form_smart($fields,stripslashes_smart($_POST));
		//сообщения с ошибкой заполнения
		$message = form_validate($fields,$post);
		if (count($message)==0) {
			unset($_SESSION['captcha'],$post['captcha']);
			//запись в таблицу заказов
			$order=array(
				'date'=>date('Y-m-d H:i:s'),
				'type'=>5,
				'user'=>isset($user['id']) ? $user['id'] : 0,
				'total'=>i18n('certificates|price'),
				'parent'=>0,
				'paid'=>0,
				'basket'=>serialize($post)
			);
			if($order_id=mysql_fn('insert','orders',$order)){
				header('Location: /'.$modules['profile']."/certificates/");
			}
		}
		if (count($message)>0) $post['message'] = $message;
	}
	//вывод шаблона формы
	$html['content'] = html_array('certificates/form',@$post);
}
else {
  $result=mysql_select("select * from orders where user = '".$user['id']."' and type=5",'rows_id');
  if(!$result){
    header('location: /'.$modules['profile'].'/certificates/add/');
  }
  else {
    $html['content'] = html_array('certificates/list',$result);
  }
}

?>
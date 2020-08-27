<?php

$list=mysql_select('select * from contests where display=1 order by rank desc','rows_id');

if(!$u[2]){
	//просто список
	$html['content'] = html_array('contests/normal',$list);
}
else {
	//проверяем есть ли конкурс с таким id, результат в data
	$id = intval(explode2('-',$u[2]));
//	if ($data = mysql_select("
//		SELECT *
//		FROM contests
//		WHERE id = $id AND display = 1
//		LIMIT 1
//	",'row')) {
//		if (explode2('-',$u[2],2)!=$data['url']) die(header('location: /'.$modules['contests'].'/'.$data['id'].'-'.$data['url'].'/'));
//	}
        if(isset($list[$id])) {
		$data=$list[$id];
		if (!$u[3]&&explode2('-',$u[2],2)!=$data['url']) die(header('location: /'.$modules['contests'].'/'.$data['id'].'-'.$data['url'].'/'));
	}
	else $error++;
	//если есть то выводим форму или страницу конкурса
	if(!$error) {
		if($u[3]=='add') {
			if (!access('user auth')) {
//				header('HTTP/1.1 301 Moved Permanently');
				header('Location: /'.$modules['login'].'/?returl='.urlencode($_SERVER['REQUEST_URI']));
			}
			else {
				//обрабока формы
				if (count($_POST)>0) {
					//загрузка функций для формы
					require_once(ROOT_DIR.'functions/form_func.php');
					//определение значений формы
					$fields = array(
						'fio'		=>	'text',
						'fio2'		=>	'required text',
						'workplace'	=>	'required text',
						'address'	=>	'text',
						'name'		=>	'required text',
						'comment'	=>	'text',
						'captcha'	=>	'required captcha2',
						'video'		=>	'text'
					);
					//создание массива $post
					$post = form_smart($fields,stripslashes_smart($_POST));
					//сообщения с ошибкой заполнения
					$message = form_validate($fields,$post);
					if(!$post['video']&&!$_FILES['attaches']['name'])
                                          $message[]='не загружен файл и нет ссылки на видео';
					if (count($message)==0) {
						//прикрепленный файл
//						if(!isset($_FILES['attaches'])||strpos($_FILES['attaches']['type'],'zip')===false)
//							$message[]=i18n('validate|wrong_file',true).' ('.$_FILES['attaches']['type'].')';
//						else {
							unset($_SESSION['captcha'],$post['captcha']);
//							$post['date'] = date('Y-m-d H:i:s');
//							$post['file'] = trunslit($_FILES['attaches']['name']);
//							$post['parent'] = $data['id'];
//							$post['parentname'] = $data['name'];
//							$post['shortdesc'] = $data['shortdesc'];
//							$post['parenttext'] = $data['text'];
							//запись в таблицу заказов
							$order=array(
								'date'=>date('Y-m-d H:i:s'),
								'type'=>2,
								'user'=>isset($user['id']) ? $user['id'] : 0,
								'total'=>$data['price'],
								'parent'=>$data['id'],
								'paid'=>0,
								'file'=>trunslit($_FILES['attaches']['name']),
								'basket'=>serialize($post)
							);
							if($oldorder=mysql_select('select id from orders
							where paid=0 and type='.$order['type'].' and user='.$order['user'].
							' and total='.$order['total'].' and parent='.$order['parent'].
							" and basket='".$order['basket']."'",'row')) {
								header('Location: /'.$modules['profile'].'/orders/'.$oldorder['id']);
							}
							elseif($order['id']=mysql_fn('insert','orders',$order)){
								//копирование файла
								$path = ROOT_DIR."files/orders/".$order['id']."/file/";
								mkdir($path,0755,true);
								copy($_FILES['attaches']['tmp_name'],$path.$order['file']);

								require_once(ROOT_DIR.'functions/mail_func.php');	//функции почты
								mailer('basket',$lang['id'],$order,$user['email']);

								header('Location: /'.$modules['profile']."/orders/".$order['id']."/");
							}
//						}
					}
					if (count($message)>0) $post['message'] = $message;
				}
				//вывод шаблона формы
				$breadcrumb=array();
				$page['testname']=$data['name'];
				$html['content'] = html_array('contests/form',@$post);
			}
		}
		else {
//			$page['current'] = html_array('contests/current',$data);
			$page['title']=$data['title'];
			$page['description']=$data['description'];
			$page['keywords']=$data['keywords'];
//			$breadcrumb['module'][] = array($data['name'],'/'.$modules['contests'].'/'.$data['id'].'-'.$data['url'].'/');
			$breadcrumb=array();
			$html['content'] = html_array('contests/current',$list);
		}
	}
}

?>
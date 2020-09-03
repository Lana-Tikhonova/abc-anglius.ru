<?php


if($page['text']!='') $html['condition']=true;
if(!$u[2]){
	//просто список
	$html['content'] = html_array('conferences/normal');
}
else {
	//проверяем есть ли конкурс с таким id, результат в data
	$id = intval(explode2('-',$u[2]));
	if ($data = mysql_select("
		SELECT *
		FROM conferences
		WHERE id = $id AND display = 1
		LIMIT 1
	",'row')) {
		if (explode2('-',$u[2],2)!=$data['url']) die(header('location: /'.$modules['conferences'].'/'.$data['id'].'-'.$data['url'].'/'));
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
						'fio'		=>	'required text',
						'workplace'	=>	'required text',
						'position'	=>	'required text',
						'section'	=>	'required text',
						'name'		=>	'required text',
						'captcha'	=>	'required captcha2'
					);
					//создание массива $post
					$post = form_smart($fields,stripslashes_smart($_POST));
					//сообщения с ошибкой заполнения
					$message = form_validate($fields,$post);
					if (count($message)==0) {
						unset($_SESSION['captcha'],$post['captcha']);
//						$post['confname']=i18n('conferences|confname');
						$post['confname']=$data['name'];
						//запись в таблицу заказов
						$order=array(
							'date'=>date('Y-m-d H:i:s'),
							'type'=>6,
							'user'=>isset($user['id']) ? $user['id'] : 0,
							'total'=>$data['price'],
							'parent'=>$data['id'],
							'paid'=>0,
							'file'=>trunslit($_FILES['attaches']['name']),
							'basket'=>serialize($post)
						);
						if(isset($_POST['promocode'])&&$_POST['promocode']!='') {
							if($off=intval(mysql_select('select pr.percent from promocodes AS pr LEFT JOIN `promocodes-types` AS pt ON pt.child = pr.id where (pr.type=6 OR pt.parent=6) and pr.display=1 
							and promocode="'.$_POST['promocode'].'"','string'))) {
								$order['total']=$order['total']*(100-$off)/100;
								$order['promocode']=$_POST['promocode'];
							}
						}
						if($oldorder=mysql_select('select id from orders
						where paid=0 and type='.$order['type'].' and user='.$order['user'].
						' and total='.$order['total'].' and parent='.$order['parent'].
						" and basket='".$order['basket']."'",'row')) {
//							header('Location: /'.$modules['profile'].'/orders/'.$oldorder['id']);
							header('Location: /'.$modules['profile']."/conferences/");
						}
						elseif($order['id']=mysql_fn('insert','orders',$order)){
							//копирование файла
							$path = ROOT_DIR."files/orders/".$order['id']."/file/";
							mkdir($path,0755,true);
							copy($_FILES['attaches']['tmp_name'],$path.$order['file']);
//							require_once(ROOT_DIR.'functions/mail_func.php');	//функции почты
//							mailer('basket',$lang['id'],$order,$user['email']);
//							header('Location: /'.$modules['profile']."/orders/".$order['id']."/");
							header('Location: /'.$modules['profile']."/conferences/");
						}
					}
					if (count($message)>0) $post['message'] = $message;
				}
				//вывод шаблона формы
				$breadcrumb=array();
				$page['testname']=$data['name'];
				$post['price']=$data['price'];
				$html['content'] = html_array('conferences/form',@$post);
			}
		}
		else {
			$page['current'] = html_array('conferences/current',$data);
			$page['title']=$data['title'];
			$page['description']=$data['description'];
			$page['keywords']=$data['keywords'];
			$breadcrumb['module'][] = array($data['name'],'/'.$modules['conferences'].'/'.$data['id'].'-'.$data['url'].'/');
			$html['content'] = html_array('conferences/normal');
		}
	}
}

?>
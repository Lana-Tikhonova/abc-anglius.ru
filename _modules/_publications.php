<?php

if($u[2]=='add'){
	if (!access('user auth')) {
//		header('HTTP/1.1 301 Moved Permanently');
		header('Location: /'.$modules['login'].'/?returl='.urlencode($_SERVER['REQUEST_URI']));
	}
	else {
		//�������� �����
		if (count($_POST)>0) {
			//�������� ������� ��� �����
			require_once(ROOT_DIR.'functions/form_func.php');
			//����������� �������� �����
			$fields = array(
				'fio'		=>	'required text',
				'workplace'	=>	'required text',
				'name'		=>	'required text',
				'captcha'	=>	'required captcha2'
			);
			//�������� ������� $post
			$post = form_smart($fields,stripslashes_smart($_POST));
			//��������� � ������� ����������
			$message = form_validate($fields,$post);

			if (count($message)==0) {
				//������������� ����
//				if(!isset($_FILES['attaches'])||strpos($_FILES['attaches']['type'],'zip')===false)
//					$message[]=i18n('validate|wrong_file',true).' ('.$_FILES['attaches']['type'].')';
//				else {
					unset($_SESSION['captcha'],$post['captcha']);
					$post['date'] = date('Y-m-d H:i:s');
					$post['file'] = trunslit($_FILES['attaches']['name']);
					//������ � �������
					$post['id'] = mysql_fn('insert','publications',$post);
					//����������� �����
					$path = ROOT_DIR.'files/publications/'.$post['id'].'/file/';
					mkdir($path,0755,true);
					copy($_FILES['attaches']['tmp_name'],$path.$post['file']);
					//������ � ������� �������
					$order=array(
						'date'=>$post['date'],
						'type'=>3,
						'user'=>isset($user['id']) ? $user['id'] : 0,
						'total'=>i18n('common|publications_price'),
						'paid'=>0,
						'parent'=>$post['id']
					);
					if($order['id']=mysql_fn('insert','orders',$order)){
						require_once(ROOT_DIR.'functions/mail_func.php');	//������� �����
						mailer('basket',$lang['id'],$order,$user['email']);
						header('Location: /'.$modules['profile']."/orders/".$order['id']."/");
					}
//				}
			}
			if (count($message)>0) $post['message'] = $message;
		}
		//����� ������� �����
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
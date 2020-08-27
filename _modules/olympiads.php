<?php
//if($page['text']!='') $html['condition']=true;

$where='';
if(isset($_GET['filter'])) {
  if($_GET['filter']==1) $where=' and teacher=0 ';
  if($_GET['filter']==2) $where=' and teacher=1 ';
}
$list=mysql_select("select * from olympiads where display=1 $where order by rank desc",'rows_id');

if(!$u[2]){
	//просто список
	$html['content'] = html_array('olympiads/normal',$list);
}
else {
	//проверяем есть ли конкурс с таким id, результат в data
	$id = intval(explode2('-',$u[2]));
//	if ($data = mysql_select("
//		SELECT o.*,oc.teacher
//		FROM olympiads o
//		LEFT JOIN olympiads_categories oc ON oc.id=o.category
//		WHERE o.id = $id AND o.display = 1
//		LIMIT 1
//	",'row')) {
        if(isset($list[$id])) {
		$data=$list[$id];
		if (!$u[3]&&explode2('-',$u[2],2)!=$data['url']) die(header('location: /'.$modules['olympiads'].'/'.$data['id'].'-'.$data['url'].'/'));
	}
	else $error++;
	if(!$error) {
		if($u[3]=='download') {
			if (!access('user auth')) {
				header('Location: /'.$modules['login'].'/?'.($_GET['count']?'count='.$_GET['count'].'&':'').'returl='.$_GET['returl']);
			}
			else {
				$filename=ROOT_DIR.'/files/olympiads/'.$id.'/file/'.$data['file'];
				if(!file_exists($filename)) {$error++;}
				else {
					$order=array(
						'date'=>date('Y-m-d H:i:s'),
						'type'=>$data['offline']*7+1,//0 для обычной, 8 для оффлайн
						'paid'=>0,
						'user'=>isset($user['id']) ? $user['id'] : 0,
						'parent'=>$id,
						'total'=>0,
                                                'cert_template' => $data['cert_template'], // указываем на отдельный шаблон, если есть
					);
					if(isset($_GET['count'])){
						$order['basket']=array();
						for($i=0;$i<$_GET['count'];$i++) $order['basket']['results'][$i]=array();
						$order['basket']=serialize($order['basket']);
					}
					if(!mysql_select('select * from orders where (type=1 or type=8) and paid=0 and user='.$order['user']
					." and parent=$id",'row')) {
						if($order['id']=mysql_fn('insert','orders',$order)){
							require_once(ROOT_DIR.'functions/mail_func.php');	//функции почты
							mailer('basket',$lang['id'],$order,$user['email']);
						}
					}

					header('Content-Type: application/octet-stream');
//					header('Content-Disposition: attachment; filename="'.trunslit($_SERVER['HTTP_HOST'].'-'.$data['id'].'-'.$data['file']).'"');
					header('Content-Disposition: attachment; filename="'.trunslit($_SERVER['HTTP_HOST']).'-'.$id.'.zip"');
					header('Content-Transfer-Encoding: binary');
					header('Cache-Control: private',false);
					header('Pragma: public');
					header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
					header('Content-Length: '.filesize($filename));
					readfile($filename);
					exit();
				}
			}
		}
		elseif(!$u[3]) {
			$page['title']=$data['title'];
			$page['description']=$data['description'];
			$page['keywords']=$data['keywords'];
			$breadcrumb=array();
			$html['content'] = html_array('olympiads/current',$list);
		}
		else $error++;
	}
}
?>

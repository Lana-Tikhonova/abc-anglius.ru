<?php

if(!$u[2]) $error++;
else{
	if($u[3]=='') {header('Location: /'.$u[1].'/'.$u[2].'/1/');exit;}
        elseif($u[3]=='0') {
//		$breadcrumb['page']=array(array($q['olname'],'/'.$q['olurl'].'/'),array($config['types'][$q['type']],'/'.$modules['olympiads'.$q['type']].'/'));
		unset($breadcrumb);
		$html['content']=html_array('online_olympiads/needreg',$_POST);
        }
	elseif($q=mysql_select('
		select ot.*,o.name olname,o.name olurl,o.type from online_olympiads_tests ot
		left join online_olympiads o on ot.olympiad=o.id
		where ot.display=1 and o.display=1 and ot.id='.intval($u[2])
	,'row')) {
		$q['qa']=unserialize($q['qa']);
		$page['title']=$q['olname'];

		$count=count($q['qa']);
		$allanswers=true;
		for($i=1;$i<=$count;$i++) if(!isset($_POST['a'.$i])) $allanswers=false;

		if($u[3]=='result') {
			if(!$allanswers) {
				header('Location: /'.$u[1].'/'.$u[2].'/1/');exit;
			}
			else {
//				пишем заказ и редиректим на оплату
				$p=array(
					'date'=>date('Y-m-d H:i:s'),
					'type'=>$q['type'],
					'paid'=>0,
					'user'=>$user['id'],
					'parent'=>$q['id'],
					'total'=>$config['price'.$q['type']],
				);
				$userfields=unserialize($user['fields']);
				$p['basket']=array(
					'workplace'=>$userfields[4][0],
					'f'=>$userfields[3][0],
					'io'=>$userfields[1][0],
					'kk'=>'',
					'template'=>1
				);
				if(isset($userfields[5][0])) $p['basket']['kk']=$userfields[5][0];

				$p['place']=0;$rightanswers=0;
				for($i=1;$i<=$count;$i++) {
					$p['basket'][$i]=$_POST['a'.$i];
					if($_POST['a'.$i]==$q['qa'][$i]['a']) $rightanswers++;
				}
				$percent=$rightanswers/$count*100;
				if    ($percent>=floatval(str_replace(',','.',$config['percent1']))) $p['place']=1;
				elseif($percent>=floatval(str_replace(',','.',$config['percent2']))) $p['place']=2;
				elseif($percent>=floatval(str_replace(',','.',$config['percent3']))) $p['place']=3;

				$p['basket']=serialize($p['basket']);
				if ($p['id']=mysql_fn('insert','orders',$p)) {
					require_once(ROOT_DIR.'functions/mail_func.php');	//функции почты
					header('Location: /'.$modules['profile'].'/orders/'.$p['id'].'/');
				}
				exit;
			}
		}
		elseif(strval($u[3]+0)!=$u[3]||intval($u[3])<1||count($q['qa'])<intval($u[3])) {
			unset($breadcrumb);
			$error++;
		}
		else {
			$breadcrumb['page']=array(array($q['olname'],'/'.$q['olurl'].'/'),array($config['types'][$q['type']],'/'.$modules['online_olympiads'.$q['type']].'/'));
			$html['content']=html_array('online_olympiads/test',$q);
		}
	}
	else $error++;
}

?>
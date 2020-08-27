<?php
if($u[3]=='') {
	header('Location: /'.$modules['profile'].'/'.$u[2].'/unpaid/');
}
else {
	$statuses=array('unpaid'=>0,'unconfirmed'=>1,'paid'=>2);
	$html['content']='';
	if (isset($statuses[$u[3]])) {
		for($i=0;$i<3;$i++) {
			if($statuses[$u[3]]==$i) {$html['content'].='<div class="paidunpaid">'.$config['paid'][$i].'</div>';}
			else {
				$tmp=array_flip($statuses);
				$html['content'].='<a class="paidunpaid" href="/'.$modules['profile'].'/orders/'.$tmp[$i].'/">'.$config['paid'][$i].'</a>';
			}
		}
		$html['content'].='<div class="clear-both"></div>';
		switch ($statuses[$u[3]]) {
			case 0:$where="o.paid=0 and o.receipt='' ";break;
			case 1:$where="o.paid=0 and o.receipt!='' ";break;
			case 2:$where="o.paid=1 ";break;
		}
//		$query = "
//			SELECT *
//			FROM orders
//			WHERE user = '".$user['id']."' AND type<5 AND $where
//			ORDER BY date DESC
//		"; //echo $query;

/*
		if(!$q1 = mysql_select("SELECT *
			FROM orders o
			LEFT JOIN olympiads c ON c.id=o.parent
			WHERE o.user = '".$user['id']."' AND o.type=1 AND $where
			ORDER BY c.id,o.date DESC",'rows_id')
		) $q1=array();
		if(!$q2 = mysql_select("SELECT *
			FROM orders o
			LEFT JOIN contests c ON c.id=o.parent
			WHERE o.user = '".$user['id']."' AND o.type=2 AND $where
			ORDER BY c.id,o.date DESC",'rows_id')
		) $q2=array();
		if(!$q3 = mysql_select("SELECT *
			FROM orders o
			WHERE o.user = '".$user['id']."' AND o.type=3 AND $where
			ORDER BY c.id,o.date DESC",'rows_id')
		) $q3=array();
		if(!$q4 = mysql_select("SELECT *
			FROM orders o
			LEFT JOIN tests c ON c.id=o.parent
			WHERE o.user = '".$user['id']."' AND o.type=4 AND $where
			ORDER BY c.id,o.date DESC",'rows_id')
		) $q4=array();
		$query=array_merge($q1,$q2,$q3,$q4);
*/

		$query=array();

//		$temp=mysql_select('SELECT o.name,ot.name klass FROM olympiads o,
//		olympiads_tests ot WHERE ot.id='.$q['parent'].' AND ot.olympiad=o.id','row');


//		if($q1 = mysql_select("SELECT o.*,ol.name,ol.id olid,ot.name klass,teacher
//			FROM orders o
//                        LEFT JOIN olympiads_tests ot ON o.parent=ot.id
//			LEFT JOIN olympiads ol ON ol.id=ot.olympiad
//			LEFT JOIN olympiads_categories oc ON ol.category=oc.id
//			WHERE o.user = '".$user['id']."' AND o.type=1 AND $where
//			ORDER BY teacher ASC,olid ASC,klass ASC",'rows_id')
//		) $query=$query+$q1;
		if($q1 = mysql_select("SELECT o.*,ol.name,ol.img
			FROM orders o
			LEFT JOIN online_olympiads_tests oo ON o.parent=oo.id 
                        LEFT JOIN online_olympiads ol ON oo.olympiad=ol.id 
			WHERE o.user = '".$user['id']."' AND (o.type IN (91,92,93,94)) AND $where
			ORDER BY ".(($u[3]=='paid')?'o.type ASC, o.date DESC, ':'')." parent ASC",'rows_id')
		) $query=$query+$q1;
                
		if($q1 = mysql_select("SELECT o.*,ol.name,ol.img,ol.teacher
			FROM orders o
			LEFT JOIN olympiads ol ON o.parent=ol.id
			WHERE o.user = '".$user['id']."' AND (o.type=1 OR o.type=8) AND $where
			ORDER BY ".(($u[3]=='paid')?'o.type ASC, o.date DESC, ':'')." teacher ASC,parent ASC",'rows_id')
		) $query=$query+$q1;
		if($q1 = mysql_select("SELECT *	FROM orders o
			WHERE user = '".$user['id']."' AND type>1 AND type<5 AND $where
			ORDER BY id,date DESC",'rows_id')
		) $query=$query+$q1;

		// [5.15] добавил ниже, в условия, иначе ломается где-то
//		krsort($query);
//		reset($query);
		
		//$html['content'] .= html_query('order/list',$query,'Заявок не найдено');
		// http://workspace.abc-cms.com/proekty/mir-olimpiad.ru/4/7/
                if ($u[3]=='paid') {
					// [5.15]
					krsort($query);
					reset($query);
                    $html['content'] .= html_query('order/list_table',$query,'Заявок не найдено');
                } else {
					// [5.15]
//					if (access('user admin') && isset($_GET['_debug'])) {
//						krsort($query);
//						reset($query);
//					}
                    $html['content'] .= html_query('order/list',$query,'Заявок не найдено');
                }
                
		if($statuses[$u[3]]==2) {
			$query = "
				SELECT *
				FROM orders
				WHERE user = '".$user['id']."' AND paid=1 AND type>=3 AND type<5 OR type IN (91,92,93,94) 
				ORDER BY date ASC
			";
			$data=array();
			$result=mysql_select($query,'rows');
			if($result) {
				foreach ($result as $v) {
					switch ($v['type']) {
						case 3: $pub=mysql_select('select * from publications where id='.$v['parent'],'row');
							if($pub&&$pub['display']>0) {
								if(!isset($data[$pub['fio']])) $data[$pub['fio']]=$v['id'];
							}
							break;
						case 4:	if ($v['place']>0) {
								$basket=unserialize($v['basket']);
								if(!isset($data[$basket['fio']])) $data[$basket['fio']]=$v['id'];
							}
							break;
					}
				}
				if(count($data)>0) {
					$html['content'].='<div class="curorder" style="margin:20px 0;">';
					foreach ($data as $k=>$v) {
						$html['content'].="<div>$k - <a class='down' href='/certificate.php?key=".base64_encode('100500.'.$v)."'><b>скачать сертификат по ИКТ-компетентности</b></a></div>";
					}
					$html['content'].='</div>';
				}
			}
		}
	}
	elseif(intval($u[3])) {
		//конкретный заказ
		$orderid=$u[3]+0;
                if($order=mysql_select("
			SELECT * FROM orders
			WHERE user = ".$user['id']." AND id=$orderid
		",'row')) {                        
                        $order['basket']=unserialize($order['basket']);
                        // исключение смены шаблона для онлайн олимпиады - перенеено в case pay
                        /*if(isOnlineOlympiads($order['type']) && !empty($_POST['template']) && intval($_POST['template'])) {
                            $order['basket']['template'] = intval($_POST['template']);
                            mysql_fn('update', 'orders', ['id'=>$order['id'], 'basket'=>serialize($order['basket'])]);
                        }*/
                        //
			$order['more']=
                                (in_array($order['type'], [91,92,93,94]))
                                ? mysql_select('SELECT oot.*, oo.name olname FROM online_olympiads_tests AS oot LEFT JOIN online_olympiads AS oo ON oo.id = oot.olympiad WHERE oot.id='.$order['parent'].' ','row')
                                : mysql_select('SELECT * FROM '.$config['types_modules'][$order['type']].' WHERE id='.$order['parent'],'row');
			switch($u[4]){
				case '':
					break;
				case 'edit':
					break;
				case 'pay':
					if(isset($_POST['template'])) {
						$basket=$order['basket'];
						$basket['template']=intval($_POST['template']);
						$basket['f']=mysql_real_escape_string($_POST['f']);
						$basket['io']=mysql_real_escape_string($_POST['io']);
						$basket['kk']=mysql_real_escape_string($_POST['kk']);
						$basket['workplace']=htmlchars($_POST['workplace']);//mysql_real_escape_string($_POST['workplace']);
						$basket['director']=htmlchars($_POST['director']);
						mysql_fn('update','orders',array('id'=>$order['id'],'basket'=>serialize($basket)));
					}
					//$html['content']=html_array('order/pay',$order);					
					break;
				case 'payonline':				
					echo '<html><body><form id="form" action="https://money.yandex.ru/eshop.xml" method="post">';
//					echo '<html><body><form id="form" action="https://demomoney.yandex.ru/eshop.xml" method="post">';
					echo '<input name="shopId" value="'.$config['yandex_shopId'].'" type="hidden"/>';
					echo '<input name="scid" value="'.$config['yandex_scid'].'" type="hidden"/>';
					echo '<input name="sum" value="'.$order['total'].'" type="hidden">';
					echo '<input name="customerNumber" value="'.$config['yandex_customerNumber'].'" type="hidden"/>';
					echo '<input name="orderNumber" value="'.$orderid.'" type="hidden"/>';
					if(!isset($_GET['method'])) $_GET['method']='PC';
					echo '<input name="paymentType" value="'.$_GET['method'].'" type="hidden"/>';
					echo '<input name="ym_merchant_receipt" value=\''.getOnlineReceipt($order).'\' type="hidden"/>'; 
					echo '</form>';
					echo '<script>document.getElementById("form").submit();</script></body></html>';
					exit;
					break;
				case 'delete':
					mysql_select('delete from orders where id='.$orderid);
					header('Location: /'.$modules['profile']."/orders/unpaid/");
					break;
				case 'download':
					require_once(ROOT_DIR.'plugins/phpexcel/PHPExcel/IOFactory.php');
					require_once(ROOT_DIR.'plugins/phpexcel/PHPExcel/Writer/Excel5.php');
					$objPHPExcel = PHPExcel_IOFactory::load(ROOT_DIR.'template.xls');
					$objPHPExcel->setActiveSheetIndex(0);
				        $aSheet = $objPHPExcel->getActiveSheet();
					$order['fio']=$order['basket']['fio'];
					$order['address']=$order['basket']['address'];
					$order['total']=round($order['total']);
					$order['date']=strftime('%d/%m/%Y',strtotime($order['date']));
				        foreach($aSheet->getRowIterator() as $row){
				            $cellIterator = $row->getCellIterator();
				            foreach($cellIterator as $cell){
				              $val = $cell->getValue();
				              if (preg_match_all("#{([^}]+)}#sei",$val,$match)) {
					        foreach($match[1] as $v)
					          if(isset($order[$v])) $val=str_replace('{'.$v.'}',$order[$v],$val);
						  else $val=str_replace('{'.$v.'}','',$val);
					        $cell->setValue($val);
				              }
				            }
				        }
					$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
					header("Content-Type: application/vnd.ms-excel\r\n");
//					header("Content-Disposition: attachment; filename=\"".trunslit($_SERVER['HTTP_HOST'].'-receipt-'.$orderid.'.xls')."\"\r\n");
					header("Content-Disposition: attachment; filename=\"$orderid.xls\"\r\n");
					header("Cache-Control: max-age=0\r\n");
					$objWriter->save('php://output');
				        $objPHPExcel->disconnectWorksheets();
				        unset($objPHPExcel);
					break;
				case 'upload':
					if($_FILES['attaches']) {
						$temp=array(
							'id'=>$orderid,
							'receipt'=>trunslit($_FILES['attaches']['name']),
							'date_paid'=>date('Y-m-d H:i:s'));
						$path = ROOT_DIR."files/orders/$orderid/receipt/";
						if (is_dir($path) || mkdir($path,0755,true)) {
							copy($_FILES['attaches']['tmp_name'],$path.$temp['receipt']);
							if(mysql_fn('update','orders',$temp)){
								require_once(ROOT_DIR.'functions/mail_func.php');
								mailer('receipt_uploaded',$lang['id'],$temp);
							}
						}
					}
					if($order['type']==5) {
						header('Location: /'.$modules['profile']."/certificates/");
					}
					elseif($order['type']==6) {
						header('Location: /'.$modules['profile']."/conferences/");
					}
					elseif($order['type']==7) {
						header('Location: /'.$modules['profile']."/reviews/");
					}
					else header('Location: /'.$modules['profile']."/orders/$orderid/");
//					exit;
					break;
				default:$error++;
			}

			$breadcrumb['module'][0] = array(0=>'Заявка №'.$u[3],1=>$config['profile'][$u[2]],'/'.$modules['profile'].'/'.$u[2].'/'.$u[3].'/');
	                if($u[4]=='pay')
						$html['content']=html_array('order/pay',$order);					
					elseif(file_exists(ROOT_DIR.'templates/includes/order/'.$order['type'].'.php'))
						$html['content'] = html_array('order/'.$order['type'],$order,'');
						//$html['content'] = (in_array($order['type'], [91,92,93,94])) ? html_array('order/congrats',$order,'') : html_array('order/'.$order['type'],$order,'');
					elseif(in_array($order['type'], [91,92,93,94]))
						$html['content'] = html_array('order/congrats',$order,'');
                }
		else $error++;
	}
	else $error++;
}


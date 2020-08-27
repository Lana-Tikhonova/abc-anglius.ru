<?php
	if($u[4]=='edit') {
		if(!$q['paid']) {
			if(count($_POST)>0&&count($_POST['answers'])>0) {
				$count=count($_POST['answers']);
				if($count>=5){$price=$q['more']['price2'];}
				else{$price=$q['more']['price'];}
				$q['total']=$price*$count;
				if(is_array($_POST['fio'])){$_POST['fio']=implode('|',$_POST['fio']);}
				$q['basket']=array(
					'workplace'=>$_POST['workplace'],
					'fio'=>$_POST['fio']
//					'klass'=>$_POST['klass']
				);
				$diplomnr=1;
				foreach($_POST['answers'] as $k=>$v) {
                                        $v['place']=0;
					ksort($v);
					$q['basket']['results'][$diplomnr]=$v;
					$diplomnr++;
				}

				$r=array(
					'id'=>$q['id'],
					'total'=>$q['total'],
					'basket'=>serialize($q['basket'])
				);
				if(isset($_FILES['attaches'])&&$_FILES['attaches']['name']) {
					$r['file'] = trunslit($_FILES['attaches']['name']);
					//копирование файла
					$path = ROOT_DIR."files/orders/".$q['id']."/file/";
					mkdir($path,0755,true);
					copy($_FILES['attaches']['tmp_name'],$path.$r['file']);
				}
				mysql_fn('update','orders',$r);
				header('Location: '.str_replace('edit/','',$_SERVER['REQUEST_URI']));
			}
			else {
				echo html_array('olympiads/form_offline',@$q);
			}
		}
		else {
			header('Location: '.str_replace('edit/','',$_SERVER['REQUEST_URI']));
		}
	}
	else {
		if(!$q['basket']['workplace']) {
			header('Location: '.$_SERVER['REQUEST_URI'].'edit/');
		}
		else {
			echo html_array('olympiads/results_offline',@$q);
		}
	}
?>
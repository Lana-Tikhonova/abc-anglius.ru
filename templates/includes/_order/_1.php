<?php
//в море информация из olympiads, то что было в ol
//	$q['ol']=mysql_select('SELECT o.name,price,price2,price3,teacher FROM olympiads o
//	LEFT JOIN olympiads_categories oc on oc.id=o.category WHERE o.id='.$q['more']['olympiad'],'row');
//	$answers=(unserialize($q['more']['answers']));

	$q['tests']=mysql_select('select id,name,answers from olympiads_tests
                                  where display=1 and olympiad='.$q['more']['id'].' order by name asc','rows_id');
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
					'fio'=>$_POST['fio'],
				);
				$diplomnr=1;
				foreach($_POST['answers'] as $k=>$v) {
                                        $v['place']=0;
                                        if(isset($q['tests'][$v['test']])){
						$right=0;
						$answers=unserialize($q['tests'][$v['test']]['answers']);
						for($i=1;$i<=count($answers);$i++) {
							if($answers[$i]==$v[$i]) $right++;
						}
//						if($right>=count($answers)*0.92) $v['place']=1;
//						elseif($right>=count($answers)*0.79) $v['place']=2;
//						elseif($right>=count($answers)*0.66) $v['place']=3;
//						else $v['place']=4;
						$percent=$right/count($answers)*100;
						if    ($percent>=floatval(str_replace(',','.',$config['percent1']))) $v['place']=1;
						elseif($percent>=floatval(str_replace(',','.',$config['percent2']))) $v['place']=2;
						elseif($percent>=floatval(str_replace(',','.',$config['percent3']))) $v['place']=3;
						else $v['place']=4;
                                        }
					ksort($v);
					$q['basket']['results'][$diplomnr]=$v;
					$diplomnr++;
				}

				$r=array(
					'id'=>$q['id'],
					'total'=>$q['total'],
					'basket'=>serialize($q['basket'])
				);
				mysql_fn('update','orders',$r);
				header('Location: '.str_replace('edit/','',$_SERVER['REQUEST_URI']));
			}
			else {
				echo html_array('olympiads/form',@$q);
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
			echo html_array('olympiads/results',@$q);
		}
	}
?>
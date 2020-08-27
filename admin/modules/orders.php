<?php

//исключение при редактировании модуля
if ($get['u']=='edit') {
	require_once(ROOT_DIR.'functions/mail_func.php');
	if($post['basket']) {

		if(isset($post['pedagog'])) {
			$post['basket']['fio']=implode('|',$post['pedagog']);
			unset($post['pedagog']);
	        }
		if(isset($post['student'])) {
			foreach($post['student'] as $k=>$v) $post['basket']['results'][$k]['fio']=$v;
			unset($post['student']);
	        }

		if(isset($post['basket']['results']))
		  foreach($post['basket']['results'] as $k=>$v)
		    if($v['place']=='') $post['basket']['results'][$k]['place']=4;
		$post['basket'] = serialize($post['basket']);
	}
//	if(isset($post[parent])){
//		mysql_query('update publications set display='.$post[paid].' where id='.$post[parent]);
//	}
	if(!$post['paidcheck']&&$post['paid']&&$post['user']) {
//		$f=fopen(ROOT_DIR.'admin/modules/orders.txt','a');fwrite($f,$email);fclose($f);
		require_once(ROOT_DIR.'functions/mail_func.php');
		$p=$post;$p['id']=$get['id'];mailer('paid',$lang['id'],$p,$email);
	}
	if(isset($post['placecheck'])){
          if(!$post['placecheck']&&$post['place']&&$post['user']){
		$email=mysql_select('select email from users where id='.$post['user'],'string');
		$p=$post;$p['id']=$get['id'];mailer('place',$lang['id'],$p,$email);
 	  }
	  unset($post['placecheck']);
        }
	unset($post['paidcheck']);
}

$a18n['type']	= 'тип';
$a18n['login']	= 'пользователь';
$a18n['paid']	= 'оплата';
$a18n['date_paid']	= 'дата оплаты';

$filter[] = array('type',$config['types'],'тип');
$filter[] = array('paid',array(1=>'неоплаченные',2=>'ожидают подтверждения',3=>'оплаченные'),'оплата');
$filter[] = array('place',array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10),'призовое место');
//$emails=mysql_select("select u.id,u.email name from users u,orders o where o.user=u.id order by u.email asc","array");
//$filter[] = array('login',$emails,'e-mail');
$filter[] = array ('search');

$where='';
if(isset($get['type'])&&$get['type']) $where.=' AND orders.type='.$get['type'];
if(isset($get['place'])&&$get['place']!='') $where.=' AND orders.place='.$get['place'];
//if(isset($get['login'])&&$get['login']) $where.=' AND u.id='.$get['login'];
if (isset($get['search'])&&$get['search']!='') $where.= " AND (LOWER(u.email) like '%".strtolower($get['search'])."%' OR orders.id='".$get['search']."')";
if(isset($get['paid'])&&$get['paid']) {
	switch($get['paid']) {
		case 1:$where.=" AND paid=0 AND receipt=''";break;
		case 2:$where.=" AND paid=0 AND receipt!=''";break;
		case 3:$where.=" AND paid=1";break;
	}
}

$query = "
	SELECT orders.*,u.email login
	FROM orders
	LEFT JOIN users u ON orders.user = u.id
	WHERE 1 $where
";

$table = array(
	'id'	=>	'id:desc',
	'login'	=>	'<a href="/admin.php?m=users&id={user}">{login}</a>',
	'type'	=>	$config['types'],
	'total'	=>	'right',
	'date'	=>	'',
	'receipt'	=>	'{receipt}',
	'paid'	=>	'boolean',
	'place'	=>	'right',
);

$basket = unserialize($post['basket']);
$form[] = "<input name='paidcheck' value='".$post['paid']."' type='hidden'>";
if(in_array($post['type'],array(2,4,5,7,8,91,92,93,94))) $form[] = "<input name='placecheck' value='".$post['place']."' type='hidden'>";
if($post['type']<5||$post['type']==8||in_array($post['type'], [91,92,93,94])) {
	$form[] = "<div class='field td6'><label><span>родитель</span></label><a href='/admin.php?m=".$config['types_modules'][$post['type']]."&id=".$post['parent']."'>[".$config['types'][$post['type']].', ID'.$post['parent'].']</a></div>';
}
else	$form[] = "<div class='field td6'></div>";
$form[] = array('user td6','user',true);
$form[] = array('input td3','date',true);
$form[] = array('input td3','date_paid',true,array('name'=>'дата оплаты / загрузки квитанции'));
$form[] = array('input td2','place',true);
$form[] = array('input td2','total',true);
$form[] = array('checkbox','paid',true);
$form[] = array('file td6','receipt','Квитанция');
if($post['type']!=5&&$post['type']!=7) $form[]='<div style="display:none">';
$form[] = array('file td6','file','Сертификат');
if($post['type']!=5&&$post['type']!=7) $form[]='</div>';

switch ($post['type']) {
	case '1':
		foreach($basket as $k=>$v)
			if(!is_array($v)) $form[]="<input type='hidden' name='basket[$k]' value='$v'>";
			else foreach($v as $k1=>$v1) {
				if(!is_array($v1)) $form[]="<input type='hidden' name='basket[$k][$k1]' value='$v1'>";
				else foreach($v1 as $k2=>$v2)
					$form[]="<input type='hidden' name='basket[$k][$k1][$k2]' value='$v2'>";
			}
		if(isset($basket[fio])) {
		  $form[]='<div class="field td12" style="min-height:0;margin-bottom:10px;"><label>'.i18n('olympiads|fio1').'</label>';
		  $fios=explode('|',$basket[fio]);
                  foreach ($fios as $k=>$v) {
//		    $form[]='<div>'.$v.($post[paid]?', <a target="_blank" href="/certificate.php?key='.base64_encode($post[id].'.0.'.$k).'">благодарственное письмо</a>':'').'</div>';
		    $form[]='<div class="field input td4" style="min-height:24px"><div><input type="text" name="pedagog[]" value="'.$v.'"></div></div>'.($post[paid]?'<div class="field td8" style="padding-top:6px;min-height:24px"><a target="_blank" href="/certificate.php?key='.base64_encode($post[id].'.0.'.$k).'">благодарственное письмо</a></div>':'');
		  }
		  $form[]='</div>';
		}
		if(isset($basket[workplace])) {
//		  $form[]='<div class="field td12"><label>'.i18n('olympiads|workplace').'</label><div>'.$basket['workplace'].'</div></div>';
		  $form[]='<div class="field td12"><label>'.i18n('olympiads|workplace').'</label>';
		  $form[]='<div class="field input td4" style="min-height:24px"><div><input type="text" name="basket[workplace]" value="'.htmlspecialchars($basket['workplace'],ENT_COMPAT).'"></div></div>';
		  $form[]='</div>';
		}
		if(isset($basket[results])) {
		  $form[]='<div class="field td12"><label>Результаты</label><div><table class="table" style="min-width:875px;width:875px;"><tr><th>'.i18n('olympiads|fio2').'</th>';
                  $form[]='<th>Тест</th><th>Место</th>';
                  $str='';$max=0;
		  foreach ($basket[results] as $k=>$v) {
                    $tempr=mysql_select('select id,name from olympiads_tests where id='.$v['test'],'row');
//		    $str.='<tr><td>'.$v['fio'].'</td>';
		    $str.='<tr><td><input type="text" style="width:98%" name="student['.$k.']" value="'.$v['fio'].'"></td>';
                    $str.='<td><a href="/admin.php?m=olympiads_tests&id='.$tempr['id'].'">'.str_replace('0 класс','тест',($tempr['name']>20)?($tempr['name']-20).' курс':$tempr['name'].' класс').'</a></td>';
                    $str.='<td>'.($v['place']<4?$v['place']:'-').($post[paid]?', <a target="_blank" href="/certificate.php?key='.base64_encode($post[id].'.'.$k).'">диплом</a>':'').'</td>';
                    $i=1;do {$str.="<td>$v[$i]</td>";if($i>$max) $max=$i;$i++;} while(isset($v[$i]));
		    $str.='</tr>';
		  }
		  for($i=1;$i<=$max;$i++) $form[]="<th>$i</th>";
                  $form[]="</tr>$str</table></div></div>";
		}
		break;
	case '2':
		$form[]='<div class="clear"></div>';
		$form[]=array('input td6','basket[name]',$basket[name],array('name'=>i18n('contests|name')));
		$form[]=array('input td6','basket[fio]',$basket[fio],array('name'=>i18n('contests|fio1')));
		$form[]=array('input td6','basket[fio2]',$basket[fio2],array('name'=>i18n('contests|fio2')));
		$form[]=array('input td6','basket[workplace]',$basket[workplace],array('name'=>i18n('contests|workplace')));
//		$form[]=array('input td6','basket[address]',$basket[address],array('name'=>i18n('contests|city')));
		$form[]=array('textarea td12','basket[comment]',$basket[comment],array('name'=>i18n('contests|comment')));
		if($basket['video']) $form[]=array('input td12','basket[video]',$basket[video],array('name'=>'видеоролик'));
//		if($basket['file']) $form[] = "<input name='basket[file]' value='".$basket[file]."' type='hidden'><div class='field td12'><label><span>файл конкурса</span></label><a href='/files/orders/".$post['id']."/file/".$basket['file']."'>СКАЧАТЬ ФАЙЛ</a></div>";
		$form[] = "<div class='field td12'><label><span>файл конкурса</span></label><a href='/files/orders/".$post['id']."/file/".$post['file']."'>СКАЧАТЬ ФАЙЛ</a></div>";
		if($post[paid]&&$post[place]) $form[] = "<div class='field td12'><a target='_blank' href='/certificate.php?key=".base64_encode($post[id].'.0')."'>диплом</a></div>";
		break;
	case '3':
//		$form[] = "<input name='parent' value='".$post[parent]."' type='hidden'>";
		if($post[paid]&&mysql_select('select * from publications where id='.$post[parent].' and display=1','num_rows'))
		  $form[] = "<div class='field td12'><a target='_blank' href='/certificate.php?key=".base64_encode($post[id].'.0')."'>свидетельство о публикации</a></div>";
		break;
	case '4':
		$form[]='<div class="clear"></div>';
		$form[]=array('input td6','basket[name]',$basket[name],array('name'=>i18n('tests|name')));
		$form[]=array('input td6','basket[fio]',$basket[fio],array('name'=>i18n('tests|fio')));
		$form[]=array('input td6','basket[workplace]',$basket[workplace],array('name'=>i18n('tests|workplace')));
//		$form[]=array('input td6','basket[address]',$basket[address],array('name'=>i18n('tests|city')));
		$form[]=array('textarea td12','basket[comment]',$basket[comment],array('name'=>i18n('tests|comment')));
//		$form[] = "<input name='basket[file]' value='".$basket[file]."' type='hidden'><div class='field td12'><label><span>файл конкурса</span></label><a href='/files/orders/".$post['id']."/file/".$basket['file']."'>СКАЧАТЬ ФАЙЛ</a></div>";
		$form[] = "<div class='field td12'><label><span>файл конкурса</span></label><a href='/files/orders/".$post['id']."/file/".$post['file']."'>СКАЧАТЬ ФАЙЛ</a></div>";
		if($post[paid]&&$post[place]) $form[] = "<div class='field td12'><a target='_blank' href='/certificate.php?key=".base64_encode($post[id].'.0')."'>диплом</a></div>";
		break;
	case '5':
//		$form[] = array('file td6','cert','Квитанция');
		$form[]=array('input td6','basket[fio]',$basket[fio],array('name'=>i18n('certificates|fio')));
		$form[]=array('input td6','basket[workplace]',$basket[workplace],array('name'=>i18n('certificates|workplace')));
		$form[]=array('textarea td12','basket[comment]',$basket[comment],array('name'=>i18n('certificates|comment')));
		break;
	case '6':
//		$form[] = array('file td6','cert','Квитанция');
		$form[]='<div class="clear"></div>';
		$form[]=array('input td6','basket[confname]',$basket[confname],array('name'=>'Название конференции'));
		$form[]=array('input td6','basket[fio]',$basket[fio],array('name'=>i18n('conferences|fio')));
		$form[]=array('input td6','basket[workplace]',$basket[workplace],array('name'=>i18n('conferences|workplace')));
		$form[]=array('input td6','basket[position]',$basket[position],array('name'=>i18n('conferences|position')));
//		$form[]=array('input td6','basket[city]',$basket[city],array('name'=>i18n('conferences|city')));
		$form[]=array('input td6','basket[section]',$basket[section],array('name'=>i18n('conferences|section')));
		$form[]=array('input td6','basket[name]',$basket[name],array('name'=>i18n('conferences|name')));
//		$form[]=array('input td6','basket[email]',$basket[email],array('name'=>i18n('conferences|email')));
//		$form[] = "<input name='basket[file]' value='".$basket[file]."' type='hidden'><div class='field td12'><label><span>файл конкурса</span></label><a href='/files/orders/".$post['id']."/file/".$basket['file']."'>СКАЧАТЬ ФАЙЛ</a></div>";
		$form[] = "<div class='field td12'><label><span>файл конкурса</span></label><a href='/files/orders/".$post['id']."/file/".$post['file']."'>СКАЧАТЬ ФАЙЛ</a></div>";
		if($post[paid]) {
			$form[] = "<div class='field td12'><a target='_blank' href='/certificate.php?key=".base64_encode($post[id].'.1')."'>диплом</a><br>";
			$form[] = "<a target='_blank' href='/certificate.php?key=".base64_encode($post[id].'.2')."'>сертификат</a><br>";
			$form[] = "<a target='_blank' href='/certificate.php?key=".base64_encode($post[id].'.3')."'>свидетельство о публикации</a></div>";
		}
		break;
	case '7':
		$form[]=array('input td6','basket[fio]',$basket[fio],array('name'=>i18n('reviews|fio')));
		$form[]=array('input td6','basket[workplace]',$basket[workplace],array('name'=>i18n('reviews|workplace')));
		$form[]=array('input td6','basket[position]',$basket['position'],array('name'=>i18n('reviews|position')));
		$form[]=array('input td6','basket[name]',$basket['name'],array('name'=>i18n('reviews|name')));
		$form[] = "<input name='basket[review]' value='".$basket['review']."' type='hidden'><div class='field td12'><label><span>файл рецензии на ".($basket['type']==1?'методическую разработку урока (мероприятия)':'рабочую программу')."</span></label><a href='/files/orders/".$post['id']."/review/".$basket['review']."'>СКАЧАТЬ ФАЙЛ</a></div>";
//		if($post[paid]&&$post[place]) $form[] = "<div class='field td12'><a target='_blank' href='/certificate.php?key=".base64_encode($post[id].'.0')."'>диплом</a></div>";
		break;
	case '8':
		$form[]='<div class="clear"></div>';
		if(isset($basket[fio])) {
		  $form[]="<input type='hidden' name='basket[fio]' value='".$basket['fio']."'>";
		  $form[]='<div class="field td12" style="min-height:0;margin-bottom:10px;"><label>'.i18n('olympiads|fio1').'</label>';
		  $fios=explode('|',$basket['fio']);
                  foreach ($fios as $k=>$v) {
		    $form[]='<div>'.$v.($post[paid]?', <a target="_blank" href="/certificate.php?key='.base64_encode($post[id].'.0.'.$k).'">благодарственное письмо</a>':'').'</div>';
		  }
		  $form[]='</div>';
		}
		if(isset($basket[workplace]))
//		  $form[]="<input type='hidden' name='basket[klass]' value='".$basket['klass']."'>";
		  $form[]="<input type='hidden' name='basket[workplace]' value='".$basket['workplace']."'>";
		  $form[]='<div class="field td12"><label>'.i18n('olympiads|workplace').'</label><div>'.$basket[workplace].'</div></div>';
		if(isset($basket[results])) {
		  $form[]='<div class="field td12"><label>Результаты</label><div><table class="table" style="min-width:875px;width:875px;"><tr><th>'.i18n('olympiads|fio2').'</th>';
                  $form[]='<th>Класс (1..11) / курс (1=21 .. 4=24)</th><th>Место</th><th>Диплом</th></tr>';
		  foreach ($basket['results'] as $k=>$v) {
//		    $form[]='<tr><td>'.$v['fio'].'</td>';
		    $form[]="<tr><td><input name='basket[results][$k][fio]' value='".$v['fio']."' style='border:0;width:100%'></td>";
		    $form[]="<td><input name='basket[results][$k][klass]' value='".$v['klass']."' style='border:0;width:100%'></td>";
                    $form[]="<td><input name='basket[results][$k][place]' value='".(in_array($v['place'],array(1,2,3))?$v['place']:'')."' style='border:0;width:100%'></td>";
		    $form[]='<td>'.(($post[paid]&&$v['place'])?'<a target="_blank" href="/certificate.php?key='.base64_encode($post['id'].'.'.$k).'">диплом</a>':'').'</td>';
		    $form[]='</tr>';
		  }
                  $form[]="</table></div></div>";
		}
		$form[] = "<div class='field td12'><label><span>файл конкурса</span></label><a href='/files/orders/".$post['id']."/file/".$post['file']."'>СКАЧАТЬ ФАЙЛ</a></div>";
		break;
        case '91':
        case '92':
        case '93':
        case '94':
            $letters=array(1=>'а',2=>'б',3=>'в',4=>'г');
            //if(isset($post['basket'])) $basket = unserialize($post['basket']);
            $form[]='<div class="field td12"><label>Результаты</label><div><table class="table" style="min-width:875px;width:875px;"><tr><th>'.i18n('olympiads|fio2').'</th><th>Макет</th>';
            //  $str='';
            for($i=1;$i<=count($basket)-5;$i++) $form[]="<th>$i</th>";
            $form[]='</tr><tr><td>'.$basket['f'].' '.$basket['io'].'</td><td>'.$basket['template'];
            if($post['paid']) $form[]=' (<a target="_blank" href="/certificate'.(isOnlineOlympiads(@$post['type'])?'_online':'').'.php?key='.base64_encode($post['id']).'">диплом</a>)';
            $form[]='</td>';
            for($i=1;$i<=count($basket)-5;$i++) {
                $form[]='<td>'.$letters[$basket[$i]].'</td>';
            }
            $form[]="</tr></table></div></div>";
            break;
}

if(!empty($post['type']) && !isOnlineOlympiads($post['type'])){
    $cert_templates = mysql_select("SELECT id,name FROM cert_templates WHERE type = {$post['type']} ORDER BY sort DESC, name ASC",'array');
    $form[] = '<div class="clear"></div>';
    $form[] = array('select td3','cert_template',array(true,$cert_templates,'- по умолчанию -'),array('name'=>'Шаблон диплома'));
}
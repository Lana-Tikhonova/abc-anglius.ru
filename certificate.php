<?php
define('ROOT_DIR', dirname(__FILE__).'/');
require_once(ROOT_DIR.'_config.php');	//динамические настройки
require_once(ROOT_DIR.'_config2.php');	//установка настроек
require_once(ROOT_DIR.'functions/mysql_func.php');	//функции для работы с БД
require_once(ROOT_DIR.'functions/string_func.php');
require_once(ROOT_DIR.'functions/lang_func.php');	//функции словаря
require_once(ROOT_DIR.'functions/html_func.php');
require_once(ROOT_DIR.'functions/auth_func.php');

//основной язык
$lang = lang(1);

$font_path = ROOT_DIR.'templates/fonts/PTSansRegular.ttf';
$font_path_b = ROOT_DIR.'templates/fonts/PTSansBold.ttf';
$font_path_i = ROOT_DIR.'templates/fonts/PTSansItalic.ttf';

$font1_path = ROOT_DIR.'templates/fonts/times.ttf';
$font1_path_b = ROOT_DIR.'templates/fonts/timesbd.ttf';
$font1_path_i = ROOT_DIR.'templates/fonts/timesi.ttf';
$font1_path_bi = ROOT_DIR.'templates/fonts/timesbi.ttf';

$font2_path = ROOT_DIR.'templates/fonts/verdana.ttf';
$font2_path_b = ROOT_DIR.'templates/fonts/verdanab.ttf';

//$font3_path = ROOT_DIR.'templates/fonts/MyriadProRegular.ttf';
//$font3_path_i = ROOT_DIR.'templates/fonts/MyriadProItalic.ttf';

$font3_path = ROOT_DIR.'templates/fonts/times.ttf';
$font3_path_i = ROOT_DIR.'templates/fonts/timesi.ttf';

function textinblock($img,$color,$font,$fontsize,$str,$param,$wordwrap=true) {
//param - массив с размерами блока и выравниванием x,y,w,h,h_align,v_align
//x,y - координаты верхнего левого угла, w,h - ширина и высота блока

//  imagerectangle($img,$param['x'],$param['y'],$param['x']+$param['w'],$param['y']+$param['h'],$color);
  $str=str_replace(array('&quot;','«','»'),'"',$str);
  if($wordwrap) {
    $str=preg_replace('/\s+/',' ',$str);
    $words=explode(' ',$str);
    $ret='';
    foreach($words as $word) {
      $tmp_string = $ret.' '.$word;
      // Получение параметров рамки обрамляющей текст, т.е. размер временной строки 
      $bigbox = imagettfbbox($fontsize,0,$font,$tmp_string);
      // Если временная строка не укладывается в нужные нам границы, то делаем перенос строки, иначе добавляем еще одно слово
      if($bigbox[2] > $param['w']) $ret.=($ret==""?"":"\n").$word;
                              else $ret.=($ret==""?"":" ").$word;
    }
    $strings=explode("\n",$ret);
  }
  else $strings=array($str);
  //Выводить будем построчно с нужным смещением относительно левой границы
  $count=count($strings);
  foreach ($strings as $k=>$string) {
    $smallbox=imagettfbbox($fontsize,0,$font,$string);
    if($param['h_align']=='center') $left_x=round(($param['w']-($smallbox[2]-$smallbox[0]))/2);
    elseif($param['h_align']=='right') $left_x=round($param['w']-($smallbox[2]-$smallbox[0]));
    else $left_x=0;
    if ($param['v_align']=='top')
      imagettftext($img,$fontsize,0,$param['x']+$left_x,$param['y']+$fontsize*(1+$k*1.66),$color,$font,$string);
    else
//        imagettftext($img,$fontsize,0,$param['x']+$left_x,$param['y']+$fontsize+$k*$fontsize*1.66+($param['h']-$fontsize*1.66*$count+$fontsize*0.36),$color,$font,$string);
      imagettftext($img,$fontsize,0,$param['x']+$left_x,$param['y']+$param['h']+$fontsize*(1+1.66*($k-$count)+0.36),$color,$font,$string);
  }
}

$error=0;
list($orderid,$number,$pedagog)=explode('.',base64_decode($_GET['key']));
if($orderid==100500) {
	//ikt
	$order=mysql_select('select * from orders where id='.($number+0).' and paid=1 and type>=3','row');
	if($order){
		if($order['type']==3) {
			$pub=mysql_select('select * from publications where id='.$order['parent'],'row');
			if($pub&&$pub['display']>0) {
				$data=array('fio'=>$pub['fio'],'workplace'=>str_replace(array('№','&quot;'),array('N','"'),$pub['workplace']),'date'=>($order['date_paid']!=0?$order['date_paid']:$order['date']));
			}
			else $error++;
		}
		elseif($order['type']==4&&$order['place']>0) {
			$basket=unserialize($order['basket']);
			$data=array('fio'=>$basket['fio'],'workplace'=>str_replace(array('№','&quot;'),array('N','"'),$basket['workplace']),'date'=>($order['date_paid']!=0?$order['date_paid']:$order['date']));
		}
		else $error++;
		if(!$error) {
			//икт
			$img=imagecreatefromjpeg('./certif_7HbghJk/0/blank.jpg');
			$color1 = imagecolorallocate($img, 0, 48, 255);
                        $black  = imagecolorallocate($img, 0, 0, 0);
			textinblock($img,$color1,$font2_path_b,74,$data['fio'],array('x'=>0,'y'=>930,'w'=>3508,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			textinblock($img,$color1,$font1_path_b,64,str_replace(array('№','&quot;'),array('N','"'),$data['workplace']),array('x'=>0,'y'=>1060,'w'=>3508,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			//textinblock($img,$black,$font1_path,48,'город Краснодар, '.date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года',array('x'=>2078,'y'=>2348,'w'=>1400,'h'=>0,'h_align'=>'left','v_align'=>'top'));
		}
	}
	else $error++;
}
else {
	$order=mysql_select('select * from orders where id='.($orderid+0).' and paid=1','row');
	//print_r($order);
	if($order) {
		$basket=unserialize($order['basket']);
		switch ($order['type']) {
			case 1:
			case 8:
				$ol=mysql_select('select name,teacher
					from olympiads
					where id='.$order['parent'],'row');
				if(!$ol) {$error++;}
				elseif(!$ol['teacher']) {
					//олимпиада учащихся
					$fios=explode('|',$basket['fio']);
					if($number==0&&isset($pedagog)&&isset($fios[$pedagog])) {
						//благодарственное письмо
						$year=date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'%Y')+0;
						$years=(date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'%m')+0>=8)?$year.'-'.($year+1):($year-1).'-'.$year;
						if(strtotime($order['date'])>strtotime('2016-12-13 00:00:00')) {
							//новое
							//$img=imagecreatefromjpeg('./certif_7HbghJk/1/2/letter_2.jpg');
							$img=imagecreatefromjpeg(get_cert_path($order, 'letter', './certif_7HbghJk/1/2/letter_2.jpg'));
							$black = imagecolorallocate($img, 0, 0, 0);
							$color1=imagecolorallocate($img,27,27,27);
							$color2=imagecolorallocate($img,0,35,124);
							$color3=imagecolorallocate($img,13,13,13);
							$color4=imagecolorallocate($img,60,60,60);
							textinblock($img,$black,$font3_path,37,'НАГРАЖДАЕТСЯ',array('x'=>327,'y'=>1105,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font3_path_i,93,$fios[$pedagog],array('x'=>327,'y'=>1230,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font1_path,43,'педагог, ',array('x'=>327,'y'=>1560,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font1_path,43,str_replace('&quot;','"',$basket['workplace']),array('x'=>327,'y'=>1660,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color3,$font1_path,43,'за подготовку победителей (участников)',array('x'=>327,'y'=>1790,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$black,$font1_path,43,
								str_replace(array('еждународный','еждународная ','лимпиада ','икторина ', 'ексическая', 'ический', 'онкурс','страноведческая'),array('еждународного','еждународной ','лимпиады ','икторины ', 'ексической', 'ического','онкурса','страноведческой'),$ol['name']).', '.
								' проводимой на портале дистанционных проектов по английскому языку "Англиус" в '.
								$years.' учебном году',
								array('x'=>327,'y'=>1860,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color4,$font1_path,31,'регистрационный номер '.$order['id'].sprintf('%02s',$number),array('x'=>327,'y'=>2529,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color4,$font_path,31,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года           /           Anglius.ru',array('x'=>327,'y'=>3190,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
						} else {
							//старое
							$img=imagecreatefromjpeg('./certif_7HbghJk/1/2/letter.jpg');
							$black = imagecolorallocate($img, 0, 0, 0);
							$color1= imagecolorallocate($img, 178,157,52);

							textinblock($img,$color1,$font2_path,68,mb_strtoupper($fios[$pedagog],'utf-8'),array('x'=>0,'y'=>1605,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$black,$font1_path,55,'педагог, ',array('x'=>0,'y'=>1743,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$black,$font1_path,55,str_replace('&quot;','"',$basket['workplace']),array('x'=>0,'y'=>1825,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$black,$font1_path,55,'за подготовку победителей (участников)',array('x'=>50,'y'=>2194,'w'=>2380,'h'=>0,'h_align'=>'center','v_align'=>'top'));

							textinblock($img,$black,$font1_path,55,
								str_replace(array('еждународная ','лимпиада '),array('еждународной ','лимпиады '),$ol['name']).
								' проводимой на портале дистанционных проектов по английскому языку "Англиус" '.
								$years.' учебного года',
								array('x'=>50,'y'=>2277,'w'=>2380,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$black,$font1_path,55,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года ',array('x'=>0,'y'=>3210,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						}
					}
					elseif(isset($basket['results'][$number])) {
						//диплом ok
						if($order['type']==8) {$ol['testname']=$basket['results'][$number]['klass'];} else
						$ol['testname']=mysql_select('select name from olympiads_tests where id='.$basket['results'][$number]['test'],'string')+0;
						if($ol['testname']>20) {$ol['testname']='студент(ка) '.($ol['testname']-20).' курса';}
							else {$ol['testname']='ученик(ца) '.$ol['testname'].' класса';}
		// изменена дата с 2016 на 2014 год				
						if(strtotime($order['date'])>strtotime('2014-12-13 00:00:00')) {
							//новый
							//$img=imagecreatefromjpeg('./certif_7HbghJk/1/2/'.$basket['results'][$number]['place'].'_2.jpg');
                                                        $img=imagecreatefromjpeg(get_cert_path($order, 'diplom_'.$basket['results'][$number]['place'], './certif_7HbghJk/1/2/'.$basket['results'][$number]['place'].'_2.jpg'));
							$black= imagecolorallocate($img,0,0,0);
							$color1=imagecolorallocate($img,60,60,60);
							$color2=imagecolorallocate($img,0,35,124);
							textinblock($img,$color1,$font_path,56,str_replace(array('этап ','ая ','лимпиада','икторины','икторина'),array('этапа ','ой ','лимпиады','икторины','икторины'),$ol['name']),
								array('x'=>327,'y'=>1325,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$black,$font3_path,34,'НАГРАЖДАЕТСЯ',array('x'=>327,'y'=>1653,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font3_path_i,94,$basket['results'][$number]['fio'],array('x'=>327,'y'=>1757,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font1_path,38,$ol['testname'].', '.$basket['workplace'],array('x'=>327,'y'=>2060,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font_path,31,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>327,'y'=>2529,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font_path,31,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года           /           Anglius.ru',array('x'=>327,'y'=>3190,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
						} 
						//координатор
						if (isset($fios[0]) && !empty($fios[0])) {
                            textinblock($img,$color3,$font_path,40,'Координатор: ' . $fios[0],
								array('x'=>327,'y'=>2460,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
                        }
											else {
							//старый
							$img=imagecreatefromjpeg('./certif_7HbghJk/1/2/'.$basket['results'][$number]['place'].'.jpg');
							$color1= imagecolorallocate($img, 72,92,139);
							$color2= imagecolorallocate($img, 60,60,60);
							switch($basket['results'][$number]['place']){
	                                                  case 1:$color3= imagecolorallocate($img, 178,157,51);break;
	                                                  case 2:$color3= imagecolorallocate($img, 158,158,158);break;
	                                                  default:$color3= imagecolorallocate($img, 153,106,41);break;
	                                                }
							textinblock($img,$color1,$font_path,44,'олимпиада',array('x'=>390,'y'=>1548,'w'=>1700,'h'=>0,'h_align'=>'left','v_align'=>'top'));
							textinblock($img,$color2,$font_path,56,$ol['name'],
								array('x'=>390,'y'=>1633,'w'=>1750,'h'=>0,'h_align'=>'left','v_align'=>'top'));
							textinblock($img,$color1,$font_path,44,'награждается',array('x'=>390,'y'=>1895,'w'=>1700,'h'=>0,'h_align'=>'left','v_align'=>'top'));
//							textinblock($img,$color3,$font_path_b,75,mb_strtoupper($basket['results'][$number]['fio'],'UTF-8'),array('x'=>390,'y'=>1982,'w'=>1560,'h'=>0,'h_align'=>'left','v_align'=>'top'));

							textinblock($img,$color3,$font2_path_b,75,mb_strtoupper($basket['results'][$number]['fio'],'UTF-8'),array('x'=>390,'y'=>1982,'w'=>1700,'h'=>0,'h_align'=>'center','v_align'=>'top'));

							textinblock($img,$color2,$font_path,44,$ol['testname'].', '.$basket['workplace'],array('x'=>100,'y'=>2270,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font_path,31,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>100,'y'=>2520,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font_path,32,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года          /          anglius.ru',array('x'=>0,'y'=>3220,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
						}
					}
					else $error++;
				}
				else {
					//олимпиады педагогов
					if(isset($basket['results'][$number])) {
						//диплом
						if(strtotime($order['date'])>strtotime('2016-12-13 00:00:00')) {
							//новый
							//$img=imagecreatefromjpeg('./certif_7HbghJk/1/1/'.$basket['results'][$number]['place'].'_2.jpg');
                                                        $img=imagecreatefromjpeg(get_cert_path($order, 'diplom_'.$basket['results'][$number]['place'], './certif_7HbghJk/1/1/'.$basket['results'][$number]['place'].'_2.jpg'));
							$black= imagecolorallocate($img,0,0,0);
							$color1=imagecolorallocate($img,60,60,60);
							$color2=imagecolorallocate($img,0,35,124);
							textinblock($img,$black,$font3_path,34,'ОЛИМПИАДА',array('x'=>327,'y'=>1290,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font3_path,56,$ol['name'],
								array('x'=>327,'y'=>1365,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$black,$font3_path,34,'НАГРАЖДАЕТСЯ',array('x'=>327,'y'=>1753,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font3_path_i,94,$basket['results'][$number]['fio'],array('x'=>327,'y'=>1830,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font_path,44,'преподаватель '.$ol['testname'].' '.$basket['workplace'],array('x'=>327,'y'=>2140,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font_path,31,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>327,'y'=>2529,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font_path,31,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года           /           Anglius.ru',array('x'=>327,'y'=>3190,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
						} else {
							//старый
							$img=imagecreatefromjpeg('./certif_7HbghJk/1/1/'.$basket['results'][$number]['place'].'.jpg');
							$color1= imagecolorallocate($img, 72,92,139);
							$color2= imagecolorallocate($img, 60,60,60);
							switch($basket['results'][$number]['place']){
	                                                  case 1:$color3= imagecolorallocate($img, 178,157,51);break;
	                                                  case 2:$color3= imagecolorallocate($img, 158,158,158);break;
	                                                  default:$color3= imagecolorallocate($img, 153,106,41);break;
	                                                }
							textinblock($img,$color1,$font_path,44,'олимпиада',array('x'=>390,'y'=>1465,'w'=>1700,'h'=>0,'h_align'=>'left','v_align'=>'top'));
							textinblock($img,$color2,$font_path,56,$ol['name'],
								array('x'=>390,'y'=>1570,'w'=>1700,'h'=>0,'h_align'=>'left','v_align'=>'top'));
							textinblock($img,$color1,$font_path,44,'награждается',array('x'=>390,'y'=>1895,'w'=>1700,'h'=>0,'h_align'=>'left','v_align'=>'top'));
//							textinblock($img,$color3,$font_path_b,75,mb_strtoupper($basket['results'][$number]['fio'],'UTF-8'),array('x'=>300,'y'=>1990,'w'=>1880,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color3,$font2_path_b,75,mb_strtoupper($basket['results'][$number]['fio'],'UTF-8'),array('x'=>300,'y'=>1990,'w'=>1880,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font_path,44,'преподаватель '.$ol['testname'].' '.$basket['workplace'],array('x'=>390,'y'=>2235,'w'=>1700,'h'=>0,'h_align'=>'left','v_align'=>'top'));
							textinblock($img,$color2,$font_path,31,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>1020,'y'=>2480,'w'=>1000,'h'=>0,'h_align'=>'left','v_align'=>'top'));
							textinblock($img,$color2,$font_path,32,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года          /          anglius.ru',array('x'=>0,'y'=>3240,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
						}
					}
					else $error++;
				}
				break;
			case 2:	if (!$order['place']) {$error++;}
				else {
					$ct=mysql_select('select name,teacher from contests where id='.$order['parent'],'row');
					if($ct&&isset($order['place'])) {
						if(!$ct['teacher']) {
							//конкурс учащихся
							if(strtotime($order['date'])>strtotime('2016-12-13 00:00:00')) {
								//новый
								$img=imagecreatefromjpeg('./certif_7HbghJk/2/2/'.$order['place'].'_2.jpg');
								$black= imagecolorallocate($img,0,0,0);
								$color1=imagecolorallocate($img,60,60,60);
								$color2=imagecolorallocate($img,0,35,124);
								textinblock($img,$black,$font3_path,36,'КОНКУРС',array('x'=>327,'y'=>1140,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font3_path,54,$ct['name'],
									array('x'=>240,'y'=>1220,'w'=>2000,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$black,$font3_path,36,'РАБОТА',array('x'=>240,'y'=>1570,'w'=>2000,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font_path,54,$basket['name'],
									array('x'=>240,'y'=>1652,'w'=>2000,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$black,$font3_path,36,'НАГРАЖДАЕТСЯ',array('x'=>327,'y'=>1890,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								//textinblock($img,$color2,$font3_path_i,94,$basket['fio2'],array('x'=>327,'y'=>1959,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
                                                                textinblock($img,$color2,$font3_path_i,70,$basket['fio2'],array('x'=>240,'y'=>1960,'w'=>2000,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font3_path,50,$basket['workplace'],array('x'=>240,'y'=>2130,'w'=>2000,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font3_path,50,'Координатор: '.$basket['fio'],array('x'=>327,'y'=>2510,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font_path,38,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>327,'y'=>2640,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font_path,31,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года           /           Anglius.ru',array('x'=>327,'y'=>3190,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
							} else {
								//старый
								$img=imagecreatefromjpeg('./certif_7HbghJk/2/2/'.$order['place'].'_2.jpg');
								$color1= imagecolorallocate($img, 72,92,139);
								$color2= imagecolorallocate($img, 60,60,60);
								switch($order['place']){
		                                                  case 1:$color3= imagecolorallocate($img, 178,157,51);break;
		                                                  case 2:$color3= imagecolorallocate($img, 158,158,158);break;
		                                                  case 3:$color3= imagecolorallocate($img, 153,106,41);break;
		                                                }
								textinblock($img,$color1,$font_path,44,'конкурс',array('x'=>0,'y'=>1538,'w'=>550,'h'=>0,'h_align'=>'right','v_align'=>'top'));
								textinblock($img,$color2,$font_path,56,$ct['name'],
									array('x'=>600,'y'=>1533,'w'=>1680,'h'=>0,'h_align'=>'left','v_align'=>'top'));

								textinblock($img,$color1,$font_path,44,'работа',array('x'=>0,'y'=>1688,'w'=>550,'h'=>0,'h_align'=>'right','v_align'=>'top'));
								textinblock($img,$color2,$font_path,56,$basket['name'],
									array('x'=>600,'y'=>1683,'w'=>1680,'h'=>0,'h_align'=>'left','v_align'=>'top'));

								textinblock($img,$color1,$font_path,44,'координатор',array('x'=>0,'y'=>1888,'w'=>550,'h'=>0,'h_align'=>'right','v_align'=>'top'));
//								textinblock($img,$color2,$font_path_b,56,mb_strtoupper($basket['fio'],'UTF-8'),
//									array('x'=>600,'y'=>1883,'w'=>1680,'h'=>0,'h_align'=>'left','v_align'=>'top'));
								textinblock($img,$color2,$font2_path_b,56,mb_strtoupper($basket['fio'],'UTF-8'),
									array('x'=>600,'y'=>1883,'w'=>1680,'h'=>0,'h_align'=>'left','v_align'=>'top'));


								textinblock($img,$color1,$font_path,44,'награждается',array('x'=>0,'y'=>2022,'w'=>550,'h'=>0,'h_align'=>'right','v_align'=>'top'));

//								textinblock($img,$color3,$font_path_b,75,mb_strtoupper($basket['fio2'],'UTF-8'),array('x'=>600,'y'=>2022,'w'=>1680,'h'=>0,'h_align'=>'left','v_align'=>'top'));
								textinblock($img,$color3,$font2_path_b,75,mb_strtoupper($basket['fio2'],'UTF-8'),array('x'=>600,'y'=>2022,'w'=>1680,'h'=>0,'h_align'=>'left','v_align'=>'top'));
								textinblock($img,$color2,$font_path,44,$basket['workplace'],array('x'=>1036,'y'=>2300,'w'=>1250,'h'=>0,'h_align'=>'left','v_align'=>'top'));
								textinblock($img,$color2,$font_path,31,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>1020,'y'=>2550,'w'=>1000,'h'=>0,'h_align'=>'left','v_align'=>'top'));
								textinblock($img,$color2,$font_path,32,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года          /          anglius.ru',array('x'=>0,'y'=>3243,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
							}
						}
						else {
							//конкурс учителей
							if(strtotime($order['date'])>strtotime('2016-12-13 00:00:00')) {
								//новый
								$img=imagecreatefromjpeg('./certif_7HbghJk/2/1/'.$order['place'].'_2.jpg');
								$black= imagecolorallocate($img,0,0,0);
								$color1=imagecolorallocate($img,60,60,60);
								$color2=imagecolorallocate($img,0,35,124);
								textinblock($img,$black,$font3_path,34,'КОНКУРС',array('x'=>327,'y'=>1256,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font3_path,48,$ct['name'],
									array('x'=>327,'y'=>1323,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$black,$font3_path,34,'РАБОТА',array('x'=>327,'y'=>1588,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font_path,34,$basket['name'],
									array('x'=>327,'y'=>1669,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$black,$font3_path,34,'НАГРАЖДАЕТСЯ',array('x'=>327,'y'=>1890,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color2,$font3_path_i,84,$basket['fio2'],array('x'=>300,'y'=>1959,'w'=>1870,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font3_path,38,$basket['workplace'],array('x'=>327,'y'=>2116,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font_path,31,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>327,'y'=>2529,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color1,$font_path,31,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года           /           Anglius.ru',array('x'=>327,'y'=>3190,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
							} else {
								$img=imagecreatefromjpeg('./certif_7HbghJk/2/1/'.$order['place'].'.jpg');
								$color1= imagecolorallocate($img, 72,92,139);
								$color2= imagecolorallocate($img, 60,60,60);
								switch($order['place']){
		                                                  case 1:$color3= imagecolorallocate($img, 178,157,51);break;
		                                                  case 2:$color3= imagecolorallocate($img, 158,158,158);break;
		                                                  case 3:$color3= imagecolorallocate($img, 153,106,41);break;
		                                                }
								textinblock($img,$color1,$font_path,44,'конкурс',array('x'=>100,'y'=>1456,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color2,$font_path,56,$ct['name'],
									array('x'=>100,'y'=>1570,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));

								textinblock($img,$color2,$font_path,43,$basket['name'],
									array('x'=>100,'y'=>1750,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));

								textinblock($img,$color1,$font_path,44,'награждается',array('x'=>100,'y'=>1934,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
//								textinblock($img,$color3,$font_path_b,75,mb_strtoupper($basket['fio2'],'UTF-8'),array('x'=>0,'y'=>2039,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color3,$font2_path_b,75,mb_strtoupper($basket['fio2'],'UTF-8'),array('x'=>0,'y'=>2039,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
//								textinblock($img,$color2,$font_path,44,'преподаватель '.$basket['workplace'],array('x'=>100,'y'=>2326,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color2,$font_path,44,'преподаватель '.$basket['workplace'],array('x'=>100,'y'=>2326,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'center'));
								textinblock($img,$color2,$font_path,31,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>100,'y'=>2408,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
								textinblock($img,$color2,$font_path,32,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года          /          anglius.ru',array('x'=>0,'y'=>3240,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
							}
						}
					}
					else $error++;
				}
				break;
			case 3:	
				$pub=mysql_select('select name,display,fio,workplace from publications where id='.$order['parent'],'row');
				if($pub&&$pub['display']>0) {
					$img=imagecreatefromjpeg('./certif_7HbghJk/3/blank.jpg');
//					$black = imagecolorallocate($img, 0, 0, 0);
					$color1= imagecolorallocate($img, 0, 22, 80);
					$color2= imagecolorallocate($img, 0, 40, 150);

					textinblock($img,$color1,$font1_path,45,'№ '.$order['id'].sprintf('%02s',0).' от '.date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'%d.%m.%Y'),array('x'=>0,'y'=>805,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
//					textinblock($img,$color1,$font_path_b,50,'Настоящим подтверждается публикация авторских материалов',array('x'=>360,'y'=>832,'w'=>2786,'h'=>0,'h_align'=>'center','v_align'=>'top'));
					textinblock($img,$color1,$font2_path_b,68,$pub['fio'],array('x'=>0,'y'=>978,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
//					textinblock($img,$color1,$font_path_b,50,'педагог',array('x'=>360,'y'=>1043,'w'=>2786,'h'=>0,'h_align'=>'center','v_align'=>'top'));
					textinblock($img,$color1,$font1_path,45,str_replace('&quot;','"',$pub['workplace']),array('x'=>0,'y'=>1267,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));

//					textinblock($img,$color1,$font_path_b,50,'Опубликованный материал:',array('x'=>360,'y'=>1400,'w'=>2786,'h'=>0,'h_align'=>'center','v_align'=>'top'));
					$pub['name']=str_replace('&quot;','"',trim($pub['name']));if($pub['name'][0]!='"'){$pub['name']='"'.$pub['name'].'"';}
					textinblock($img,$color1,$font1_path,45,$pub['name'],array('x'=>0,'y'=>1610,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
//					textinblock($img,$color1,$font_path_b,50,'ссылка на материал: мир-олимпиад.рф',array('x'=>360,'y'=>1730,'w'=>2786,'h'=>0,'h_align'=>'center','v_align'=>'top'));
					textinblock($img,$color2,$font1_path,45,'ссылка на опубликованный материал: www.anglius.ru/publikatsii/'.$order['parent'].'/',array('x'=>0,'y'=>2110,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
				}
				else $error++;
				break;
			case 4:	if (!$order['place']) {$error++;}
				else {
					$tt=mysql_select('select name from tests where id='.$order['parent'],'row');
					if ($tt) {
						//конкурс учителей англ
 						if(strtotime($order['date'])>strtotime('2016-12-13 00:00:00')) {
							//новый
							$img=imagecreatefromjpeg('./certif_7HbghJk/4/'.$order['place'].'_2.jpg');
							$black= imagecolorallocate($img,0,0,0);
							$color1=imagecolorallocate($img,60,60,60);
							$color2=imagecolorallocate($img,0,35,124);
							$color3=imagecolorallocate($img,12,10,70);
							textinblock($img,$black,$font3_path,34,'КОНКУРС',array('x'=>327,'y'=>1287,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color3,$font3_path,50,$tt['name'],
								array('x'=>327,'y'=>1360,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font3_path,46,$basket['name'],
								array('x'=>327,'y'=>1596,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$black,$font3_path,34,'НАГРАЖДАЕТСЯ',array('x'=>327,'y'=>1882,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font3_path_i,94,$basket['fio'],array('x'=>327,'y'=>1951,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font3_path,44,'педагог '.$basket['workplace'],array('x'=>327,'y'=>2281,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font_path,31,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>327,'y'=>2529,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color1,$font_path,31,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года           /           Anglius.ru',array('x'=>327,'y'=>3190,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
						} else {
							//старый
							$img=imagecreatefromjpeg('./certif_7HbghJk/2/1/'.$order['place'].'.jpg');
							$color1= imagecolorallocate($img, 72,92,139);
							$color2= imagecolorallocate($img, 60,60,60);
							switch($order['place']){
								case 1:$color3= imagecolorallocate($img, 178,157,51);break;
								case 2:$color3= imagecolorallocate($img, 158,158,158);break;
								case 3:$color3= imagecolorallocate($img, 153,106,41);break;
							}
							textinblock($img,$color1,$font_path,44,'конкурс',array('x'=>100,'y'=>1456,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font_path,56,$tt['name'],
								array('x'=>100,'y'=>1570,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));

							textinblock($img,$color2,$font_path,50,$basket['name'],
								array('x'=>100,'y'=>1750,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));

							textinblock($img,$color1,$font_path,44,'награждается',array('x'=>100,'y'=>1934,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
//							textinblock($img,$color3,$font_path_b,75,mb_strtoupper($basket['fio'],'UTF-8'),array('x'=>0,'y'=>2039,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color3,$font2_path_b,75,mb_strtoupper($basket['fio'],'UTF-8'),array('x'=>0,'y'=>2039,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font_path,44,'преподаватель '.$basket['workplace'],array('x'=>100,'y'=>2326,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'center'));
							textinblock($img,$color2,$font_path,31,'регистрационный номер диплома '.$order['id'].sprintf('%02s',$number),array('x'=>100,'y'=>2408,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
							textinblock($img,$color2,$font_path,32,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года          /          anglius.ru',array('x'=>0,'y'=>3240,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
						}
					}
					else $error++;
				}
				break;
			case 6: 
				$basket['name']=trim($basket['name']);if($basket['name'][0]!='"'){$basket['name']='"'.$basket['name'].'"';}
				$basket['section']=trim($basket['section']);if($basket['section'][0]!='"'){$basket['section']='"'.$basket['section'].'"';}
				switch ($number) {
					case 1:	//диплом
						$img=imagecreatefromjpeg('./certif_7HbghJk/6/diplom.jpg');
						$black = imagecolorallocate($img, 0, 0, 0);
						$color1= imagecolorallocate($img, 178,157,52);
//						$color2= imagecolorallocate($img, 100,100,100);
				$basket['confname']=str_replace(array('еждународная ','сероссийская ','онференция '),array('еждународной ','сероссийской ','онференции '),$basket['confname']);
						textinblock($img,$black,$font1_path,55,$basket['confname'],array('x'=>280,'y'=>1280,'w'=>1920,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$black,$font1_path,55,str_replace(array('№','&quot;'),array('N','"'),$basket['name']),array('x'=>100,'y'=>1700,'w'=>2280,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$color1,$font2_path_b,70,mb_strtoupper($basket['fio'],'utf-8'),array('x'=>0,'y'=>2170,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$color1,$font1_path,45,str_replace('&quot;','"',$basket['workplace']),array('x'=>0,'y'=>2450,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$black,$font1_path,44,'регистрационный номер диплома '.$order['id'].sprintf('%02s',0).' от '.date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'%d.%m.%Y'),array('x'=>0,'y'=>3210,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						break;
					case 2: //сертификат
						$img=imagecreatefromjpeg('./certif_7HbghJk/6/certificate.jpg');
						$black = imagecolorallocate($img, 0, 0, 0);
						$color1= imagecolorallocate($img, 0,60,255);
						textinblock($img,$black,$font1_path,37,'регистрационный номер сертификата '.$order['id'].sprintf('%02s',0).' от '.date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'%d.%m.%Y'),array('x'=>0,'y'=>55,'w'=>3240,'h'=>0,'h_align'=>'right','v_align'=>'top'));
				$basket['confname']=str_replace(array('еждународная ','сероссийская ','онференция '),array('еждународной ','сероссийской ','онференции '),$basket['confname']);
						textinblock($img,$black,$font1_path_b,62,$basket['confname'],array('x'=>0,'y'=>635,'w'=>3508,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$color1,$font2_path_b,85,$basket['fio'],array('x'=>0,'y'=>852,'w'=>3508,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$color1,$font1_path_b,75,str_replace('&quot;','"',$basket['workplace']),array('x'=>0,'y'=>995,'w'=>3508,'h'=>0,'h_align'=>'center','v_align'=>'top'));
//						textinblock($img,$black,$font1_path_b,50,'Секция конференции',array('x'=>0,'y'=>1340,'w'=>3508,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$black,$font1_path_b,62,str_replace('&quot;','"',$basket['section']),array('x'=>0,'y'=>1370,'w'=>3508,'h'=>0,'h_align'=>'center','v_align'=>'top'));
//						textinblock($img,$black,$font1_path_b,50,'Представленный материал',array('x'=>0,'y'=>1642,'w'=>3508,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$black,$font1_path_b,62,str_replace('&quot;','"',$basket['name']),array('x'=>0,'y'=>1775,'w'=>3508,'h'=>0,'h_align'=>'center','v_align'=>'top'));
//						textinblock($img,$black,$font1_path,52,date2($order['date'],'d month y'),array('x'=>130,'y'=>2336,'w'=>3378,'h'=>0,'h_align'=>'left','v_align'=>'top'));
						break;
					case 3: //св-во
						$img=imagecreatefromjpeg('./certif_7HbghJk/6/publication.jpg');
						$color1= imagecolorallocate($img, 0, 22, 80);
						$color2= imagecolorallocate($img, 0, 39, 150);
						textinblock($img,$color1,$font1_path,45,'№ '.$order['id'].sprintf('%02s',0).' от '.date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'%d.%m.%Y'),array('x'=>0,'y'=>805,'w'=>2480,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$color1,$font1_path,45,str_replace('&quot;','"',$basket['confname']),array('x'=>260,'y'=>1058,'w'=>1960,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$color1,$font2_path_b,70,$basket['fio'],array('x'=>260,'y'=>1280,'w'=>1960,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$color2,$font1_path,45,$basket['position'],array('x'=>260,'y'=>1536,'w'=>1960,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$color1,$font1_path,45,str_replace('&quot;','"',$basket['workplace']),array('x'=>260,'y'=>1672,'w'=>1960,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						textinblock($img,$color1,$font1_path,45,str_replace('&quot;','"',$basket['name']),array('x'=>260,'y'=>1983,'w'=>1960,'h'=>0,'h_align'=>'center','v_align'=>'top'));
						break;
					defaut:	$error++;
				}
				break;
			default:$error++;
		}
	}
	else $error++;
}




if(!$error) {
	header('Content-type: image/jpeg');
//	header("Content-Disposition: attachment; filename=\"".$order['id'].sprintf('%02s',0)."\"");
	header('Cache-Control: max-age=0');
	imagejpeg($img);
	imagedestroy($img);
}
else {
	header("HTTP/1.0 404 Not Found");
	$page['title'] = $page['name'] = i18n('common|str_no_page_name');
	$html['module'] = 'error';
	require_once(ROOT_DIR.$config['style'].'/includes/common/template.php');
}

?>

<?php 
/**
 * 
 * @param array $q Order array
 * @param int $type Поле таблицы откуда брать данные diplom/letter 
 * @param string $default_path полный путь до файла если путь из заказа наверен ли файла уже нет
 * @return boolean
 */
function get_cert_path($q, $type, $default_path){    
    if (empty($q['cert_template']))
        return $default_path;
    
    global $config;
    
    $cert = mysql_select('SELECT * FROM cert_templates WHERE id = ' . $q['cert_template'] . ' LIMIT 1', 'row');
    //var_dump($cert); die();
    $path = ($cert) ? "./files/cert_templates/{$q['cert_template']}/{$type}/{$config['cert_templates_prefix']}-{$cert[$type]}" : false;
    //var_dump($path); die();
    return ($path && file_exists($path)) ? $path : $default_path;
    
}
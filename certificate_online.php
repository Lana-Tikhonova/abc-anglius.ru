<?php

//66.79->50
//45.83->35
//58.33->43
//150->115

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

$font_path = ROOT_DIR.'templates/fonts/verdana.ttf';
$font_path_b = ROOT_DIR.'templates/fonts/verdanab.ttf';

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
  $str=str_replace(array('&quot;','«','»'),array('"','"','"'),$str);
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
$order=mysql_select('
	select o.*, ol.name name
	from orders o
	LEFT JOIN olympiads_tests ot ON ot.id = o.parent
	LEFT JOIN olympiads ol ON ot.olympiad=ol.id
	where o.id='.(base64_decode($_GET['key'])+0).' and paid=1','row');
if($order) {
	$basket=unserialize($order['basket']);
	$ol=mysql_select('select t.*, o.name testname 
					from online_olympiads_tests AS t 
					LEFT JOIN online_olympiads AS o ON o.id = t.olympiad  
					where t.id='.$order['parent'],'row');
	//var_dump($ol); die();
	//$img=imagecreatefromjpeg('./certif_7HbghJk/'.$order['type'].'/'.$basket['template'].'/'.$order['place'].'.jpg');
	$img=imagecreatefromjpeg(get_cert_path($order, 'diplom'.($order['place']?('_'.$order['place']):'_4'), './certif_7HbghJk/'.$order['type'].'/'.$basket['template'].'/'.$order['place'].'.jpg'));
	//var_dump(); die();
	$gray  = imagecolorallocate($img, 47, 47, 47);
	$gray2 = imagecolorallocate($img, 63, 63, 63);
	$gray3 = imagecolorallocate($img,128,128,128);
	$white = imagecolorallocate($img,255,255,255);
	$gold  = imagecolorallocate($img,196,165, 32);
	$color2=imagecolorallocate($img,0,35,124);
	
	list($orderid,$number,$pedagog)=explode('.',base64_decode($_GET['key']));
	
	if ($number==='0' && $pedagog === '0') {		//var_dump($basket); die();
		//благодарственное письмо
		$img=imagecreatefromjpeg(get_cert_path($order, 'letter', './certif_7HbghJk/1/2/letter_2.jpg'));
		$black = imagecolorallocate($img, 0, 0, 0);
		$color1=imagecolorallocate($img,27,27,27);
		$color2=imagecolorallocate($img,0,35,124);
		$color3=imagecolorallocate($img,13,13,13);
		$color4=imagecolorallocate($img,60,60,60);
		textinblock($img,$black,$font3_path,37,'НАГРАЖДАЕТСЯ',array('x'=>327,'y'=>1105,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
		textinblock($img,$color2,$font3_path_i,90,$basket['director'],array('x'=>327,'y'=>1230,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
		//textinblock($img,$color1,$font1_path,43,'педагог, ',array('x'=>327,'y'=>1560,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
		textinblock($img,$color1,$font1_path,43,str_replace('&quot;','"',$basket['workplace']),array('x'=>327,'y'=>1660,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
		textinblock($img,$color3,$font1_path,43,'за подготовку победителей (участников)',array('x'=>327,'y'=>1790,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
		$year=date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'%Y')+0;
		$years=(date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'%m')+0>=8)?$year.'-'.($year+1):($year-1).'-'.$year;
		textinblock($img,$black,$font1_path,43,
			str_replace(array('еждународный','еждународная ','лимпиада ','икторина ', 'ексическая', 'ический', 'онкурс','страноведческая'),array('еждународного','еждународной ','лимпиады ','икторины ', 'ексической', 'ического','онкурса','страноведческой'),$ol['testname']).', '.
			' проводимой на портале дистанционных проектов по английскому языку "Англиус" в '.
			$years.' учебном году',
			array('x'=>327,'y'=>1860,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
		textinblock($img,$color4,$font1_path,31,'регистрационный номер '.$order['id'].sprintf('%02s',$number),array('x'=>327,'y'=>2529,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
		textinblock($img,$color4,$font_path,31,date2(($order['date_paid']!=0?$order['date_paid']:$order['date']),'d month y').' года           /           Anglius.ru',array('x'=>327,'y'=>3190,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
                // [4.31] Ссылка на диплом
                //textinblock($img,$color1,$font_path,31,$current_link,array('x'=>327,'y'=>3200,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);			
	} else {
	    switch($basket['template']) {
		    case 1:
		    case 2:
		    case 3:
		    default:
			    //textinblock($img,$white,$font_path,37,$order['name'],array('x'=>465,'y'=>1029,'w'=>1550,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    $o_name = str_replace(array('этап ','ая ', 'лимпиада','икторины','икторина'),array('этапа ','ой ','лимпиады','икторины','икторины'),$ol['testname']);
			    $o_name = str_replace(array('редметной '),array('редметная '),$o_name);
			    textinblock($img,$gold,$font_path,56,$o_name,
								    array('x'=>327,'y'=>1325,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    //textinblock($img,$gray2,$font_path,74,(in_array($order['place'],array(1,2,3))?'Победитель '.str_pad('',$order['place'],'I').' Степени':'Дипломант'),array('x'=>465,'y'=>1222,'w'=>1550,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    //textinblock($img,$gray2,$font_path,31,'№ ONL-'.$order['id'],array('x'=>465,'y'=>1476,'w'=>1550,'h'=>0,'h_align'=>'left','v_align'=>'top'));			
			    //textinblock($img,$gray3,$font_path,43,'Награждается:',array('x'=>465,'y'=>1897,'w'=>1550,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    textinblock($img,$black,$font_path,34,'Награждается',array('x'=>327,'y'=>1653,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    //textinblock($img,$gold,$font_path,92,$basket['f'].' '.$basket['io'],array('x'=>465,'y'=>1981,'w'=>1550,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    textinblock($img,$gold,$font_path,88,$basket['f'].' '.$basket['io'],array('x'=>180,'y'=>1730,'w'=>2100,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    //if(isset($basket['kk'])) textinblock($img,$gold,$font_path,43,$basket['kk'],array('x'=>465,'y'=>2265,'w'=>1850,'h'=>0,'h_align'=>'center','v_align'=>'top'));			
			    if(isset($basket['kk'])) textinblock($img,$color2,$font_path,43,stripcslashes($basket['kk']),array('x'=>200,'y'=>2000,'w'=>2080,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    //textinblock($img,$gold,$font_path,43,$basket['workplace'],array('x'=>465,'y'=>2365,'w'=>1550,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    textinblock($img,$color2,$font_path,38,$basket['workplace'],array('x'=>200,'y'=>2115,'w'=>2080,'h'=>0,'h_align'=>'center','v_align'=>'top'));
				if(isset($basket['director'])&&$basket['director']!='')
			    textinblock($img,$color1,$font_path,34,'Руководитель: '.@$basket['director'],array('x'=>200,'y'=>2480,'w'=>2080,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$color1,$font_path,31,'регистрационный номер диплома № ONL-'.$order['id'],array('x'=>200,'y'=>2580,'w'=>2080,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    //textinblock($img,$gray2,$font_path,34,date2($order['date'],'d month y').' года',array('x'=>465,'y'=>1549,'w'=>1550,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    textinblock($img,$color1,$font_path,31,date2($order['date'],'d month y').' года           /           г. Краснодар           /           Anglius.ru',array('x'=>327,'y'=>3190,'w'=>1832,'h'=>0,'h_align'=>'center','v_align'=>'top'),false);
			    //textinblock($img,$gray2,$font_path,34,'г. Краснодар',array('x'=>465,'y'=>1608,'w'=>1550,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    break;
		    /* case 2:	
			    textinblock($img,$gray2,$font_path,37,'№ ONL-'.$order['id'],array('x'=>575,'y'=>792,'w'=>1330,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray,$font_path,50,$order['name'],array('x'=>555,'y'=>1482,'w'=>1370,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray2,$font_path,74,(in_array($order['place'],array(1,2,3))?'Победитель '.str_pad('',$order['place'],'I').' Степени':'Дипломант'),array('x'=>575,'y'=>1890,'w'=>1330,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray2,$font_path,34,date2($order['date'],'d month y').' года',array('x'=>575,'y'=>2017,'w'=>1330,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray2,$font_path,34,'г. Краснодар',array('x'=>575,'y'=>2076,'w'=>1330,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray3,$font_path,43,'Награждается:',array('x'=>456,'y'=>2182,'w'=>1568,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    textinblock($img,$gold,$font_path,92,$basket['f'].' '.$basket['io'],array('x'=>456,'y'=>2266,'w'=>1568,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    if(isset($basket['kk'])) textinblock($img,$gold,$font_path,43,$basket['kk'],array('x'=>456,'y'=>2550,'w'=>1850,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    textinblock($img,$gold,$font_path,43,$basket['workplace'],array('x'=>456,'y'=>2650,'w'=>1568,'h'=>0,'h_align'=>'left','v_align'=>'top'));
			    break;
		    case 3:	
    //			textinblock($img,$gray,$font_path,50,$order['name'],array('x'=>106,'y'=>918,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray,$font_path,37,$order['name'],array('x'=>106,'y'=>920,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
    //			textinblock($img,$white,$font_path,100,(in_array($order['place'],array(1,2,3))?'Победитель '.str_pad('',$order['place'],'I').' Степени':'Дипломант'),array('x'=>106,'y'=>1075,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$white,$font_path,74,(in_array($order['place'],array(1,2,3))?'Победитель '.str_pad('',$order['place'],'I').' Степени':'Дипломант'),array('x'=>106,'y'=>1075,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
    //			textinblock($img,$gray2,$font_path,42,'№ '.$order['id'],array('x'=>106,'y'=>1220,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray2,$font_path,31,'№ ONL-'.$order['id'],array('x'=>106,'y'=>1220,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
    //			textinblock($img,$gold,$font_path,125,$order['f'].' '.$order['io'],array('x'=>106,'y'=>1898,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gold,$font_path,92,$basket['f'].' '.$basket['io'],array('x'=>206,'y'=>1898,'w'=>2066,'h'=>0,'h_align'=>'center','v_align'=>'top'));
    //			textinblock($img,$gray2,$font_path,58,$order['workplace'],array('x'=>106,'y'=>2066,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    if(isset($basket['kk'])) textinblock($img,$gray2,$font_path,43,$basket['kk'],array('x'=>106,'y'=>2230,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray2,$font_path,43,$basket['workplace'],array('x'=>106,'y'=>2330,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
    //			textinblock($img,$gray2,$font_path,46,date2($order['date'],'d month y').' года',array('x'=>106,'y'=>3169,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray2,$font_path,34,date2($order['date'],'d month y').' года',array('x'=>106,'y'=>3169,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
    //			textinblock($img,$gray2,$font_path,46,'г. Краснодар',array('x'=>106,'y'=>3227,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    textinblock($img,$gray2,$font_path,34,'г. Краснодар',array('x'=>106,'y'=>3227,'w'=>2266,'h'=>0,'h_align'=>'center','v_align'=>'top'));
			    break;*/
	    }
	}
	header('Content-type: image/jpeg');
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


function get_cert_path($q, $type, $default_path){  
	$basket = unserialize($q['basket']);
    if (empty($basket['template']))
        return $default_path;
    
    global $config;
    
    $cert = mysql_select('SELECT * FROM cert_templates WHERE id = ' . $basket['template'] . ' LIMIT 1', 'row');
    //var_dump($cert); die();
    $path = ($cert) ? "./files/cert_templates/{$basket['template']}/{$type}/{$config['cert_templates_prefix']}-{$cert[$type]}" : false;
    //var_dump($path); die();
    return ($path && file_exists($path)) ? $path : $default_path;
    
}

?>
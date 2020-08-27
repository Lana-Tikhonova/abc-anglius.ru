<?php

//массив возможных количеств выдачи записей
$count_array = array(30);
//номер страницы с записями
$n = (isset($_GET['n']) && $_GET['n']>=1) ? intval($_GET['n']) : 1;
//количество записей на страницу
$c = (isset($_GET['c']) && in_array($_GET['c'],$count_array)) ? $_GET['c'] : $count_array[0];
//полное количество записей
if (isset($m[2])) $num_rows = $m[2];
else $num_rows = mysql_select($query,'num_rows',$cache);

//КОД ПАГИНАТОРА ***************************************************************
if ($num_rows>0 && $c>0) {
	//если количество переданное через урл больше реального количества то сравнивается
	if ($c>$num_rows) $c = $num_rows;
	//количество страниц пагинатора
	$quantity = ceil($num_rows/$c);

	//количество ссылок
	$lc = 7;

	//страниц меньше или равно $lc
	if ($quantity <= $lc) {
		for ($i=1; $i<=$quantity; $i++) $list[] = array($i,$i);

	//если страниц пагинатора больше $lc, так как пагинатор расчитан только на $lc ссылок
	} else {
		//активная в начале  [1][2][3][4][5][..][100], если она не замыкает группу (5)
		if ($n < ($e = $lc - 2)) {
			for ($i=1; $i<=$e; $i++) $list[] = array($i,$i);			//$lc-2 первых ссылок
			$list[] = array(ceil(($quantity + $e)/2),0);				//[..]
			$list[] = array($quantity,$quantity);						//последняя ссылка

		//активная в коце [1][..][96][97][98][99][100], если она не начинает группу (96)
		} elseif ($n > ($s = $quantity - $lc + 2 + 1)) {
			$list[] = array(1,1);										//первая ссылка
			$list[] = array(ceil(($s + 1)/2),0);						//[..]
			for ($i = $s; $i<=$quantity; $i++) $list[] = array($i,$i);	//$lc-2 последних ссылок

		//активная в середине [1][..][49][50][51][..][100]
		} else {
			$s = $n - ceil(($lc - 4 - 1)/2);
			$e = $n + floor(($lc - 4 - 1)/2);

			$list[] = array(1,1);										//первая ссылка
			$list[] = array((ceil(($s + 1)/2)),0);						//[..]
			for ($i = $s; $i<=$e; $i++) $list[] = array ($i,$i);		//$lc-4 средних ссылок
			$list[] = array(ceil(($quantity + $e)/2),0);				//[..]
			$list[] = array($quantity,$quantity);						//последняя ссылка
		}
	}
}

//HTML *************************************************************************
$get = $_GET;
unset($get['u'],$get['n']);
$url = http_build_query($get);
if ($url) $url = $url.'&amp;';
$clear =  '/';
foreach ($u as $k=>$v) if ($v) $clear.=$v.'/';
//если есть пагинатор
if (isset($list) && count($list)>1) {
	$pagination = '<div class="pagination pagination_normal">';
	$pagination .= '<ul class="pagination">';
	if ($n==1) $pagination.= '<li class="disabled"><span class="button">&#171;</span></li>';
	else $pagination.= '<li><a class="button" href="'.(($url=='' AND $n==2) ? $clear : '?'.$url.'n='.($n-1)).'">&#171;</a></li>';
	foreach ($list as $k=>$v) {
		$name = $v[1]==0 ? '...' : $v[0];
		if ($v[0]==$n) $pagination.= '<li class="current"><span class="button">'.$name.'</span></li>';
		else $pagination.= '<li><a class="button" href="'.(($url=='' AND $v[1]==1) ? $clear : '?'.$url.'n='.$v[0]).'">'.$name.'</a></li>';
	}
	if ($n==$quantity) $pagination.= '<li class="disabled"><span class="button">&#187;</span></li>';
	else $pagination.= '<li><a class="button" href="?'.$url.'n='.($n+1).'">&#187;</a></li>';
	$pagination.= '</ul></div>';
	$pagination = '{content}'.$pagination;
//если нет результата
} else $pagination = '{content}';

//QUERY ************************************************************************
$begin = $n*$c-$c;
$query.= ' LIMIT '.$begin.','.$c;

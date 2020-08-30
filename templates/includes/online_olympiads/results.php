
<table class="anstbl" style='float:none;'>
<tr><th valign=top class="fio"><?=i18n('olympiads|fio2')?></th>
<?php $basket = $q['basket']; //var_dump($basket);
	echo '<th>Скачать диплом</th></tr>';        
        echo '<tr><td>'.$basket['f'].' '.$basket['io'].'</td>';		
        echo '<td><a target="_blank" href="/certificate_online.php?key='.base64_encode($q['id']).'">Скачать диплом ';
        switch ($q['place']) {
                case 1:echo 'победителя, I место';break;
                case 2:echo 'победителя, II место';break;
                case 3:echo 'победителя, III место';break;
                default:echo 'участника';
        }
        echo '</a></td></tr>';
?>
</table>

<?php 
  if(!empty($q['basket']['director'])) {
	//echo '<div style="text-align:right;padding-right:30px;">(<a class="down" href="/zip.php?key='.base64_encode($q['id'].'.0').'">Скачать архив с дипломами</a>)</div>';       
	echo '<div><a target="_blank" class="down" href="/certificate_online.php?key='.base64_encode($q['id'].'.0.0').'">Скачать благодарственное письмо</a> - '.$q['basket']['director'].'</div>';
 }
?>
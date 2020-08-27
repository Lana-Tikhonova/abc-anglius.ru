<table class="anstbl" style='float:none;'>
<tr><th valign=top class="fio"><?=i18n('olympiads|fio2')?></th>
<?php $basket = $q['basket']; //var_dump($basket);
	echo '<th>Скачать диплом</th></tr>';        
        echo '<tr><td>'.$basket['f'].' '.$basket['io'].'</td>';		
        echo '<td><a target="_blank" href="/certificate_online.php?key='.base64_encode($q['id']).'">Скачать диплом ';
        switch ($q['place']) {
                case 1:echo 'победителя I степени';break;
                case 2:echo 'победителя II степени';break;
                case 3:echo 'победителя III степени';break;
                default:echo 'участника';
        }
        echo '</a></td></tr>';
?>
</table>
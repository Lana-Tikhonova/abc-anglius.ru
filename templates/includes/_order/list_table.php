<?php
if($q['type']==3){
    $basket['name']=mysql_select('SELECT name FROM '.$config['types_modules'][$q['type']].' WHERE id='.$q['parent'],'string');
} else {
    if(isset($q['basket'])) $basket=unserialize($q['basket']);
    if($q['type']==1||$q['type']==8) {
        $basket['name']=$q['name'];
    }
}
?>

<? if($i==1): ?>
    <div class='list_cont orders'>
        <table>
            <thead>
                <th>№</th>
                <th>Дата</th>
                <th>Тип</th>
                <th>Наименование</th>
                <th></th>
            </thead>
            <tbody>
<? endif; ?>
                
                <tr>
                    <td class="number"><?=$q['id']?></td>
                    <td class="date"><?=date('Y.m.d',strtotime($q['date']))?></td>
                    <td class="type"><?=($q['type']==8?$config['types'][1]:$config['types'][$q['type']])?></td>
                    <td class="name"><?=@$basket['name']?></td>
                    <td class="goto"><a href="/<?=$modules['profile']?>/orders/<?=$q['id']?>/">перейти к дипломам</a></td>
                </tr>
            
<? if($i==$num_rows): ?>
            </tbody>
        </table>
    </div>
<? endif; ?>


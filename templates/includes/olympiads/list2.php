<?php if ($i<=6) { ?>
    <div class='block' <?=($i%2==1)?"style='margin-right:20px;'":''?>>
      <img class='img' src='/files/olympiads/<?=$q['id']?>/img/p-<?=$q['img']?>'>
      <div class='txt'>
       <img class='float-left' src='/templates/images/flag.gif'><div class='ct text-center'>ДЛЯ <?=$q['teacher']?'ПЕДАГОГОВ':'УЧАЩИХСЯ И СТУДЕНТОВ'?></div><div class='clear-both'></div>
       <div class='name'><?=$q['name']?></div>
       <div><?=$q['shortdesc']?></div>
      </div>
      <div class='clear-both' style='margin-bottom:19px;'></div>
      <div class='dates'>По <?=date2($q['date2'],'d month y')?> года</div>
      <a href='/<?=$u[1]?>/<?=$q['id']?>-<?=$q['url']?>/' class='particip text-center gradient1 border5'>ПРИНЯТЬ УЧАСТИЕ</a>
    </div>
<?php } ?>
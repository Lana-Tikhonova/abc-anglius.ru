<?php if ($i<=6) { ?>
    <div class='block' <?=($i%2==1)?"style='margin-right:20px;'":''?>>
      <img class='img' src='/files/tests/<?=$q['id']?>/img/p-<?=$q['img']?>'>
      <div class='txt'>
       <div class='name'><?=$q['name']?></div>
       <div><?=$q['shortdesc']?></div>
      </div>
      <div class='clear-both' style='margin-bottom:19px;'></div>
      <a href='/<?=$u[1]?>/<?=$q['id']?>-<?=$q['url']?>/' class='particip text-center gradient1 border5'>ПРИНЯТЬ УЧАСТИЕ</a>
    </div>
<?php } ?>

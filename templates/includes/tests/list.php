      <div class='block'>
        <img class='img' src='/files/tests/<?=$q['id']?>/img/p-<?=$q['img']?>'>
        <div class='txt'>
         <div class='name'><?=$q['name']?></div>
         <div><?=$q['shortdesc']?></div>
         <div class='info'><img src='/templates/images/info.png'><span><b>СТОИМОСТЬ УЧАСТИЯ</b> <?=$q['price']?> рублей.</span></div>
         <a href='/<?=$modules['tests']?>/<?=$q['id']?>-<?=$q['url']?>/' class='particip text-center gradient1 border5'>ПРИНЯТЬ УЧАСТИЕ</a>
        </div>
        <div class='clear-both'></div>
      </div>

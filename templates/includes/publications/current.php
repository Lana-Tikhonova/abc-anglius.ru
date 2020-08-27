<div class='container'>
<b>Автор</b>: <?=$q['fio']?><br>
<b>Учебное заведение</b>: <?=$q['workplace']?><br>
<b>Наименование материала</b>: Статья<br>
<b>Тема</b>: <?=$q['name']?><br>
<b>Дата публикации</b>: <?=date2($q['date'],'d month y')?> г.<br>
<a class='leftitem' style='margin-left:0px;margin-top:20px' href='/files/publications/<?=$q['id']?>/file/<?=$q['file']?>'>Скачать публикацию</a>
<a class='leftitem' style='margin-left:0px;margin-top:20px' href='/<?=$modules['publications']?>/'>Назад к списку публикаций</a>
<br><br>
</div>
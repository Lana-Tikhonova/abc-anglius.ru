<? //var_dump($q) ?>
<div class="search_diplom-results" style="padding-bottom: 30px;">
	<div class="container">
	<? if($q && (!empty($q['basket']['results']) OR !empty($q['basket']['letters'])) ): ?>
		
		<? if(!empty($q['basket']['results'])): ?><h3>Дипломы</h3><? endif; ?>
		<? foreach ($q['basket']['results'] as $v): ?>
			<p><a href="<?= $v['url'] ?>" target="_blank" style="color: #333;"><?= $v['fio'] ?></a></p> 
		<? endforeach; ?>
			
		<? if(!empty($q['basket']['letters'])): ?><br><br><h3>Благодарственные письма</h3><? endif; ?>
		<? foreach ($q['basket']['letters'] as $v): ?>
			<p><a href="<?= $v['url'] ?>" target="_blank" style="color: #333;"><?= $v['fio'] ?></a></p> 
		<? endforeach; ?>
	<? else: ?>
		<p>Не найдено заявок или дипломов в заявке</p>
	<? endif; ?>
	</div>	
</div>


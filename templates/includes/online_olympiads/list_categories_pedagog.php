<?php if($i==1) { 
    //$category_id = FALSE;
    //$category_name = $q['oc_name'];    
?>
    <div class='container lastk lkoo'>
<?php if($q['type']==91&&i18n('common|pedagog_txt')) echo '<div style="margin-bottom:30px;">'.i18n('common|pedagog_txt').'</div>'; ?>
        <div class='list olimp'>
<?php } ?>
			<?
				$category_name = ($q['oc_name']) ? $q['oc_name'] : i18n('common|olimp_category_empty'); 
				$link='/online_tests/'.$q['testid'].'/';    
			?>

			<div class="block">
				<? /* if($q['oc_img']): ?><img class="img" src="/files/online_olympiads_categories/<?=$q['category']?>/img/p-<?=$q['oc_img']?>"><? endif; */ ?>                               			
				<? if($q['oc_img']): ?><img class="img" src="/files/online_olympiads<?=$q['type']?>/<?=$q['id']?>/img/p-<?=$q['oc_img']?>"><? endif; ?>                               			
				<div class="txt">
					<img class="float-left" src="/templates/images/flag.gif"><div class="ct text-center"><?=$category_name?></div>
					<div class="clear-both"></div>
					<div class="name"><?=$q['name']?></div>
					<div><?=$q['oc_text']?></div>
					<? /* <div class="info"><img src="/templates/images/info.png"><span><b>СТОИМОСТЬ УЧАСТИЯ</b> <?=$config['price'.$q['type']]?> рублей.</span></div>	*/	?>			
					<a href="<?=$link?>" class="particip text-center gradient1 border5"><?=i18n('common|olimp_accept2')?></a>
				</div>
				<div class="clear-both"></div>
			</div>			                                        
<?php if($i==$num_rows) { ?>                
        </div>
    </div>          
<?php } ?>
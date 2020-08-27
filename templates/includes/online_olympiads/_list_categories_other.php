<?php if($i==1) {	 //var_dump($q)
    //$category_id = FALSE;
    //$category_name = $q['oc_name'];  
	if($q['type']==93) $grades=array(1=>'1 класс',2=>'2 класс',3=>'3 класс',4=>'4 класс',5=>'5 класс',6=>'6 класс',7=>'7 класс',8=>'8 класс',9=>'9 класс',10=>'10 класс',11=>'11 класс');
	elseif($q['type']==94) $grades=array(1=>'1 курс',2=>'2 курс',3=>'3 курс',4=>'4 курс',5=>'5 курс');
?>
    <div class='container lastk lkoo'>
<?php if($q['type']==91&&i18n('common|pedagog_txt')) echo '<div style="margin-bottom:30px;">'.i18n('common|pedagog_txt').'</div>'; ?>
        <div class='list olimp'>
<?php } ?>
			<?
				$category_name = ($q['klass']) ? $grades[$q['klass']] : i18n('common|olimp_category_empty'); 
				//$category_name = ($q['oc_name']) ? $q['oc_name'] : $category_name; 
				//$link='/online_tests/'.$q['testid'].'/';  
				$link='/'.$u[1].'/'.$q['id'].'-'.$q['url'].'/';
			?>

			<div class="block">
				<? /* if($q['oc_img']): ?><img class="img" src="/files/online_olympiads_categories/<?=$q['category']?>/img/p-<?=$q['oc_img']?>"><? endif; */ ?>                               			
				<? if($q['oc_img']): ?><img class="img" src="/files/online_olympiads<?=$q['type']?>/<?=$q['id']?>/img/p-<?=$q['oc_img']?>"><? endif; ?>                               			
				<div class="txt">
					<? /* <img class="float-left" src="/templates/images/flag.gif"><div class="ct text-center"><?=$category_name?></div> */ ?>
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
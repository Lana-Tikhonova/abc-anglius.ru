<?php
$img = $q['img'] ? '/files/shop_products/'.$q['id'].'/img/p-'.$q['img'] : '/'.$config['style'].'/images/no_img.png';
$title = filter_var($q['name'],FILTER_SANITIZE_STRING);
$alt = $q['img'] ? 'p-'.$q['img'] : i18n('common|wrd_no_photo');
$url = '/'.$modules['shop'].'/'.$q['category'].'-'.$q['category_url'].'/'.$q['id'].'-'.$q['url'].'/';
?>
<div class="shop_product_random">
	<h4><?=i18n('shop|product_random',true)?></h4>
	<div class="border clearfix">
		<a class="img" href="<?=$url?>" title="<?=$title?>"><img src="<?=$img?>" alt="<?=$title?>" /></a>
		<a class="name" href="<?=$url?>" title="<?=$title?>"><?=$q['name']?></a>
		<?php if ($q['price']>0) {?>
		<div class="price">
			<span><?=$q['price']?></span> <?=i18n('shop|currency')?>
			<?php if ($q['price2']>0) {?>
			<s><?=$q['price2']?> <?=i18n('shop|currency')?></s>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if (isset($modules['basket']) AND $q['price']>0) {?>
		<a class="js_buy btn btn-default pull-left" data-toggle="modal" data-target="#basket_message" data-id="<?=$q['id']?>" data-price="<?=$q['price']?>" href="#" title="<?=i18n('basket|buy')?>"><i class="icon-shopping-cart"></i> <?=i18n('basket|buy')?></a>
		<?php } ?>
		<a class="btn btn-primary pull-right" href="<?=$url?>" title="<?=i18n('common|wrd_more')?>"><?=i18n('common|wrd_more')?></a>
	</div>
</div>

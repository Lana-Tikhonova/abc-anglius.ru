<?=html_sources('return','highslide_gallery')?>
<?php
$img = $q['img'] ? '/files/shop_products/'.$q['id'].'/img/m-'.$q['img'] : '/templates/images/no_img.png';
$images = $q['imgs'] ? unserialize($q['imgs']) : false;
$parameters = $q['parameters'] ? unserialize($q['parameters']) : false;
$shop_parameters = false;
if (is_array($parameters)) {
	$prms=array();
	foreach ($parameters as $k=>$v) if (@$v['display'] AND @$v['product']) $prms[]=$k;
	$shop_parameters = mysql_select("
		SELECT * FROM shop_parameters
		WHERE display=1 AND id IN('".implode("','",$prms)."')
		ORDER BY rank DESC
	",'rows_id');
}
$title = filter_var($q['name'],FILTER_SANITIZE_STRING);
?>
<h1<?=editable('shop_products|name|'.$q['id'])?>><?=$q['name']?></h1>
<div class="shop_product_text content">
	<div class="gallary">
		<?php if ($q['img']) {?><a title="<?=$title?>" onclick="return hs.expand(this, config1)" href="/files/shop_products/<?=$q['id']?>/img/<?=$q['img']?>"><?php } ?>
		<img src="<?=$img?>" alt="<?=$title?>" />
		<?php if ($q['img']) {?></a><?php } ?>
		<?php if ($images) {
			$n = 0;
			$list = '';
			foreach ($images as $k=>$v) if (@$v['display']==1) {
				$n++;
				$title2=filter_var($v['name'],FILTER_SANITIZE_STRING);
				$path = '/files/shop_products/'.$q['id'].'/imgs/'.$k.'/';
				$list.= '<li><a title="'.$title2.'" onclick="return hs.expand(this, {slideshowGroup: \'group1\',transitions: [\'expand\', \'crossfade\']})" href="'.$path.$v['file'].'"><img src="'.$path.'p-'.$v['file'].'" alt="'.$title2.'" /></a></li>';

			}
			//$list.= '</ul><ul>';
			foreach ($images as $k=>$v) if (@$v['display']==1) {
				$n++;
				$title2=filter_var($v['name'],FILTER_SANITIZE_STRING);
				$path = '/files/shop_products/'.$q['id'].'/imgs/'.$k.'/';
				$list.= '<li><a title="'.$title2.'" onclick="return hs.expand(this, {slideshowGroup: 2})" href="'.$path.$v['file'].'"><img src="'.$path.'p-'.$v['file'].'" alt="'.$title2.'" /></a></li>';

			}
			if ($n) {?>
			<div class="carousel">
				<ul style="width:<?=($n*161)?>px"><?=$list?></ul>
				<a class="next" href="#" title=""></a>
				<a class="prev" href="#" title=""></a>
			</div>
		<?php }} ?>
	</div>
	<div class="info">

		<?php if ($parameters OR $q['brand_name'] OR $q['article']) {?>
		<dl class="dl-horizontal">
			<?php if ($q['article']) {?>
			<dt><?=i18n('shop|article',true)?>:</dt>
			<dd <?=editable('shop_products|article|'.$q['id'])?>><?=$q['article']?></dd>
			<?php } ?>
			<?php if ($q['brand_name']) {?>
			<dt><?=i18n('shop|brand',true)?>:</dt><dd><?=$q['brand_name']?></dd>
			<?php } ?>
			<?php if ($parameters) foreach($parameters as $k=>$v) if ($q['p'.$k]!=0 AND isset($shop_parameters[$k])) {?>
			<dt><?$shop_parameters[$k]['name']?></dt>
			<dd><?php
				$values = $shop_parameters[$k]['values'] ? unserialize($shop_parameters[$k]['values']) : array();
				if (in_array($shop_parameters[$k]['type'],array(1,3))) @$values[$q['p'.$k]];
				elseif ($shop_parameters[$k]['type']==2) echo $q['p'.$k];
				if ($shop_parameters[$k]['units']) echo $shop_parameters[$k]['units'];
			?></dd>
			<?php } ?>
		</dl>
		<?php } ?>
		<?php if ($q['price']>0) {?>
		<div class="price">
			<span<?=editable('shop_products|price|'.$q['id'])?>><?=$q['price']?></span> <?=i18n('shop|currency',true)?>
			<?php if ($q['price2']>0){?>
			<s><span<?=editable('shop_products|price2|'.$q['id'])?>><?=$q['price2']?></span> <?=i18n('shop|currency',true)?></s>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if (isset($modules['basket']) AND $q['price']>0) {?>
		<a class="js_buy btn btn-default pull-left" data-id="<?=$q['id']?>" data-price="<?=$q['price']?>" href="#" title="<?=i18n('basket|buy')?>"><i class="icon-shopping-cart"></i> <?=i18n('basket|buy')?></a>
		<?php } ?>

		<?=html_array('common/share')?>
		<div<?=editable('shop_products|text|'.$q['id'],'editable_text','text')?>><?=$q['text']?></div>
	</div>
	<div class="clearfix"></div>
	<?=html_query('shop/review_list',"SELECT * FROM shop_reviews WHERE display=1 AND product=".$q['id']." ORDER BY date DESC",'')?>
	<?=html_array('shop/review_form',$q)?>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.shop_product_text .gallary .carousel').hover(
		function () {
			$('.shop_product_text .carousel .next, .shop_product_text .carousel .prev').show().css('display','block');
		},
		function () {
			$('.shop_product_text .carousel .next, .shop_product_text .carousel .prev').hide();
		}
	);
	$('.shop_product_text .gallary .next,.shop_product_text .gallary .prev').click(function(){
		var left = parseInt($('.shop_product_text .gallary ul').css('margin-left'));
		var width = parseInt($('.shop_product_text .gallary ul').width());
		var count = $('.shop_product_text .gallary .carousel ul li').length;
		var margin = $('.shop_product_text .gallary .carousel ul li:first-child').outerWidth(); //ширина одной фото
		//alert (width+' '+left);
		if ($(this).hasClass('next')) {
			if (width+left<margin*count) return false;
			left = left-margin;
		} else {
			if (left>=0) return false;
			left = left+margin;
		}
		$('.shop_product_text .gallary ul').animate({marginLeft:left+'px'},500);
		return false;
	});
});
</script>

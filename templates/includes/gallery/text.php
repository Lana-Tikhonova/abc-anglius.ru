<?php
if ($q['template']==1) {
	?>
<?=html_sources('return','highslide_gallery')?>
<div class="inner_container"><div class="row">
<?php
$path = 'files/gallery/'.$q['id'].'/images'; //папка от корня основной папки
$root = ROOT_DIR.$path.'/'; //папка от корня сервера
$images = $q['images'] ? unserialize($q['images']) : array();
$i=0;
foreach ($images as $k=>$v) if (@$v['display']==1) {
	$i++;
	?>
	<div class="gallery_list _list col-xs-6 col-sm-6 col-md-4">
		<a onclick="return hs.expand(this, config1 )" href="/<?=$path?>/<?=$k?>/<?=$v['file']?>" title="<?=$v['name']?>"><img src="/<?=$path?>/<?=$k?>/p-<?=$v['file']?>" alt="<?=$v['name']?>" /></a>
		<?=$v['name']?>
	</div>
	<?php
	if (fmod($i,3)==0) echo '<div class="clearfix visible-md"></div>';
	if (fmod($i,2)==0) echo '<div class="clearfix visible-xs visible-sm"></div>';
}
?>
</div></div>
<?php
} else {
	echo html_array('gallery/slider',$q);
}
?>

<?php
$basket = unserialize($q['basket']);
$page['name'] = i18n('basket|order_name').' № '.$q['id'].' '.i18n('basket|order_from').' '.date2($q['date'],'%d.%m.%Y');
?>
<div class="content pb">

<h1><?=$page['name']?></h1>

<?=i18n('basket|order_status')?>: <?=$q['ot_name']?> | <?=$q['paid']==1?i18n('order|paid',true):i18n('order|not_paid',true)?>

<div style="padding:10px 0"><?=$q['ot_text']?></div>

<table class="table basket_product_list">
<thead>
	<tr>
		<th class="id"><?=i18n('basket|product_id',true)?></th>
		<th class="name"><?=i18n('basket|product_name',true)?></th>
		<th class="price text-right"><?=i18n('basket|product_price',true)?></th>
		<th class="count text-right"><?=i18n('basket|product_count',true)?></th>
		<th class="sum text-right"><?=i18n('basket|product_cost',true)?></th>
	</tr>
</thead>
<tbody>
<?php
$i = 0;
foreach ($basket['products'] as $k=>$v) {
	$i = $i==0 ? 1 : 0;
	$sum = $v['price']*$v['count'];
?>
<tr class="tr<?=$i?>">
	<td class="id"><?=$v['id']?></td>
	<td class="name"><?=$v['name']?></td>
	<td class="price text-right"><span><?=number_format($v['price'],2,'.','')?></span><?=i18n('shop|currency')?></td>
	<td class="count text-right"><?=$v['count']?></td>
	<td class="sum text-right"><span ><?=number_format($sum,2,'.','')?></span><?=i18n('shop|currency')?></td>
</tr>
<?php } if ($basket['delivery']['type']) { ?>
<tr>
	<td colspan="4"  style="text-align:right"><?=i18n('basket|delivery_cost',true)?>
	<?php
	$delivery = mysql_select("SELECT * FROM order_deliveries WHERE id = '".intval($basket['delivery']['type'])."'",'row');
	if ($delivery) {
		echo '('.$delivery['name'].')';
	}
	?>:
	</td>
	<td class="text-right"><?=number_format($basket['delivery']['cost'],2,'.','')?><?=i18n('shop|currency')?></td>
</tr>
<?php } ?>
</tbody>
<tfoot>
	<tr>
		<td colspan="4"><?=i18n('basket|total')?>:</td>
		<td class="total text-right"><span><?=number_format($q['total'],2,'.','')?></span> <?=i18n('shop|currency')?></td>
	</tr>
</tfoot>
</table>

<h2><?=i18n('basket|profile',true)?></h2>
<dl class="dl-horizontal">
	<dt><?=i18n('profile|email',true)?>:</dt>
	<dd><?=$q['email']?></dd>
<?php
if (is_array($basket['user'])) {
	$result = mysql_query("SELECT * FROM user_fields WHERE display = 1 ORDER BY rank DESC");
	while ($f = mysql_fetch_assoc($result)) if (isset($basket['user'][$f['id']])) {
	?>
	<dt><?=$f['name']?>:</dt>
	<dd><?php
	if ($f['type']==2) {
		$values = $f['values'] ? unserialize($f['values']) : '';
		echo $values[$basket['user'][$f['id']][0]];
	}
	else echo $basket['user'][$f['id']][0];
	?></dd>
	<?php
	}
}
?>
</dl>

<?php if ($basket['text']) {?>
	<h2><?=i18n('basket|comment',true)?></h2>
	<?=str_replace ("\n",'<br />',$basket['text']);?>
<?php } ?>


<?php
//способы оплаты
if ($q['paid']==0) {
	echo html_query('order/payments',"SELECT * FROM order_payments WHERE display=1 ORDER BY rank DESC",'');
}
?>


</div>
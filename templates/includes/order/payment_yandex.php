<form action="https://money.yandex.ru/eshop.xml" method="post">
	<input name="shopId" value="<?=$config['yandex_shopId']?>" type="hidden"/>
	<input name="scid" value="<?=$config['yandex_scid']?>" type="hidden"/>
	<input name="sum" value="<?=$page['total']?>" type="hidden">
	<input name="customerNumber" value="<?=$config['yandex_customerNumber']?>" type="hidden"/>
	<input name="orderNumber" value=<?=$page['id']?>" type="hidden"/>
	<input type="submit" value="<?=i18n('order|pay')?>"/>
</form>
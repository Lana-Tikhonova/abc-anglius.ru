$(document).ready(function(){
	//валидация форм
	if ($.isFunction($.fn.validate)) {
		$('form.validate').each(function(){
			$(this).validate();
		});
	}

	//очитска урл от путстых значений
	$('form.form_clear').submit(function(){
		$(this).find('select,input').each(function(){
			if($(this).val()=='' || $(this).val()=='0-0') $(this).removeAttr('name');
		});
	});

	//мультичексбокс
	$(document).on("change",'.form_multi_checkbox .data input',function(){
		var arr = [];
		var i = 0;
		$(this).parents('.data').find('input:checked').each(function(){
			arr[i] = $(this).val();
			i++;
		});
		$(this).parents('.data').next('input').val(arr);
	});
	//min-max
	$(document).on("change",'.form_input2 input',function(){
		var min = parseInt($(this).parents('.form_input2').find('input.form_input2_1').val());
		var max = parseInt($(this).parents('.form_input2').find('input.form_input2_2').val());
		$(this).parents('.form_input2').find('input[type=hidden]').val(min+'-'+max);
	});

	//добавление товара в корзину
	$('.js_buy').click(function(){
		var basket	= $('#basket_info'),
			product	= $(this).data('id'),
			price	= $(this).data('price'),
			count	= 1,
			counter = $('.count',basket),
			total = $('.total',basket),
			basket_count = parseInt(counter.text()),
			basket_total = parseInt(total.text());
		$.getJSON('/ajax.php',{
				file:		'basket',
				action:		'add_product',
				product:	product,
				count:		count
			},function (data) {
				if (data.done){
					counter.text(data.count);
					total.text(data.total);
				} else alert(data.message);
			}
		);
		//моментальное изменение количества и цены товароы на старнице
		basket_count+= count;
		basket_total+= price * count;
		//количество знаков после запятой
		basket_total = basket_total.toFixed();
		counter.text(basket_count);
		total.text(basket_total);
		$('.full',basket).show();
		$('.empty',basket).hide();
		$('#basket_message').modal();
		return false;
	});

	//terms modal
	$('.terms,.feedhref').click( function(event){
		event.preventDefault();
		$('#overlay').fadeIn(400,
		 	function(){
				$('.modal_form') 
					.css('display', 'block')
					.animate({opacity: 1, top: '50%'}, 200);
		});
	});
	function close_modal(){
		$('.modal_form')
			.animate({opacity: 0, top: '45%'}, 200,
				function(){
					$(this).css('display', 'none');
					$('#overlay').fadeOut(400);
				}
			);
	}
	$('#modal_close, #overlay').click( function(){
		close_modal();
	});

	$('#feed .btn').click(function(event){
		event.preventDefault();
		var form=$('#feed .form');
		if(form.valid()) {
			$.post(
				'/ajax.php?file=feedback',
				{'email':$('input[name=email]').val(),'name':$('input[name=name]').val(),
				'text':$('textarea[name=text]').val(),'captcha':$('input[name=captcha]').val()},
				function(data){
					if(data!='') {
						alert(data);
						close_modal();
					}
				}
			).fail(function() {
				alert('Нет соединения!');
			});
		}
	});

	$(".upload").click(function(){
		$(this).parent(".bparent").find(".uploadfile").trigger("click");
		return false;
	});
	$(".uploadfile").change(function(){
		if($(this).prop("files")){$(this).closest("form").submit();}
	});
	$(".payonline").click(function(){
		$(this).parents(".bparent").find(".paymethods").toggle();
		return false;
	});

	//scroll
	$('a[href^="#"]').click(function(){
        	var goto = $($(this).attr('href')).offset().top;
	        $('html:not(:animated),body:not(:animated)').animate({scrollTop: goto},500);
	        return false; 
	});
});
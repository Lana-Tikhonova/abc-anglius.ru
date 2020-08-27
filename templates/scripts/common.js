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
        
        if($('.form7 form').length) {
            setSisyphusOlymp($('.form7 form'));
        } 
});

function setSisyphusOlymp(form){    
    $(form).sisyphus({
        locationBased: true,
        customKeyPrefix: 'mo_',
        timeout: 1,
        onSave: function() {
            //console.log(this);
            //this.browserStorage.set( key, value )
            var idAndName = (this.href + this.identifier + 'olymp_form_');
            var idTeachers = (this.href + this.identifier + 'olymp_form_' + 'teachers_count');
            var idAnswers = (this.href + this.identifier + 'olymp_form_' + 'answers_count');
            if(this.targets.find('.teacher').length > 1)
                this.browserStorage.set( idTeachers, this.targets.find('.teacher').length );
            else
                this.browserStorage.remove( idTeachers );

            if(this.targets.find('[id*=ansblock]').length > 1)
                this.browserStorage.set( idAnswers, this.targets.find('[id*=ansblock]').length );
            else
                this.browserStorage.remove( idAnswers );

        },
        onBeforeRestore: function() {
            var idAndName = (this.href + this.identifier + 'olymp_form_');
            var idTeachers = (this.href + this.identifier + 'olymp_form_' + 'teachers_count');
            var idAnswers = (this.href + this.identifier + 'olymp_form_' + 'answers_count');
            
            var varTeachersCount = this.browserStorage.get(idTeachers);
            if (varTeachersCount && (varTeachersCount > 1) && (this.targets.find('.teacher').length == 1) ) {
                var tmpTeacher = this.targets.find('.teacher').eq(0);
                for (i=1; i<varTeachersCount; i++) {
                    var cl = $(tmpTeacher).clone();
                    $(cl).attr('id', 'teacher'+i).attr('data-id', i).find('input').attr('name', 'fio['+i+']');
                    $(cl).find('input').after(  $('<a>').addClass('ansclose').attr('href', 'javascript:fclose('+i+')').css({'margin-left': '10px', 'padding-top':'5px'}).html( $('<img>').attr('src', '/templates/images/close.png').attr('alt', 'удалить результат').attr('title', 'удалить результат') ) );
                    $(cl).insertBefore($('#addf'));
                }
                //console.log('будем добавлять учителей');
            }
            
            var varAnswersCount = this.browserStorage.get(idAnswers);
            if (varAnswersCount && (varAnswersCount > 1) && (this.targets.find('[id*=ansblock]').length == 1) ) {
                var tmpAnswer = this.targets.find('[id*=ansblock]').eq(0);
                for (i=1; i<varAnswersCount; i++) {
                    var cl = $(tmpAnswer).clone();
                    $(cl).attr('id', 'ansblock'+i).attr('data-id',i);
                    //$(cl).find('.ansclose').attr('href', 'javascript:ansclose('+i+')');                    
                    $(cl).find('.anstbl').after( $('<a>').addClass('ansclose').attr('href', 'javascript:ansclose('+i+')').html( $('<img>').attr('src', '/templates/images/close.png').attr('alt', 'удалить результат').attr('title', 'удалить результат') )); //.append( $('<div>').addClass('clear-both') );
                    $(cl).find('input[name=answers\\[0\\]\\[fio\\]]').attr('name', 'answers['+i+'][fio]');
                    $(cl).find('select[name=answers\\[0\\]\\[test\\]]').attr('name', 'answers['+i+'][test]');
                    // ответы 
                    var inptAnswsCnt = $(cl).find('input[value=A]').length;
                    for (k=1;k<=inptAnswsCnt;k++){
                        var inptAnsws = $(cl).find('input[name=answers\\[0\\]\\['+k+'\\]]');
                        //console.log($(inptAnsws).length);
                        $.each(inptAnsws, function(key, el){
                            var idInptAnsw = i + '_' + k + '_' + $(el).val();
                            //console.log(el);
                            $(el).attr('name', 'answers['+i+']['+k+']').attr('id', idInptAnsw);
                            $(el).next('label').attr('for', idInptAnsw);
                        });                        
                    }
                    
                    
                    $(cl).appendTo($('.allanswers'));
                }
                //console.log('будем добавлять ответы');
            }            
            //console.log(this.browserStorage.get(idTeachers), this.browserStorage.get(idAnswers));
        },
        onRestore: function() {},
        onRelease: function() {
            var idAndName = (this.href + this.identifier + 'olymp_form_');
            var idTeachers = (this.href + this.identifier + 'olymp_form_' + 'teachers_count');
            var idAnswers = (this.href + this.identifier + 'olymp_form_' + 'answers_count');
            this.browserStorage.remove( idTeachers );
            this.browserStorage.remove( idAnswers );
        }           
    });
}
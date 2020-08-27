$(document).ready(function(){
    console.log('onlimp.js');
    $("body").on('change', 'input[name=csv]', function () {
        console.log('change!');
        $(this).parse({
            config: {
                complete: function (results, file) {
                    var qas = $('.qas');                    
                    $(qas).find('.qa').not('[data-i=0]').remove();                                        
                    $.each(results.data, function (i, line) {
                        var content = $("#template_qa").val(),
                            n = i + 1;
                        content = content.replace(/{i}/g, n);
                        content = content.replace(/{[\w]*}/g,"");
                        content = $(content);
                        $(content).find('input[name="qa['+n+'][q]"]').attr('value', line[0]);                        
                        $(content).find('input[name="qa['+n+'][img]"]').attr('value', line[6]?line[6]:'');                        
                        $(content).find('input[name="qa['+n+'][audio]"]').attr('value', line[7]?line[7]:'');                        
                        $(content).find('input[name="qa['+n+'][video]"]').attr('value', line[8]?line[8]:'');                                                
                        var answer = line[1];                        
                        $(content).find('select[name="qa['+n+'][a]"]').find('option[value='+answer+']').attr('selected', '');
                        
                        // answers
                        for (var k=1; k<=4;k++) {
                            var field_n = k + 1;
                            $(content).find('input[name="qa['+n+'][a'+k+']"]').attr('value', line[field_n]?line[field_n]:'');                        
                        }
                        
                        
                        $(qas).append($(content));                        
                        //console.log(decoder.decode(line[0]));
                    });
                }
            },
            complete: function () {
                console.log("All files done!");
            }
        });
    });
});

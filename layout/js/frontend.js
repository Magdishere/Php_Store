$(function() {

    
    'use strict';

    // Switch Between Login & Signup

	$('.login-page h1 span').click(function () {

		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-page form').hide();

		$('.' + $(this).data('class')).show();

	});

    // hide placeholder on form focus 

    $('[placeholder]').focus(function() {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function() {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // Trigger The Selectboxit

	$("select").selectBoxIt({

        autoWidth: false
        
    });

    //Add Asterisk in required fields

    $('input').each(function() {
        if($(this).attr('required') === 'required'){
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    //Confirmation message on button
    $('.confirm').click(function (){
        return confirm('Are you sure?');
    });

    $('.live').keyup(function(){

        $($(this).data('class')).text($(this).val());
    });


    

});

    


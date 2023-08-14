$(function() {

    
    'use strict';

    //Dashboard

    $('.toggle-info').click(function(){

        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

        if($(this).hasClass('selected')){

            $(this).html('<i class="fa fa-plus fa-lg"></i>');

        }else{

            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }


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

    //Convert Password Field to Text Field on hover
    var passField = $('.password');

	$('.show-pass').hover(function () {

		passField.attr('type', 'text');

	}, function () {

		passField.attr('type', 'password');

	});

    //Confirmation message on button
    $('.confirm').click(function (){
        return confirm('Are you sure?');
    });

    //Category View Option
    $('.cat h3').click(function(){
        $(this).next('.full-view').fadeToggle(100);
    });
    $('.option span').click(function(){
        $(this).addClass('active').siblings('span').removeClass('active');

        if($(this).data('view') === 'full'){
            $('.cat .full-view').fadeIn(100);
        }else{
            $('.cat .full-view').fadeOut(100);
        }
    });
});

/*$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
*/


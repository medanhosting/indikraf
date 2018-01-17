$(window).on('load', function() {
	// Animate loader off screen
	$(".loading").fadeOut("slow");
});

$(document).ready(function (){
	// Nav toggler
	$('#toggler').click(function() {
		$('#nav').toggleClass('pushed');
		return false;
	});
	$('#nav-close').click(function() {
		$('#nav').removeClass('pushed');
		return false;
	})
	$('#search').click(function() {
		$('.container--search').toggleClass('is-active');
		return false;
	});
	$(window).scroll(function () {
	    if ($(this).scrollTop() > 0) {
	        $('.header').addClass('header--sticky');
	    } else {
	    	$('.header').removeClass('header--sticky');
	    }
	});

	// Modal
	$('#login').click(function() {
		$('#modal_login').toggleClass('modal-active');
	});
	$('#register').click(function() {
		$('#modal_register').toggleClass('modal-active');
	});
	$('.detail').click(function(e) {
		e.preventDefault();
		$('#history_detail').toggleClass('modal-active');
		$('.modal-box__loading').fadeIn('slow');
	});
	$('.review').click(function(e) {
		e.preventDefault();
		$('#modal_review').toggleClass('modal-active');
	});
	$('.modal').click(function(event) {
		if (event.target.id == 'modal_login') {
			$('.modal').removeClass('modal-active');
		} else if (event.target.id == 'modal_register') {
			$('.modal').removeClass('modal-active');
		} else if (event.target.id == 'history_detail') {
			$('.modal').removeClass('modal-active');
			$('.modal-box__error').fadeOut();
		} else if (event.target.id == 'modal_review') {
			$('.modal').removeClass('modal-active');
		} else if (event.target.id == 'subscribe') {
			$('.modal').removeClass('modal-active');
		}
	});
	$('.btn-close').click(function() {
		$('.modal').removeClass('modal-active');
		$('.modal-box__error').fadeOut();
	});

	// FAQ accordion
	$('[accordion-target]').on('click', function (e) {
		var currentBody = $(this).attr('accordion-target');
		if ($(e.target).is('.active')) {
			$(this).removeClass('active');
			$(currentBody).slideUp().removeClass('open');
		} else {
			$(this).addClass('active');
			// Open up the hidden content panel
			$(currentBody).slideDown().addClass('open');
		}
	});
});

// Smooth State js
$(function(){
	'use strict';
	var 	$body = $('body, html'),
			$loading = $('#loadState'),
			$page = $('#main'),
			options = {
		  		prefetch: true,
		  		cacheLength: 2,
				blacklist: 'form, .no-smoothstate',
		  		onStart: {
						duration: 250, // Duration of our animation
						render: function ($container) {
						// Add your CSS animation reversing class
						$container.addClass('is-exiting');
						// Restart your animation
						smoothState.restartCSSAnimations();
						$body.scrollTop(0);
						$loading.show();
						$loading.css('width', '60%');
					}
				},
				onReady: {
					duration: 100,
					render: function ($container, $newContent) {
						// Remove your CSS animation reversing class
						$container.removeClass('is-exiting');
						// Inject the new content
						$container.html($newContent);
						$loading.css('width', '100%');
					}
				},
				onAfter: function($container, $newContent) {
					$loading.hide();
					$loading.css('width', '0');
				}
			},
			smoothState = $page.smoothState(options).data('smoothState');
});

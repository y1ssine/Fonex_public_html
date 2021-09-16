/**
 * Trusted Custom JS
 *
 * @package Trusted
 *
 * Distributed under the MIT license - http://opensource.org/licenses/MIT
 */
jQuery(document).ready(function($){
    // Defining a function to set size for page title padding if we have a very large primary menu
    function fullscreen(){

        var footerheight = parseInt( $('#colophon').height() );
        footerheight = footerheight - 1;
        jQuery('#page.trusted-sticky-footer').css({
            'padding-bottom' : footerheight + 'px'
        });

        var topbarheight = $('#top-bar').outerHeight();
        topbarheight = parseInt(topbarheight);

        if ( topbarheight > 0 ) {
            jQuery('.toggle-nav').css({
                'top' : topbarheight + 'px'
            });            
        }

        if ( ! $('#primary-menu').length ) {
            jQuery('.toggle-nav').css({
                'display' : 'none'
            });    
        }

        var mastheadHeight = $('#masthead').height();

        if ( $('.main-header.below').length ) {
            $('.main-header').css({
                'margin-top' : mastheadHeight + 'px'
            });
        } else {
            $('.main-header').css({
                'padding-top' : mastheadHeight + 'px'
            });
        }

        $('.blank-canvas-header').css({
            'min-height' : mastheadHeight + 'px'
        });

        $('.blank-canvas-header-full-width').css({
            'min-height' : mastheadHeight + 'px'
        });

        if ($('.header-title.align-left').length) {
            if( $('.main-excerpt').length || $('.term-description').length || $('.page-description').length || $('.taxonomy-description').length ) {
                $('.main-title').addClass('zero-bottom-left-radius');
                var widthMainTitle = parseInt( $('.main-title').outerWidth() );
                var widthMainExcerpt = parseInt( $('.main-excerpt p:first-child').outerWidth() );
                var widthTermDesc = parseInt( $('.term-description p:first-child').outerWidth() );
                var widthPageDesc = parseInt( $('.page-description p:first-child').outerWidth() );
                var widthTaxDesc = parseInt( $('.taxonomy-description p:first-child').outerWidth() );
                if ( widthMainTitle < widthMainExcerpt || widthMainTitle < widthTermDesc || widthMainTitle < widthPageDesc || widthMainTitle < widthTaxDesc ) {
                    $('.main-title').addClass('zero-bottom-right-radius');
                    $('.main-excerpt').removeClass('zero-top-right-radius');
                    $('.term-description').removeClass('zero-top-right-radius');
                    $('.page-description').removeClass('zero-top-right-radius');
                    $('.taxonomy-description').removeClass('zero-top-right-radius');
                } else {
                    if ( widthMainTitle == widthMainExcerpt || widthMainTitle == widthTermDesc || widthMainTitle == widthPageDesc || widthMainTitle == widthTaxDesc ) {
                        $('.main-title').addClass('zero-bottom-right-radius');
                        $('.main-excerpt').addClass('zero-top-right-radius');
                        $('.term-description').addClass('zero-top-right-radius');
                        $('.page-description').addClass('zero-top-right-radius');
                        $('.taxonomy-description').addClass('zero-top-right-radius');
                    } else {
                        $('.main-title').removeClass('zero-bottom-right-radius');
                        $('.main-excerpt').addClass('zero-top-right-radius');
                        $('.term-description').addClass('zero-top-right-radius');
                        $('.page-description').addClass('zero-top-right-radius');
                        $('.taxonomy-description').addClass('zero-top-right-radius');
                    }
                }
            }
        }

        if ($('.header-title.align-center').length) {
            if( $('.main-excerpt').length || $('.term-description').length || $('.page-description').length || $('.taxonomy-description').length ) {
                var widthMainTitle = parseInt( $('.main-title').outerWidth() );
                var widthMainExcerpt = parseInt( $('.main-excerpt p:first-child').outerWidth() );
                var widthTermDesc = parseInt( $('.term-description p:first-child').outerWidth() );
                var widthPageDesc = parseInt( $('.page-description p:first-child').outerWidth() );
                var widthTaxDesc = parseInt( $('.taxonomy-description p:first-child').outerWidth() );
                if ( widthMainTitle < widthMainExcerpt || widthMainTitle < widthTermDesc || widthMainTitle < widthPageDesc || widthMainTitle < widthTaxDesc ) {
                    $('.main-title').addClass('zero-bottom-left-radius').addClass('zero-bottom-right-radius');
                    $('.main-excerpt').removeClass('zero-top-left-radius').removeClass('zero-top-right-radius');
                    $('.term-description').removeClass('zero-top-left-radius').removeClass('zero-top-right-radius');
                    $('.page-description').removeClass('zero-top-left-radius').removeClass('zero-top-right-radius');
                    $('.taxonomy-description').removeClass('zero-top-left-radius').removeClass('zero-top-right-radius');
                } else {
                    if ( widthMainTitle == widthMainExcerpt || widthMainTitle == widthTermDesc || widthMainTitle == widthPageDesc || widthMainTitle == widthTaxDesc ) {
                        $('.main-title').addClass('zero-bottom-left-radius').addClass('zero-bottom-right-radius');
                        $('.main-excerpt').addClass('zero-top-left-radius').addClass('zero-top-right-radius');
                        $('.term-description').addClass('zero-top-left-radius').addClass('zero-top-right-radius');
                        $('.page-description').addClass('zero-top-left-radius').addClass('zero-top-right-radius');
                        $('.taxonomy-description').addClass('zero-top-left-radius').addClass('zero-top-right-radius');
                    } else {
                        $('.main-title').removeClass('zero-bottom-left-radius').removeClass('zero-bottom-right-radius');
                        $('.main-excerpt').addClass('zero-top-left-radius').addClass('zero-top-right-radius');
                        $('.term-description').addClass('zero-top-left-radius').addClass('zero-top-right-radius');
                        $('.page-description').addClass('zero-top-left-radius').addClass('zero-top-right-radius');
                        $('.taxonomy-description').addClass('zero-top-left-radius').addClass('zero-top-right-radius');
                    }
                }
            }
        }

        if ($('.header-title.align-right').length) {
            if( $('.main-excerpt').length || $('.term-description').length || $('.page-description').length || $('.taxonomy-description').length ) {
                $('.main-title').addClass('zero-bottom-right-radius');
                var widthMainTitle = parseInt( $('.main-title').outerWidth() );
                var widthMainExcerpt = parseInt( $('.main-excerpt p:first-child').outerWidth() );
                var widthTermDesc = parseInt( $('.term-description p:first-child').outerWidth() );
                var widthPageDesc = parseInt( $('.page-description p:first-child').outerWidth() );
                var widthTaxDesc = parseInt( $('.taxonomy-description p:first-child').outerWidth() );
                if ( widthMainTitle < widthMainExcerpt || widthMainTitle < widthTermDesc || widthMainTitle < widthPageDesc || widthMainTitle < widthTaxDesc ) {
                    $('.main-title').addClass('zero-bottom-left-radius');
                    $('.main-excerpt').removeClass('zero-top-left-radius');
                    $('.term-description').removeClass('zero-top-left-radius');
                    $('.page-description').removeClass('zero-top-left-radius');
                    $('.taxonomy-description').removeClass('zero-top-left-radius');
                } else {
                    if ( widthMainTitle == widthMainExcerpt || widthMainTitle == widthTermDesc || widthMainTitle == widthPageDesc || widthMainTitle == widthTaxDesc ) {
                        $('.main-title').addClass('zero-bottom-left-radius');
                        $('.main-excerpt').addClass('zero-top-left-radius');
                        $('.term-description').addClass('zero-top-left-radius');
                        $('.page-description').addClass('zero-top-left-radius');
                        $('.taxonomy-description').addClass('zero-top-left-radius');
                    } else {
                        $('.main-title').removeClass('zero-bottom-left-radius');
                        $('.main-excerpt').addClass('zero-top-left-radius');
                        $('.term-description').addClass('zero-top-left-radius');
                        $('.page-description').addClass('zero-top-left-radius');
                        $('.taxonomy-description').addClass('zero-top-left-radius');
                    }
                }
            }
        }

        var breadheight = $(".breadcrumbs .trail-items").height(); 
        if (breadheight > 32) {
            $('.breadcrumbs').addClass('overheight');
        } else {
            $('.breadcrumbs').removeClass('overheight');
        }

        $('.main-header').addClass('has-smoothing-effect');

    }
  
    fullscreen();

    // Run the function in case of window resize
    jQuery(window).resize(function() {
        fullscreen();         
    });

});


jQuery(function($){

    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
    	if( scrollTop > 0 ){
    		$('#masthead').addClass('scrolled');
    	}else{
    		$('#masthead').removeClass('scrolled');
    	}
        $('.main-header').css('transform', 'translateY(' + (scrollTop / 6) + 'px)');
    });

    // Mobile Menu
    $('#primary-menu .menu-item-has-children').prepend('<span class="sub-trigger"></span>');
    $( '.toggle-nav' ).click( function() {
        $( '#page' ).toggleClass( 'is-visible' );
        $( '#masthead' ).toggleClass( 'is-visible' );
        $( this ).toggleClass( 'is-visible' );
    });
    $( '.sub-trigger' ).click( function() {
        $( this ).toggleClass( 'is-open' );
        $( this ).siblings( '.sub-menu' ).toggle( 300 );
    });

    $('.entry-content h1, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6').each(function () {
        var h2alignment = $(this).css('text-align');
        if ( h2alignment == 'center') {
            $(this).addClass('center');
        } else if ( h2alignment == 'right') {
            $(this).addClass('right');
        }
    });

    $('#featured-post-section .featured-post').matchHeight();

    $('body')
        // Tabs
        .on('init', '.wc-tabs-wrapper, .woocommerce-tabs', function() {
            $('.wc-tab, .woocommerce-tabs .panel:not(.panel .panel)').hide();

            var $tabs = $( this ).find('.wc-tabs').first();
            $tabs.find('li:first a').click();
        } )
        .on('click', '.wc-tabs li a', function( e ) {
            e.preventDefault();
            var $tab          = $( this );
            var $tabs_wrapper = $tab.closest('.wc-tabs-wrapper, .woocommerce-tabs');
            var $tabs         = $tabs_wrapper.find('.wc-tabs');

            $tabs.find('li').removeClass('active');
            $tabs_wrapper.find('.wc-tab, .panel:not(.panel .panel)').hide();

            $tab.closest('li').addClass('active');
            $tabs_wrapper.find( $tab.attr('href') ).show();
        } )

    // Init tabs and star ratings
    $('.wc-tabs-wrapper, .woocommerce-tabs').trigger('init');


    // WooCommerce quantity buttons
    jQuery('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)').addClass('buttons_added').append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />');

    // Target quantity inputs on product pages
    jQuery('input.qty:not(.product-quantity input.qty)').each( function() {
        var min = parseFloat( jQuery( this ).attr('min') );

        if ( min && min > 0 && parseFloat( jQuery( this ).val() ) < min ) {
            jQuery( this ).val( min );
        }
    });

    jQuery( document ).on('click', '.plus, .minus', function() {

        // Get values
        var $qty        = jQuery( this ).closest('.quantity').find('.qty'),
            currentVal  = parseFloat( $qty.val() ),
            max         = parseFloat( $qty.attr('max') ),
            min         = parseFloat( $qty.attr('min') ),
            step        = $qty.attr('step');

        // Format values
        if ( ! currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
        if ( max === '' || max === 'NaN') max = '';
        if ( min === '' || min === 'NaN') min = 0;
        if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN') step = 1;

        // Change the value
        if ( jQuery( this ).is('.plus') ) {

            if ( max && ( max == currentVal || currentVal > max ) ) {
                $qty.val( max );
            } else {
                $qty.val( currentVal + parseFloat( step ) );
            }

        } else {

            if ( min && ( min == currentVal || currentVal < min ) ) {
                $qty.val( min );
            } else if ( currentVal > 0 ) {
                $qty.val( currentVal - parseFloat( step ) );
            }

        }

        // Trigger change event
        $qty.trigger('change');
    });

    $( '.top-login .top-login-dropdown input' ).focusin( function() {
        $( '.top-login .top-login-dropdown' ).addClass( 'locked' );
    }).add( '.top-login .top-login-dropdown' ).focusout( function() {
        if ( !$( '.top-login .top-login-dropdown' ).is( ':focus' ) ) {
            $( '.top-login .top-login-dropdown' ).removeClass( 'locked' );
        }
    });

    // Reveal animation
    if ( typeof WOW === 'function' ) {
        $('.main-title').addClass('reveal','animated');
        $('.main-excerpt').addClass('reveal','animated');
        $('.taxonomy-description').addClass('reveal','animated');
        $('.term-description').addClass('reveal','animated');
        $('.page-description').addClass('reveal','animated');
        $('.featured-post-wrap').addClass('reveal');

        $('.blog article').addClass('reveal','animated');
        $('.archive article').addClass('reveal','animated');
        $('.search article').addClass('reveal','animated');
        $('.archive li.product').addClass('reveal','animated');

        $('#secondary aside').addClass('reveal','animated');

        var trustedWow = new WOW({
            boxClass: 'reveal',
            animateClass: 'animated',
            offset: 150,
        });
        trustedWow.init();
    }

});
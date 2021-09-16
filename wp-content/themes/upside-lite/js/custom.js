/**
 * http://kopatheme.com
 * Copyright (c) 2015 Kopatheme
 *
 * Licensed under the GPL license:
 *  http://www.gnu.org/licenses/gpl.html
**/

/**
 *   1- Main menu
 *   2- Mobile menu
 *   3- OwlCarousel
 *   4- Accordion
 *   5- Progress Bar
 *   6- Toggle Boxes
 *   7- Match Height
 *   8- Back to top
 *   9- Toggle Search Box
 *   10- CountDown
 *   11- Masonry
 *   12- Google Map
 *   13- Spinner
 *   14- Magnific Popup
 *   15- Video wrapper
 *   16- FitVid
 *   17- Validate form

 
 *-----------------------------------------------------------------
 **/
 
"use strict";

jQuery(document).ready(function() {

    /* =========================================================
    1. Menu
    ============================================================ */    
    // Main menu
    jQuery('#main-menu').superfish({
        delay: 100,
        speed: 'fast',
        cssArrows: true,
        pathClass:     'overideThisToUse',
        animation:   {opacity:'show',height:'show'}
    });

    // Top menu
    jQuery('#top-menu').superfish({
        delay: 100,
        speed: 'fast',
        cssArrows: false,
        animation:   {opacity:'show',height:'show'}
    });


    /* =========================================================
    2. Mobile Menu
    ============================================================ */
    // Main menu
    jQuery(".main-menu-mobile").navgoco({ accordion: true });
    jQuery(".main-nav-mobile > .pull").on('click', function () {
        jQuery(".main-menu-mobile").slideToggle("slow");
    });
    jQuery(".caret").removeClass("caret");

    // Top menu
    jQuery(".top-main-menu-mobile").navgoco({ accordion: true });
    jQuery(".top-main-nav-mobile > .pull").on('click', function () {
        jQuery(".top-main-menu-mobile").slideToggle("slow");
    });
    jQuery(".caret").removeClass("caret");


    /* =========================================================
    3. Owl Carousel
    ============================================================ */
    if ( jQuery(".home-slider-1").length ) {
        jQuery(".home-slider-1").each(function(){
            var $this = jQuery(this);
            var owl1 = $this.find(".owl-carousel-1");
            var owl1_speed = parseInt(owl1.data('slide-speed'));
            var owl1_auto = owl1.data('slide-auto');
            if ( 'no' == owl1_auto ) {
                owl1_auto = false;
            } else {
                owl1_auto = true;
            }
            if ( ! owl1_speed ) {
                owl1_speed = 600;
            }
            owl1.owlCarousel({
                singleItem: true,
                pagination: true,
                slideSpeed: owl1_speed,
                navigationText: false,
                navigation: true,
                autoPlay: owl1_auto,
                stopOnHover: true,
                afterInit: function(){
                    $this.find('.loading').hide();
                }
            });

        });
    }

    if (jQuery('.owl-carousel-2').length ) {
        jQuery('.owl-carousel-2').each(function(){
            var $this = jQuery(this);

            var sync1 = $this.data('id');

            jQuery('#'+sync1).owlCarousel({
                singleItem : true,
                slideSpeed : 1000,
                navigation: true,
                pagination:false,
                navigationText: false,
                responsiveRefreshRate : 200,
                afterInit: function(){
                    jQuery(".home-slider-2 .loading").hide();
                }
            });

        });
    };

    if ( jQuery('.owl-carousel-3').length ) {
        jQuery('.owl-carousel-3').each(function() {
            var $this = jQuery(this);
            var owl3_speed = parseInt($this.data('slide-speed'));
            var owl3_auto = $this.data('slide-auto');
            if ( 'no' == owl3_auto ) {
                owl3_auto = false;
            } else {
                owl3_auto = true;
            }
            if ( ! owl3_speed ) {
                owl3_speed = 600;
            }
            $this.owlCarousel({
                singleItem: true,
                pagination: true,
                slideSpeed: owl3_speed,
                navigationText: false,
                navigation: false,
                autoPlay: owl3_auto,
                stopOnHover: true

            });

        });
    };

    if (jQuery('.owl-carousel-4').length) {
        var owl4 = jQuery(".owl-carousel-4");
        owl4.owlCarousel({
            items: 5,
            pagination: true,
            slideSpeed: 600,
            navigationText: false,
            navigation: false,
            autoPlay: false
        });
    };

    if (jQuery('.owl-carousel-5').length) {
        var owl5 = jQuery(".owl-carousel-5");
        owl5.owlCarousel({
            items: 4,
            pagination: true,
            slideSpeed: 600,
            navigationText: false,
            navigation: true,
            autoPlay: false
        });
    };

    if (jQuery('.owl-carousel-6').length) {
        var owl6 = jQuery(".owl-carousel-6");
        owl6.owlCarousel({
            singleItem: true,
            pagination: true,
            slideSpeed: 600,
            navigationText: false,
            navigation: true,
            autoPlay: true,
            stopOnHover: true
        });
    };

    if (jQuery('.owl-carousel-7').length) {
        jQuery(".owl-carousel-7").each(function(){
            var owl7 = jQuery(this);
            var owl7_auto = owl7.data('auto');
            var owl7_speed = owl7.data('speed');
            owl7_speed = parseInt(owl7_speed);
            if ( typeof owl7_speed == 'undefined' || ! owl7_speed ) {
                owl7_speed = 600;
            }
            if ( 1 == owl7_auto ) {
                owl7_auto = true;
            } else {
                owl7_auto = false;
            }
            owl7.owlCarousel({
                singleItem: true,
                pagination: true,
                slideSpeed: owl7_speed,
                navigationText: false,
                navigation: false,
                autoPlay: owl7_auto,
                stopOnHover:true
            });
        });
    };

    if (jQuery('.owl-carousel-8').length) {
        jQuery('.owl-carousel-8').each(function(){
            var owl8 = jQuery(this);
            var owl8_speed = owl8.attr('data-speed');
            var owl8_auto = owl8.attr('data-auto');
            if ( 'yes' ==owl8_auto ) {
                owl8_auto = true;
            } else {
                owl8_auto = false;
            }
            owl8.owlCarousel({
                singleItem: true,
                pagination: false,
                slideSpeed: owl8_speed,
                navigationText: false,
                navigation: true,
                autoPlay: owl8_auto
            });
        });
    };

    if (jQuery('.owl-carousel-9').length) {
        jQuery('.owl-carousel-9').each(function(){
            var owl9 = jQuery(this);
            var ow9_auto = owl9.attr('data-auto');
            if ( 'yes' == ow9_auto ) {
                ow9_auto = true;
            } else {
                ow9_auto = false;
            }
            var ow9_speed = owl9.attr('data-speed');
            ow9_speed = parseInt(ow9_speed);
            if ( ! ow9_speed ) {
                ow9_speed = 600;
            }
            owl9.owlCarousel({
                singleItem: true,
                pagination: true,
                slideSpeed: ow9_speed,
                navigationText: false,
                navigation: false,
                autoPlay: ow9_auto,
                stopOnHover:true
            });
        });
    };

    if (jQuery('.owl-carousel-10').length) {
        var owl10 = jQuery(".owl-carousel-10");
        owl10.owlCarousel({
            items: 4,
            pagination: true,
            slideSpeed: 600,
            navigationText: false,
            navigation: true,
            autoPlay: false
        });
    };


    /* =========================================================
    4. Accordion
    ============================================================ */

    var panel_titles = jQuery('.kopa-accordion .panel-title a');
    panel_titles.addClass("collapsed");
    jQuery('.panel-heading.active').find(panel_titles).removeClass("collapsed");
    panel_titles.on('click', function(){
        jQuery(this).closest('.kopa-accordion').find('.panel-heading').removeClass('active');
        var pn_heading = jQuery(this).parents('.panel-heading');
        if (jQuery(this).hasClass('collapsed')) {
            pn_heading.addClass('active');
        } else {
            pn_heading.removeClass('active');
        }
    });


    /* =========================================================
    5. Progress bar
    ============================================================ */
    if (jQuery('.pro-bar-container').length) {
        jQuery(document).ready(function() {
            animateProgressBar();
        });

        jQuery(window).resize(function() {
            animateProgressBar();
        });

        jQuery(window).scroll(function() {
            animateProgressBar();
            if (jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height()){
                animateProgressBar();
            }
        });    
    }

    /* =========================================================
    6. Toggle Boxes
    ============================================================ */
    jQuery('.toggle-view li').on('click', function (event) {
        var text = jQuery(this).children('.kopa-panel');
        var icon = jQuery(this).children('span');

        if (text.is(':hidden')) {
            jQuery(this).addClass('active');
            text.slideDown('300');
            kopa_toggle_click(icon, 'fa-plus', 'fa-minus');
        } else {
            jQuery(this).removeClass('active');
            text.slideUp('300');
            kopa_toggle_click(icon, 'fa-minus', 'fa-plus');
        }
    });

    /* ============================================
    7. Match height
    =============================================== */
    if (jQuery('#bottom-sidebar').length) {
        var post_1 = jQuery('#bottom-sidebar').find(".row");
        
        post_1.each(function() {
            jQuery(this).children('div').matchHeight();
        });
    };

    if ( jQuery('.upside-match-height').length ) {
        jQuery('.upside-match-height').each(function(){
            var $this = jQuery(this);
            var post_2 = $this.find(".vc_column_container");
            post_2.matchHeight();
        });
    };

    if ( jQuery('.product-match-item').length ) {
        jQuery('.product-match-item').matchHeight();
    };

    if ( jQuery('.kopa-nothumb-widget article').length ) {
        jQuery('.kopa-nothumb-widget article').matchHeight();    
    };

    if ( jQuery('.single-related-match-item').length ) {
        jQuery('.single-related-match-item').matchHeight();
    };

    if (jQuery('.bottom-section').length) {
        var post_3 = jQuery('.bottom-section').find(".row");
        post_3.each(function() {
            jQuery(this).children('div').matchHeight();
        });
    };

    if (jQuery('.kopa-demo-widget').length) {
        var post_4 = jQuery('.kopa-demo-widget').find("ul.row");

        post_4.each(function() {
            jQuery(this).children('li').matchHeight();
        });
    };

    if (jQuery('.kopa-plugin-widget').length) {
        var post_5 = jQuery('.kopa-plugin-widget').find("ul.row");

        post_5.each(function() {
            jQuery(this).children('li').matchHeight();
        });
    };


    if (jQuery('.kopa-article-list-4-widget').length) {
        var post_6 = jQuery('.kopa-article-list-4-widget').find("ul.clearfix");

        post_6.each(function() {
            jQuery(this).children('li').matchHeight();
        });        
    };

    if ( jQuery('.table-4col').length ) {
        jQuery('.table-4col').each(function(){
            var item_column = jQuery(this).find(".pricing-column .pricing-column-inner");
            item_column.matchHeight();
        });
    }


    /* =========================================================
    8. Back to top
    ============================================================ */    
    jQuery("#back-top").hide();
    
    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() > 200) {
            jQuery('#back-top').fadeIn();
        } else {
            jQuery('#back-top').fadeOut();
        }
    });

    jQuery('#back-top a').on('click', function () {
        jQuery('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });    



    /* =========================================================
    9. Toggle Search Box
    ============================================================ */
    jQuery( ".kopa-search-box" ).on({
        click: function() {
            var $this = jQuery(this);
            $this.find('.toggle-search-box').addClass('search-expand');
            $this.find('.toggle-search-box i').addClass('fa-close');
            $this.find('.search-form').addClass('block');
        }
    });

    jQuery( "body" ).on({
        click: function(event) {
            if (!jQuery(event.target).closest('.kopa-search-box').length) {
                var $this = jQuery(this);
                $this.find('.toggle-search-box').removeClass('search-expand');
                $this.find('.toggle-search-box i').removeClass('fa-close');
                $this.find('.search-form').removeClass('block');
            };
        }
    });

    /* ============================================
    10. Countdown
    =============================================== */

    /* ============================================
    11. Masonry
    =============================================== */
    if (jQuery('.kopa-masonry-list-1-widget').length) {

        jQuery('.kopa-masonry-list-1-widget .masonry-container .container-masonry').imagesLoaded(function() {            
            var options = {
                autoResize: true,
                container: jQuery('.kopa-masonry-list-1-widget .masonry-container .container-masonry'),
                offset: -1,
                fillEmptySpace: true
            };
            
            var handler = jQuery('.masonry-container .item'), filters = jQuery('.filters li');
            
            handler.wookmark(options);

            var onClickFilter = function(event) {
                var item = jQuery(event.currentTarget),
                    activeFilters = [];
                if (!item.hasClass('active')) {
                    filters.removeClass('active');
                }
                item.toggleClass('active');

                if (item.hasClass('active')) {
                    activeFilters.push(item.data('filter'));
                }
                handler.wookmarkInstance.filter(activeFilters);
            }
            
            filters.on('click', onClickFilter);
        });
    };


    if (jQuery('.kopa-portfolio-list-1-widget').length) {

        var tiles   = jQuery('.kopa-portfolio-list-1-widget .portfolio-list-item');
        var handler = jQuery('.kopa-portfolio-list-1-widget .portfolio-list-item li.por-item1');
        var filters = jQuery('.kopa-portfolio-list-1-widget .filters-options li');

        var options = {
            autoResize: true,
            container: jQuery('.kopa-portfolio-list-1-widget .portfolio-container'),
            offset: 0,
            fillEmptySpace: true
        };

        jQuery('.kopa-portfolio-list-1-widget .portfolio-list-item').imagesLoaded(function() {

            // Call the layout function.
            handler.wookmark(options);

            /**
             * When a filter is clicked, toggle it's active state and refresh.
             */
            var onClickFilter = function(event) {
              var item = jQuery(event.currentTarget),
                  activeFilters = [];

              if (!item.hasClass('active')) {
                filters.removeClass('active');
              }
              item.toggleClass('active');

              // Filter by the currently selected filter
              if (item.hasClass('active')) {
                activeFilters.push(item.data('filter'));
              }

              handler.wookmarkInstance.filter(activeFilters);
            }

            // Capture filter click events.
            filters.on('click', onClickFilter);

        });

        var button_loadmore_portfolio = jQuery('.kopa-portfolio-list-1-widget .load-more');

        if (button_loadmore_portfolio.length) {
            button_loadmore_portfolio.on('click', function() {
                var url = jQuery(this).data('url');
                var page = parseInt(jQuery(this).data('paged'));
                jQuery.ajax({
                    type: 'POST',
                    url: url + page,
                    dataType: 'html',
                    data: {},
                    beforeSend: function() {
                        button_loadmore_portfolio.addClass('in-process');
                        button_loadmore_portfolio.find('.fa-spinner').addClass('fa-spin');
                        button_loadmore_portfolio.find('.fa-spinner').show();
                    },
                    success: function(data) {
                        if ( data != undefined ) {
                            var newItems = jQuery(data).find('.por-item1');
                            if ( newItems.length ) {
                                newItems.imagesLoaded(function() {
                                    tiles.append(newItems);
                                    handler = jQuery('.kopa-portfolio-list-1-widget .portfolio-list-item li');
                                    filters = jQuery('.kopa-portfolio-list-1-widget .filters-options li');
                                    handler.wookmark(options);
                                });
                                button_loadmore_portfolio.data('paged', page + 1);
                                button_loadmore_portfolio.removeClass('in-process');
                            } else {
                                button_loadmore_portfolio.parent().first().remove();
                            }
                        } else {
                            button_loadmore_portfolio.parent().first().remove();
                        }
                    },
                    complete: function() {
                        button_loadmore_portfolio.find('.fa-spinner').removeClass('fa-spin');
                        button_loadmore_portfolio.find('.fa-spinner').hide();
                    },
                    error: function() {
                        button_loadmore_portfolio.parent().first().remove();
                    }
                });
                
            });
        }
    };

    if (jQuery('.kopa-portfolio-list-2-widget').length) {
        var handler = jQuery('.kopa-portfolio-list-2-widget .portfolio-list-item li.por-item1');
        var filters = jQuery('.kopa-portfolio-list-2-widget .filters-options li');

        var options = {
            autoResize: true,
            container: jQuery('.kopa-portfolio-list-2-widget .portfolio-container'),
            offset: 0,
            fillEmptySpace: true
        };

        jQuery('.kopa-portfolio-list-2-widget .portfolio-list-item').imagesLoaded(function() {            
            handler.wookmark(options);
            
            var onClickFilter = function(event) {
                var item = jQuery(event.currentTarget),
                    activeFilters = [];

                if (!item.hasClass('active')) {
                    filters.removeClass('active');
                }
                item.toggleClass('active');
                
                if (item.hasClass('active')) {
                    activeFilters.push(item.data('filter'));
                }

                handler.wookmarkInstance.filter(activeFilters);
            }

            filters.click(onClickFilter);
        });
    };

    if ( jQuery( 'body' ).hasClass( "tax-course-category" ) && jQuery('.kopa-masonry-list-2-widget .masonry-list-wrapper').length ) { 
        var $masonry1 = jQuery('#course-grid-four-col .masonry-list-wrapper > ul');
        imagesLoaded($masonry1, function () {
            $masonry1.masonry({
                columnWidth: 1,
                itemSelector: '.masonry-item'
            });
            $masonry1.masonry('bindResize')
        });

        if ( jQuery('#btn-more-gfc').length ) {
            jQuery('#btn-more-gfc').on('click', function() {
                var $this = jQuery(this);
                var url = $this.data('url');
                var page = parseInt($this.data('paged'));

                if( ! $this.hasClass('in-process') ){
                    jQuery.ajax({
                        type: 'POST',
                        url: url + page,
                        dataType: 'html',
                        data: {},
                        beforeSend: function() {
                            $this.addClass('in-process');
                            $this.find('.fa-spinner').addClass('fa-spin');
                            $this.find('.fa-spinner').show();
                        },
                        success: function(data) {

                            if ( data != undefined ) {
                                var uc_newItems = jQuery(data).find('#course-grid-four-col .up_source_grid_fcol > li');
                                if ( uc_newItems.length ) {
                                    jQuery('#course-grid-four-col .up_source_grid_fcol').append( uc_newItems );
                                    $this.data('paged', page + 1);
                                    $this.removeClass('in-process');
                                }
                            } else {
                                $this.parent().first().remove();
                            }
                        },
                        complete: function() {
                            $this.removeClass('in-process');
                            $this.find('.fa-spinner').removeClass('fa-spin');
                            $this.find('.fa-spinner').hide();
                        },
                        error: function() {
                            $this.parent().first().remove();
                        }
                    });
                }

            } );
        }    
    }

    var $masonry1 = jQuery('.kopa-masonry-list-3-widget .masonry-list-wrapper > ul');
    imagesLoaded($masonry1, function () {
        $masonry1.masonry({
            columnWidth: 1,
            itemSelector: '.masonry-item'
        });
        $masonry1.masonry('bindResize')
    });

    var $masonry1 = jQuery('.kopa-document-widget .masonry-list-wrapper > ul');
    imagesLoaded($masonry1, function () {
        $masonry1.masonry({
            columnWidth: 1,
            itemSelector: '.masonry-item'
        });
        $masonry1.masonry('bindResize')
    });    

    var $masonry2 = jQuery('.kopa-masonry-1-widget .masonry-list-wrapper > ul');
    imagesLoaded($masonry2, function () {
        $masonry2.masonry({
            columnWidth: 1,
            itemSelector: '.masonry-item'
        });
        $masonry2.masonry('bindResize');
    });

    /* =========================================================
    12. Google Map
    ============================================================ */
    var map;

    if (jQuery('.kopa-map').length) {
        var id_map = jQuery('.kopa-map').attr('id');
        var lat = parseFloat(jQuery('.kopa-map').attr('data-latitude'));
        var lng = parseFloat(jQuery('.kopa-map').attr('data-longitude'));
        var place = jQuery('.kopa-map').attr('data-place');

        map = new GMaps({
            el: '#'+id_map,
            lat: lat,
            lng: lng,
            scrollwheel: false,
            zoomControl : true,
            zoomControlOpt: {
                style : 'SMALL',
                position: 'TOP_LEFT'
            },
            panControl : false,
            streetViewControl : false,
            mapTypeControl: false,
            overviewMapControl: false
        });

        map.addMarker({
            lat: lat,
            lng: lng,
            title: place
        });
    };

    /* =========================================================
    13. Spinner
    ============================================================ */
    var spinner = jQuery( ".spinner" ).spinner();
    jQuery( "#disable" ).on('click', function() {
        if ( spinner.spinner( "option", "disabled" ) ) {
            spinner.spinner( "enable" );
        } else {
            spinner.spinner( "disable" );
        }
    });

    /* =========================================================
    14. Magnific Popup
    ============================================================ */
    if (jQuery('.kopa-portfolio-list-1-widget').length) {
        jQuery('.kopa-portfolio-list-1-widget .portfolio-list-item').magnificPopup({
            delegate: '.popup-icon',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1]
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
            }
        });
    };

    if (jQuery('.kopa-portfolio-list-2-widget').length) {
        jQuery('.kopa-portfolio-list-2-widget .portfolio-list-item').magnificPopup({
            delegate: '.popup-icon',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1]
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
            }
        });
    };

    if (jQuery('.owl-carousel-10').length) {
        jQuery('.owl-carousel-10 .owl-wrapper').magnificPopup({
            delegate: '.popup-icon',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1]
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
            }
        });
    };


    /* =========================================================
    15. Video wrapper
    ============================================================ */
    if (jQuery(".video-wrapper").length) {
        jQuery(".video-wrapper").fitVids();    
    };

    /* =========================================================
    16. Fitvid
    ============================================================ */
    jQuery("body").fitVids();

    /* =========================================================
    17. Validate form
    ============================================================ */
    if (jQuery('.comment-form,.contact-form').length) {

        jQuery('.comment-form,.contact-form').validate({            
            rules: {
                author: {
                    required: true,
                    minlength: 10
                },
                email: {
                    required: true,
                    email: true
                },
                comment: {
                    required: true,
                    minlength: 10
                }
            },                
            messages: {
                author: {
                    required: kopa_variable.validate.name.REQUIRED,
                    minlength: jQuery.format(kopa_variable.validate.name.MINLENGTH)
                },
                email: {
                    required: kopa_variable.validate.email.REQUIRED,
                    email: kopa_variable.validate.email.EMAIL
                },
                comment: {
                    required: kopa_variable.validate.message.REQUIRED,
                    minlength: jQuery.format(kopa_variable.validate.message.MINLENGTH)
                }
            }
        });
        
    }

    /* Change image of widget tagline */
    if ( jQuery('.kopa-area-16').length ) {
        var $this = jQuery(this);
        var tagline_img = $this.data('img');
        if ( '' != tagline_img ) {
            $this.css('background-image', tagline_img);
        }
    }


    /* Banner before footer */
    if ( jQuery('.kopa-area-parallax').length ) {
        jQuery('.kopa-area-parallax').each(function(){
            var $this = jQuery(this);
            if ( jQuery('.hide-banner').length ) {
                var $hide_banner = $this.find('.hide-banner');
                var hide_banner_url = $hide_banner.data('banner');
                $this.css('background-image','url('+hide_banner_url+')');
            }
        });
    }

    if ( jQuery('#up-single-content-wrapper').length ) {
        var up_temp_content = jQuery('#up-single-content-wrapper');
        if ( jQuery('#up-shop-related').length ) {
            jQuery('#up-shop-related').html(up_temp_content.html());
            up_temp_content.remove();
        }
    }

    /* change color of social widget square*/
    if ( jQuery('.social-item-quare').length ) {
        jQuery('.social-item-quare').each(function(){
            var $this = jQuery(this);
            var data_color = $this.attr('data-color');
            if ( '' != data_color ) {
                $this.on({
                    mouseenter: function() {
                        jQuery(this).css({ color: data_color, background : '#fff'});
                    }, mouseleave: function() {
                        jQuery(this).css({ color: '#fff', background : data_color });
                    }
                });
            }
        });
    }

    /* Event load more */
    if ( jQuery('#btn-more-event').length ) {
        jQuery('#btn-more-event').on('click', function() {
            var $this = jQuery(this);
            var url = $this.data('url');
            var page = parseInt($this.data('paged'));

            if( ! $this.hasClass('in-process') ){
                jQuery.ajax({
                    type: 'POST',
                    url: url + page,
                    dataType: 'html',
                    data: {},
                    beforeSend: function() {
                        $this.addClass('in-process');
                        $this.find('.fa-spinner').addClass('fa-spin');
                        $this.find('.fa-spinner').show();
                    },
                    success: function(data) {
                        if ( data != undefined ) {
                            var uc_newItems = jQuery(data).find('.kopa-event-list-2-widget > article');
                            if ( uc_newItems.length ) {
                                jQuery('.kopa-event-list-2-widget').append( uc_newItems );
                                $this.data('paged', page + 1);
                                $this.removeClass('in-process');
                            }
                        } else {
                            $this.remove();
                        }
                    },
                    complete: function() {
                        $this.find('.fa-spinner').removeClass('fa-spin');
                        $this.find('.fa-spinner').hide();
                    },
                    error: function() {
                        $this.remove();
                    }
                });
            }

        } );
    }

    if ( jQuery('#btn-more-event-2col').length ) {
        jQuery('#btn-more-event-2col').on('click', function() {
            var $this = jQuery(this);
            var url = $this.data('url');
            var page = parseInt($this.data('paged'));

            if( ! $this.hasClass('in-process') ){
                jQuery.ajax({
                    type: 'POST',
                    url: url + page,
                    dataType: 'html',
                    data: {},
                    beforeSend: function() {
                        $this.addClass('in-process');
                        $this.find('.fa-spinner').addClass('fa-spin');
                        $this.find('.fa-spinner').show();
                    },
                    success: function(data) {
                        if ( data != undefined ) {
                            var uc_newItems = jQuery(data).find('.kopa-event-list-1-widget > .row');
                            if ( uc_newItems.length ) {
                                jQuery('.kopa-event-list-1-widget').append( uc_newItems );
                                $this.data('paged', page + 1);
                                $this.removeClass('in-process');
                            }
                        } else {
                            $this.remove();
                        }
                    },
                    complete: function() {
                        $this.find('.fa-spinner').removeClass('fa-spin');
                        $this.find('.fa-spinner').hide();
                    },
                    error: function() {
                        $this.remove();
                    }
                });
            }

        } );
    }

    /* =========================================================
     18. smooth scrolling
     ============================================================ */
    jQuery('a.it-scroll-down[href*=#]:not([href=#])').on('click', function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = jQuery(this.hash);
            target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                jQuery('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });

    jQuery('.upside-button-trial[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = jQuery(this.hash);
            target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                jQuery('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });

    /* =========================================================
     19. Scroll Menu
     ============================================================ */

    var anchor = 0;
    if (jQuery('.kopa-header-3').length > 0) {
        jQuery(window).scroll(function() {

            var scrollPos = jQuery(window).scrollTop();

            if (scrollPos == anchor) {
                jQuery('.kopa-header-3 #kopa-header-top').removeClass('bg-none');
            } else {
                jQuery('.kopa-header-3 #kopa-header-top').addClass('bg-none');
            }
        });
    }

    /* =========================================================
     20. Landing page
     ============================================================ */
    if ( jQuery('.home.page .kopa-article-list-4-widget').length ) {
        jQuery('.home.page .kopa-article-list-4-widget .entry-title a').each(function(){
            var child = jQuery(this).text();
            jQuery(this).replaceWith(child);
        });

        jQuery('.home.page .kopa-article-list-4-widget .entry-thumb a').each(function(){
            var child_img = jQuery(this).find('img');
            jQuery(this).replaceWith(child_img);
        });
    }

    /** overlay color for row in vc */
    if ( jQuery('.upside_overlay').length ) {
        jQuery('.upside_overlay').each(function(){
            var $up_overlay_this = jQuery(this);
            var up_element_id = $up_overlay_this.attr('id');
            var up_overlay_color = $up_overlay_this.data('vc-up-overlay-color');
            var up_overlay_color_before = $up_overlay_this.data('vc-up-overlay-color-before');
            if (typeof(up_element_id) != "undefined") {
                jQuery('#'+up_element_id).parents().css('position', 'relative');
                if (typeof(up_overlay_color_before) != "undefined") {
                    jQuery('head').append('<style type="text/css">#'+up_element_id+'::before{content: "";position: absolute;top: 0;left: 0;width: 100%;height: 100%;background-color:'+up_overlay_color_before+';}</style>');
                }
                if (typeof(up_overlay_color) != "undefined") {
                    jQuery('head').append('<style type="text/css">#'+up_element_id+'{position: absolute!important;top: 0;left: 0;width: 100%;height: 100%;background-color:'+up_overlay_color+';}</style>');
                }
            }
        });
    }
});

function animateProgressBar() {
    jQuery('.pro-bar').each(function(i, elem) {
        var elem = jQuery(this),
            percent = elem.attr('data-pro-bar-percent'),
            delay = elem.attr('data-pro-bar-delay');

        if (!elem.hasClass('animated'))
            elem.css({ 'width' : '0%' });

        if (elem.visible(true)) {
            setTimeout(function() {
                elem.animate({ 'width' : percent + '%' }, 2000, 'easeInOutExpo').addClass('animated');
            }, delay);
        } 
    });
}

function kopa_toggle_click(obj, remove_class, new_class) {
    obj.removeClass(remove_class);
    obj.addClass(new_class);
}
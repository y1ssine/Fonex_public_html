<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="kopa-page-header">
<div id="kopa-header-top">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div id="logo-image" class="pull-left">

                    <?php if ( has_custom_logo() ) : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
                            <?php the_custom_logo(); ?>
                        </a>
                    <?php else: ?>
                        <?php
                            if ( is_home() || is_front_page() ) {
                                echo '<h1 class="site-title">';
                            } else {
                                echo '<div class="site-title">';
                            }
                        ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name'));?>"><?php echo esc_attr(get_bloginfo('name'), 'display');?></a>
                        <?php
                        if ( is_home() || is_front_page() ) {
                            echo '</h1>';
                        } else {
                            echo '</div>';
                        }
                        ?>
                        <p><?php echo esc_attr(get_bloginfo('description', 'display')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- col-md-3 -->

            <div class="col-md-9 col-sm-9 col-xs-12">

                <nav id="top-nav" class="pull-right clearfix">
                    <?php
                    if ( has_nav_menu('top-nav') ) {
                        #TOP MENU
                        $upside_lite_top_nav_args = array(
                            'theme_location' => 'top-nav',
                            'container' => '',
                            'container_id' => '',
                            'container_class' => '',
                            'menu_id' => 'top-menu',
                            'menu_class' => 'clearfix'
                        );
                        if ( class_exists('Upside_Lite_Toolkit_Walker_Icon_Menu') ) {
                            $upside_lite_top_nav_args['walker'] = new Upside_Lite_Toolkit_Walker_Icon_Menu();
                        }

                        wp_nav_menu( $upside_lite_top_nav_args );

                        #TOP MENU MOBILE
                        $upside_lite_top_nav_mobile_args = array(
                            'theme_location' => 'top-nav',
                            'container' => '',
                            'container_id' => '',
                            'container_class' => '',
                            'menu_id' => 'top-menu-mobile',
                            'menu_class' => 'top-main-menu-mobile clearfix'
                        );

                        echo '<nav class="top-main-nav-mobile clearfix">';
                            echo '<a class="pull"><span class="fa fa-align-justify"></span></a>';
                            wp_nav_menu( $upside_lite_top_nav_mobile_args );
                        echo '</nav>';

                    }
                    $upside_lite_search = esc_attr( get_theme_mod('header-enable-search', '1') );
                    if ( 1 == $upside_lite_search ) :
                        $upside_lite_search_class = 'toggle-search-box';
                        if ( class_exists('Upside_Lite_Toolkit_Walker_Icon_Menu') ) {
                            $upside_lite_search_class .= ' search-have-icon';
                        }
                    ?>
                        <div class="kopa-search-box">
                            <a href="#" class="<?php echo esc_attr($upside_lite_search_class);?>">
                                <?php if ( class_exists('Upside_Lite_Toolkit_Walker_Icon_Menu') ) : ?>
                                    <i class="fa fa-search"></i>
                                <?php endif; ?>
                                <span><?php esc_html_e('search', 'upside-lite'); ?></span></a>
                            <form method="get" class="search-form clearfix" action="<?php echo esc_url(home_url('/')); ?>">
                                <input type="text" class="search-text" name="s" placeholder="<?php esc_attr_e('Search...', 'upside-lite'); ?>">
                                <button type="submit" class="search-submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    <?php endif; ?>
                </nav>
                <!-- top-nav -->
            </div>
            <!-- col-md-6 -->
        </div>
        <!-- row -->
    </div>
    <!-- container -->
</div>
<!-- kopa-header-top -->
<div id="kopa-header-bottom">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12 col-sx-12">
<?php if ( has_nav_menu('main-nav') || has_nav_menu('mobile-nav') ) : ?>
<nav id="main-nav">
    <?php
    #MAIN MENU
    if ( has_nav_menu('main-nav') ) {
        $upside_lite_main_nav_args = array(
            'theme_location' => 'main-nav',
            'container' => '',
            'container_id' => '',
            'container_class' => 'main-nav',
            'menu_id' => 'main-menu',
            'menu_class' => 'clearfix'
        );
        if ( class_exists('Upside_Lite_Toolkit_Walker_Main_Menu') ) {
            $upside_lite_main_nav_args['walker'] = new Upside_Lite_Toolkit_Walker_Main_Menu();
        }
        wp_nav_menu( $upside_lite_main_nav_args );
    }

    #MOBILE MENU
    if ( has_nav_menu('mobile-nav') ) : ?>
        <nav class="main-nav-mobile clearfix">
            <a class="pull"><?php esc_html_e('Main Menu', 'upside-lite'); ?></a>
            <?php
                $upside_lite_mobile_nav_args = array(
                    'theme_location' => 'mobile-nav',
                    'container' => '',
                    'container_id' => '',
                    'container_class' => 'main-nav',
                    'menu_id' => 'mobile-menu',
                    'menu_class' => 'main-menu-mobile clearfix"'
                );
                wp_nav_menu( $upside_lite_mobile_nav_args );
            ?>
        </nav>
    <?php endif;?>
</nav>
<!-- main-nav -->
<?php endif;?>

<div class="mobile-search-box pull-right clearfix">
    <form action="<?php echo esc_url(trailingslashit(home_url('/'))); ?>" class="mobile-search-form clearfix" id="mobile-search-form" method="get">
        <input type="text" onBlur="if (this.value == '')
                                    this.value = this.defaultValue;" onFocus="if (this.value == this.defaultValue)
                                    this.value = '';" value="<?php esc_attr_e('Search Site...', 'upside-lite'); ?>" name="s" class="search-text">
        <button type="submit" class="search-submit"><i class="fa fa-search"></i></button>
    </form><!-- mobile-search-form -->
</div><!--end:mobile-search-box-->
</div>
<!-- col-md-12 -->
</div>
<!-- row -->
</div>
<!-- container -->
</div>
<!-- kopa-header-bottom -->
</div>
<!-- kopa-page-header -->
<div id="main-content">
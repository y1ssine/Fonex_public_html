<div id="main-content">
    <header class="page-header">
        <div class="mask-pattern"></div>
        <div class="mask"></div>
        <div class="page-header-bg page-header-bg-1"></div>
        <div class="page-header-inner">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php get_template_part( 'template/module/title' ); ?>
                    </div>
                    <!-- col-md-12 -->
                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </div>
        <!-- page-header-inner -->
    </header>
    <!-- page-header -->

    <?php get_template_part( 'template/module/breadcrumb' ); ?>

    <section class="kopa-area kopa-area-parallax kopa-area-404">
        <div class="mask"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="kopa-404-error text-center">
                        <div>
                            <i class="fa fa-mortar-board"></i>
                            <h2><img src="<?php echo get_template_directory_uri(); ?>/images/icons/404.png" alt=""></h2>
                            <h3><?php esc_html_e('Page not found', 'upside-lite'); ?></h3>
                            <span><?php esc_html_e('Sorry, but the page you are looking for has moved or no longer exists.', 'upside-lite'); ?></span>
                        </div>

                        <div class="search-box clearfix">
                            <form action="<?php echo esc_url(home_url('/')); ?>" class="search-form clearfix" id="search-form" method="get">
                                <input type="text" onBlur="if (this.value == '')
                                        this.value = this.defaultValue;" onFocus="if (this.value == this.defaultValue)
                                        this.value = '';" value="<?php esc_html_e('Insert keyword & hit enter', 'upside-lite'); ?>" name="s" class="search-text">
                            </form><!-- search-form -->
                        </div><!--end:search-box-->

                        <a href="<?php echo esc_url( home_url('/') ); ?>" class="kopa-button pink-button kopa-button-icon small-button"><?php esc_html_e('Go to home page', 'upside-lite'); ?></a>
                    </div>
                    <!-- kopa-404-error -->
                </div>
                <!-- col-md-12 -->
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </section>
    <!-- kopa-area -->
</div>
<!-- main-content -->

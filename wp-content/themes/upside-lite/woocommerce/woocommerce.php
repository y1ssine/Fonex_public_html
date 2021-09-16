<?php
if( !class_exists('WooCommerce') ){
    return;
}

new Upside_Lite_WooCommerce();

class Upside_Lite_WooCommerce{

	function __construct(){
		add_action('init', array($this, 'init'));

		#register-layout
		add_filter('kopa_layout_manager_settings', array($this,'add_layout_product_archive'));
		add_filter('kopa_layout_manager_settings', array($this,'add_layout_product_single'));
		add_filter('kopa_custom_template_setting_id', array($this,'set_layout_setting_id'));
		add_filter('kopa_custom_template_setting', array($this, 'get_layout_setting'), 10, 2);
	}

	public function init(){
		remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
		remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 20, 0);

		add_filter('woocommerce_show_page_title', '__return_false' );
		add_filter('woocommerce_breadcrumb_defaults', array($this, 'edit_breadcrumb_args'));
		add_action('trendmag_breadcrumb', 'woocommerce_breadcrumb' );

		add_action('woocommerce_before_main_content', array($this,'before_main_content'), 5);
		add_action('woocommerce_after_main_content', array($this,'after_main_content'), 5);
		add_action('woocommerce_sidebar', array($this, 'get_sidebar'), 5);
		add_filter('loop_shop_columns', array($this, 'loop_shop_columns'));

        #Change default images
        $upside_catalog = array(
            'width' 	=> '398',
            'height'	=> '260',
            'crop'		=> 1
        );

        $upside_single = array(
            'width' 	=> '540',
            'height'	=> '460',
            'crop'		=> 1
        );

        $upside_thumbnail = array(
            'width' 	=> '180',
            'height'	=> '180',
            'crop'		=> 0
        );

        update_option( 'shop_catalog_image_size', $upside_catalog );
        update_option( 'shop_single_image_size', $upside_single );
        update_option( 'shop_thumbnail_image_size', $upside_thumbnail );
	}

	public function edit_breadcrumb_args(){
    	return array(
            'delimiter'   => '',
            'wrap_before' => '<div class="kopa-breadcrumb" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">',
            'wrap_after'  => '</div>',
            'before'      => '<span itemprop="title">',
            'after'       => '</span>',
            'home'        => esc_attr__( 'Home', 'upside-lite'),
        );
	}

    public function before_main_content() {
        $upside_main_class = 'col-md-12 col-sm-12 col-xs-12';
        $upside_setting = upside_lite_get_template_setting();
        if ( $upside_setting ) {
            $upside_sidebar = $upside_setting['sidebars'];
            if ( is_active_sidebar($upside_sidebar['sb_right']) ) {
                $upside_main_class = 'col-md-9 col-sm-9 col-xs-12';
            }
        }
        ?>
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

            <section class="kopa-area kopa-area-31">
                <div class="container">
                    <div class="row">
                        <div class="<?php echo esc_attr($upside_main_class); ?>">
                            <div role="main">
        <?php
    }

    public function after_main_content() {
        ?>
                            </div> <!--end #content-->
                        </div> <!-- end col-md-9 .main shop content -->

        <?php
    }

	public function loop_shop_columns($product_per_row){
		$product_per_row = 4;
		return $product_per_row;
	}

	public function get_sidebar(){
        $upside_setting = upside_lite_get_template_setting();
        $upside_section_cls = 'kopa-area-light kopa-area-8';
        if ( $upside_setting ) {
            $upside_sidebar = $upside_setting['sidebars'];
            if ( is_active_sidebar($upside_sidebar['sb_right']) ) {
                echo '<div class="col-md-3 col-sm-3 col-xs-12">';
                    dynamic_sidebar($upside_sidebar['sb_right']);
                echo '</div>';
            }
        }
        ?>

                    </div> <!--end .row-->
                </div> <!--end .container-->
            </section> <!--end .kopa-area-31-->

            <?php if ( is_singular('product')) :
                global $product;
                $posts_per_page_custom = esc_attr( get_theme_mod('single_product_relate_number', '4' ) );
                $related = $product->get_related( $posts_per_page_custom );
                $upside_section_cls = 'kopa-area-16 kopa-area-parallax';
                if ( ! sizeof( $related ) == 0 ):
            ?>
                <section class="kopa-area kopa-area-light" id="up-shop-related"></section>
            <?php
                endif;
                endif;
            if ( is_active_sidebar($upside_sidebar['sb_before_footer']) ) : ?>

                <section class="<?php echo esc_attr($upside_section_cls); ?>">
                    <?php if ( is_singular('product') ) : ?>
                        <div class="mask"></div>
                    <?php endif; ?>
                    <div class="container">

                        <div class="row">

                            <div class="col-md-12">
                                <?php dynamic_sidebar($upside_sidebar['sb_before_footer']); ?>
                            </div>

                        </div>
                        <!-- row -->

                    </div>
                    <!-- container -->

                </section>

            <?php endif; ?>

        <?php
	}

	public function add_layout_product_archive($options){
		$positions = upside_lite_get_positions();
		$sidebars  = upside_lite_get_sidebars();

		$layout = array(
			'title'     => esc_attr__( 'Product Archive', 'upside-lite' ),
			'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/shop.png',
			'positions' => array(
                'sb_right',
                'sb_before_footer',
                'sb_footer_1',
                'sb_footer_2',
                'sb_footer_3',
                'sb_footer_4',
                'sb_copyright'
            )
        );

		$options[] = array(
			'title'   => esc_attr__( 'Product Archive', 'upside-lite' ),
			'type' 	  => 'title',
			'id' 	  => 'product-archive'
		);

		$options[] = array(
			'title'     =>  esc_attr__( 'Product Archive',  'upside-lite' ),
			'type'      => 'layout_manager',
			'id'        => 'product-archive',
			'positions' => $positions,
			'layouts'   => array(
				'product-archive' => $layout,
			),
			'default' => array(
				'layout_id' => 'product-archive',
				'sidebars'  => array(
					'product-archive' => $sidebars,
				),
			),
		);

		return $options;
	}

	public function add_layout_product_single($options){
		$positions = upside_lite_get_positions();
		$sidebars  = upside_lite_get_sidebars();

		$layout = array(
			'title'     => esc_attr__( 'Product Single', 'upside-lite' ),
            'preview'   => get_template_directory_uri() . '/inc/assets/images/layouts/shop.png',
			'positions' => array(
                'sb_right',
                'sb_before_footer',
                'sb_footer_1',
                'sb_footer_2',
                'sb_footer_3',
                'sb_footer_4',
                'sb_copyright'
            ));


		$options[] = array(
			'title'   => esc_attr__( 'Product Single', 'upside-lite' ),
			'type' 	  => 'title',
			'id' 	  => 'product-single'
		);

		$options[] = array(
			'title'     =>  esc_attr__( 'Product Single',  'upside-lite' ),
			'type'      => 'layout_manager',
			'id'        => 'product-single',
			'positions' => $positions,
			'layouts'   => array(
				'product-single' => $layout,
			),
			'default' => array(
				'layout_id' => 'product-single',
				'sidebars'  => array(
					'product-single' => $sidebars,
				),
			),
		);

		return $options;
	}

	public function set_layout_setting_id($setting_id){
		if(is_singular('product')){
			 $setting_id = 'product-single';
		}elseif (is_post_type_archive('product') || is_tax('product_tag')  || is_tax('product_cat')) {
			 $setting_id = 'product-archive';
		}

		return $setting_id;
	}

	public function get_layout_setting($setting, $setting_id){
		if(empty($setting)){
			if('product-single' == $setting_id){
				$layouts = $this->add_layout_product_single(array());

				if(isset($layouts[1]['default'])){
					$setting = $layouts[1]['default'];
				}
			}elseif('product-archive' == $setting_id){
				$layouts = $this->add_layout_product_archive(array());

				if(isset($layouts[1]['default'])){
					$setting = $layouts[1]['default'];
				}
			}
		}

		return $setting;
	}

}
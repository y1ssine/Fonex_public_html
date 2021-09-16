<?php
/**
 * Custom hooks and functions for WooCommerce compatibility
 *
 * @package Trusted
 */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_action( 'woocommerce_before_main_content', 'trusted_theme_wrapper_start', 10);
add_action( 'woocommerce_after_main_content', 'trusted_theme_wrapper_end', 10);
add_action( 'trusted_woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
add_action( 'trusted_woocommerce_archive_description', 'woocommerce_product_archive_description', 10);

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

function trusted_theme_wrapper_start() {
	if ( !is_active_sidebar( 'trusted-shop-sidebar' ) || is_product() ) {
		$page_full_width = ' full-width';
	} else {
		$page_full_width = '';
	}
	echo '<div id="primary" class="content-area'.$page_full_width.'">
		<main id="main" class="site-main" role="main">';
}

function trusted_theme_wrapper_end() {
	echo '</main><!-- #main -->
	</div><!-- #primary -->';
	if ( !is_product() ) {
	get_sidebar( 'shop' );
	}
}

add_filter( 'woocommerce_show_page_title', '__return_false' );

add_filter( 'woocommerce_product_description_heading', '__return_false' );

add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );

function trusted_change_prev_text( $args ){
	$args['prev_text'] = '<i class="fa fa-angle-left"></i>';
	$args['next_text'] = '<i class="fa fa-angle-right"></i>';
	return $args;
}
add_filter( 'woocommerce_pagination_args', 'trusted_change_prev_text' );

function trusted_woocommerce_breadcrumbs( $defaults ) {
	$defaults['delimiter'] = '<span class="delimiter"></span>';
	$defaults['wrap_before'] = '<nav class="breadcrumb-trail breadcrumbs"><ul class="trail-items">';
	$defaults['wrap_after'] = '</ul></nav>';
	$defaults['before'] = '<li class="trail-item">';
	$defaults['after'] = '</li>';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'trusted_woocommerce_breadcrumbs' );

/*
/plugins/woocommerce/packages/woocommerce-blocks/src/BlockTypes/AbstractProductGrid.php
*/
function trusted_wc_product_block( $html, $data, $product ) {
	if ( $product->is_on_sale() ) {
		$li_sale_class = ' sale';
	} else {
		$li_sale_class = '';
	}
	$html = '<li class="wc-block-grid__product' . $li_sale_class . '">
		<a href="' . $data->permalink . '" class="wc-block-grid__product-link">
			' . $data->image . '		
			' . $data->title . '
		</a>
		' . $data->badge . '
		' . $data->price . '
		' . $data->rating . '
		' . $data->button . '
	</li>';
	return $html;
}
add_filter( 'woocommerce_blocks_product_grid_item_html', 'trusted_wc_product_block', 10, 3);

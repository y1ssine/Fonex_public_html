<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );

if ( isset($product->regular_price) && $product->regular_price > 0 ){
    $product_class = 'product_meta';
} else {
    $product_class = 'product_meta no_sale';
}


?>
<div class="<?php echo esc_attr($product_class); ?>">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'upside-lite' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_attr__( 'N/A', 'upside-lite' ); ?></span></span>

	<?php endif; ?>

	<?php echo wp_kses_post($product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'upside-lite' ) . ' ', '</span>' )); ?>

	<?php echo wp_kses_post($product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'upside-lite' ) . ' ', '</span>' )); ?>

    <?php get_template_part( 'inc/post-types/shop/share' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>

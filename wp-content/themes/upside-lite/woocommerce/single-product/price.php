<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <?php
        $price_html = '';
    if ( isset($product->regular_price) && $product->regular_price > 0
        && $product->is_on_sale() && $product->get_sale_price() > 0) {
        $price_html .= '<del>'.woocommerce_price( $product->regular_price ).'</del>';
        $price_html .= '<ins>'.woocommerce_price( $product->get_sale_price() ).'</ins>';
    } else if ( isset($product->regular_price) && $product->regular_price > 0 ){
        $price_html = '<ins>'.woocommerce_price( $product->regular_price ).'</ins>';
    } elseif ( $product->is_on_sale() && $product->get_sale_price() > 0 ) {
        $price_html .= '<ins>'.woocommerce_price( $product->get_sale_price() ).'</ins>';
    }
        if ( ! empty($price_html) ) {?>
            <p class="price"><?php echo wp_kses_post($price_html); ?></p>
        <?php }
    ?>

	<meta itemprop="price" content="<?php echo esc_html($product->get_price()); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo esc_html($product->is_in_stock() ? 'InStock' : 'OutOfStock'); ?>" />

</div>

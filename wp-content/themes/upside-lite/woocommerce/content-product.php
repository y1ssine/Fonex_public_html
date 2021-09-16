<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;
?>

<li <?php post_class('product-match-item'); ?>>

    <div class="product-thumb">
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php
                if ( $product->is_on_sale() ) {
                    printf( '<span class="onsale">%s</span>', esc_html__('Sale!', 'upside-lite') );
                } else {
                    $is_featured = get_post_meta($product->id, '_featured', true);
                    if ( 'yes' == $is_featured ) {
                        printf( '<span class="hot-item">%s</span>', esc_html__('Hot', 'upside-lite') );
                    }
                }

            ?>
            <?php echo woocommerce_get_product_thumbnail(); ?>
        </a>
        <div class="mask">
            <?php if ( isset($product->regular_price) && $product->regular_price > 0 ) : ?>
                <div class="button-box">
                    <a class="button add_to_cart_button product_type_simple" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-product_id="<?php echo esc_attr( $product->id ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" data-quantity="<?php echo esc_attr( isset( $quantity ) ? $quantity : 1 ); ?>"><i class="fa fa-shopping-cart"></i></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- product-thumb -->
    <div class="product-detail">
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <h3><?php the_title(); ?></h3>
            <footer>
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
                    if ( ! empty($price_html) ) :
                ?>
                    <span class="price">
                        <?php echo wp_kses_post($price_html); ?>
                    </span>
                <?php endif; ?>
                <?php
                    $average = floatval($product->get_average_rating());
                    if ( $average ) {
                        $upside_title_div = 'Rated ' . $average . ' out of 5';
                        $upside_star_span = '<span style="width:' . ($average*20) . '%">';
                        $upside_title_html = '<strong class="rating">' . $average . '</strong> out of 5</span>';
                        echo sprintf('<div title="%s" class="star-rating">%s%s</div>', esc_attr($upside_title_div), wp_kses_post($upside_star_span), wp_kses_post($upside_title_html));
                    }
                ?>
            </footer>
        </a>
    </div>
    <!-- product-detail -->

</li>

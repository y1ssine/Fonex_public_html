<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', esc_attr__( 'Product Description', 'upside-lite' ) ) );

?>

<?php if ( $heading ): ?>
  <h6><?php echo esc_html($heading); ?></h6>
<?php endif; ?>

<?php the_content(); ?>

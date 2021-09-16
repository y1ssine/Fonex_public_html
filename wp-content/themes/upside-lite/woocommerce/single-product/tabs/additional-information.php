<?php
/**
 * Additional Information tab
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$heading = apply_filters( 'woocommerce_product_additional_information_heading', esc_attr__( 'Additional Information', 'upside-lite' ) );

?>

<?php if ( $heading ): ?>
	<h6><?php echo esc_html($heading); ?></h6>
<?php endif; ?>

<?php $product->list_attributes(); ?>

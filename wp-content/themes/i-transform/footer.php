<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage i-transform
 * @since i-transform 1.0
 */
 
$no_footer = "";
if ( function_exists( 'rwmb_meta' ) ) {
	$no_footer = rwmb_meta('itrans_no_footer');
}  
?>

		</div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">
        <?php if( $no_footer != 1 ) : ?>
			<?php get_sidebar( 'main' ); ?>
		<?php endif; ?>
			<div class="site-info">
                <div class="copyright">
                	<?php esc_attr_e( 'Copyright &copy;', 'i-transform' ); ?>  <?php bloginfo( 'name' ); ?>
                </div>            
            	<div class="credit-info">
					<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'i-transform' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'i-transform' ); ?>">
						<?php printf( __( 'Powered by %s', 'i-transform' ), 'WordPress' ); ?>
                    </a>
                    <?php printf( __( ', Designed and Developed by', 'i-transform' )); ?> 
                    <a href="<?php echo esc_url( __( 'http://www.templatesnext.org/', 'i-transform' ) ); ?>">
                   		<?php printf( __( 'templatesnext', 'i-transform' ) ); ?>
                    </a>
                </div>

			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>
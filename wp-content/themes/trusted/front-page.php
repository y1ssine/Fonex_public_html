<?php
if ( is_page_template( 'template-blank-canvas.php' ) ) {
	get_template_part( 'template-blank-canvas' );
	return;
}

if ( is_page_template( 'template-blank-canvas-full-width.php' ) ) {
	get_template_part( 'template-blank-canvas-full-width' );
	return;
}

get_header();
if ( 'page' == get_option( 'show_on_front' ) ) {

	trusted_home_order();

} else {

	if ( ! is_active_sidebar( 'trusted-sidebar' ) ) {
		$page_full_width = ' full-width';
	} else {
		$page_full_width = '';
	}
	?>

		<div id="primary" class="content-area<?php echo $page_full_width;?>">
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content' ); ?>

				<?php endwhile; ?>

				<?php the_posts_pagination(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->

	<?php get_sidebar(); ?>

<?php
}
get_footer(); ?>
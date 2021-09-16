<?php
/**
 * Display single product reviews (comments)
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.2
 */
global $product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews">
	<div id="comments">
		<h4><?php
			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) )
				printf( _n( '%s review for %s', '%s reviews for %s', $count, 'upside-lite' ), $count, get_the_title() );
			else
				esc_html_e( 'Reviews', 'upside-lite' );
		?></h4>

		<?php if ( have_comments() ) : ?>

            <ol class="comments-list clearfix">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'upside_lite_comments_callback' ) ) ); ?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'upside-lite' ); ?></p>

		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->id ) ) : ?>

		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
					$commenter = wp_get_current_commenter();

					$comment_form = array(
						'title_reply'          => have_comments() ? esc_attr__( 'Add a review', 'upside-lite' ) : esc_attr__( 'Be the first to review', 'upside-lite' ) . ' &ldquo;' . get_the_title() . '&rdquo;',
						'title_reply_to'       => esc_attr__( 'Leave a Reply to %s', 'upside-lite' ),
						'comment_notes_before' => '',
						'comment_notes_after'  => '',
						'fields'               => array(
							'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_attr__( 'Name', 'upside-lite' ) . ' <span class="required">*</span></label> ' .
							            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
							'email'  => '<p class="comment-form-email"><label for="email">' . esc_attr__( 'Email', 'upside-lite' ) . ' <span class="required">*</span></label> ' .
							            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p>',
						),
						'label_submit'  => esc_attr__( 'Post review', 'upside-lite' ),
						'logged_in_as'  => '',
						'comment_field' => ''
					);

					if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
						$comment_form['must_log_in'] = '<p class="must-log-in">' .  sprintf( '%s <a href="%s">%s</a> %s', esc_attr__('You must be', 'upside-lite'), esc_url( $account_page_url ), esc_attr__('logged in', 'upside-lite'), esc_attr__('to post a review.', 'upside-lite') ) . '</p>';
					}

					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
						$comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating" class="upside-rating-product">' . esc_attr__( 'Your Rating', 'upside-lite' ) .'</label><select name="rating" id="rating">
							<option value="">' . esc_attr__( 'Rate&hellip;', 'upside-lite' ) . '</option>
							<option value="5">' . esc_attr__( 'Perfect', 'upside-lite' ) . '</option>
							<option value="4">' . esc_attr__( 'Good', 'upside-lite' ) . '</option>
							<option value="3">' . esc_attr__( 'Average', 'upside-lite' ) . '</option>
							<option value="2">' . esc_attr__( 'Not that bad', 'upside-lite' ) . '</option>
							<option value="1">' . esc_attr__( 'Very Poor', 'upside-lite' ) . '</option>
						</select></p>';
					}

					$comment_form['comment_field'] .= '
					    <div class="row up-review">
                            <div class="col-md-12">
                                <p class="textarea-block">
                                    <label class="required"><i class="fa fa-list-ul"></i></label>
                                    <textarea rows="6" cols="88" id="comment" name="comment" aria-required="true" style="overflow:auto;resize:vertical ;" placeholder="' . esc_html__('Your Review', 'upside-lite') . '"></textarea>
                                </p>
                            </div>
                        </div>
					';

					comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>

	<?php else : ?>

		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'upside-lite' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>

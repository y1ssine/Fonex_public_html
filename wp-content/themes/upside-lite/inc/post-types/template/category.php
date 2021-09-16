<?php
global $post;
$portfolio_categories = array();
$utp_terms = get_the_terms(get_the_ID(), 'portfolio-category');
if( !empty($utp_terms) ){
    foreach ($utp_terms as $term) {
        $item_taxonomy = sprintf('<a href="%s" title="%s">%s</a>', esc_url(get_term_link( $term )), esc_attr($term->name), esc_html($term->name));
        $portfolio_categories[] = $item_taxonomy;
    }
}
if ( $portfolio_categories ) : ?>
    <span class="entry-categories">
        <?php
            echo implode('<span>,&nbsp;</span>', $portfolio_categories);
        ?>
    </span>
<?php endif; ?>
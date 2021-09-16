<?php

/**
 * Search 
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form role="search" method="get" id="bbp-search-form" class="search-form clearfix" action="<?php bbp_search_url(); ?>">
    <input tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" placeholder="<?php esc_attr_e( 'Search Forum', 'upside-lite' ); ?>" name="bbp_search" id="bbp_search" class="search-text" />
    <input tabindex="<?php bbp_tab_index(); ?>" class="search-submit" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'upside-lite' ); ?>" />
    <input type="hidden" name="action" value="bbp-search-request" />
</form>

<?php
$upside_lite_enable_title_des = apply_filters('upside_lite_enable_show_page_title_des', 1);
if ( $upside_lite_enable_title_des ) :
$upside_lite_page_title = upside_lite_get_page_title();
$upside_lite_page_description = upside_lite_get_page_descritpion();
if ( ! empty($upside_lite_page_title) || ! empty($upside_lite_page_description) ) :
?>

<h1 class="page-title clearfix">
    <?php if ( ! empty($upside_lite_page_title) ) : ?>
        <span class="pull-left"><?php echo wp_kses_post($upside_lite_page_title); ?></span>
    <?php endif; ?>
    <?php if ( ! empty($upside_lite_page_description) ) : ?>
        <i class="pull-left"><?php echo wp_kses_post($upside_lite_page_description); ?></i>
    <?php endif; ?>
</h1>
<?php
endif;
endif;
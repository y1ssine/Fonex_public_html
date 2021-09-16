<?php
$upside_lite_sb_f1 = apply_filters('upside_lite_get_sidebar_by_position', 'sb_footer_1', 'sb_footer_1');
$upside_lite_sb_f2 = apply_filters('upside_lite_get_sidebar_by_position', 'sb_footer_2', 'sb_footer_2');
$upside_lite_sb_f3 = apply_filters('upside_lite_get_sidebar_by_position', 'sb_footer_3', 'sb_footer_3');
$upside_lite_sb_f4 = apply_filters('upside_lite_get_sidebar_by_position', 'sb_footer_4', 'sb_footer_4');
$upside_lite_sb_copyright = apply_filters('upside_lite_get_sidebar_by_position', 'sb_copyright', 'sb_copyright');
?>
</div>
<!-- main-content -->
<div id="bottom-sidebar">
    <?php if (  is_active_sidebar($upside_lite_sb_f1) || is_active_sidebar($upside_lite_sb_f2) || is_active_sidebar($upside_lite_sb_f3) || is_active_sidebar($upside_lite_sb_f4) ) : ?>
    <div class="container">
        <div class="row">
            <?php
            if ( is_active_sidebar( $upside_lite_sb_f1 ) ) {
                echo '<div class="col-md-4 col-sm-4 col-xs-12">';
                dynamic_sidebar( $upside_lite_sb_f1 );
                echo '</div>';
            }
            ?>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="row">
                    <?php
                    if ( is_active_sidebar( $upside_lite_sb_f2 ) ) {
                        echo '<div class="col-md-6 col-sm-12 col-xs-12">';
                        dynamic_sidebar( $upside_lite_sb_f2 );
                        echo '</div>';
                    }
                    if ( is_active_sidebar($upside_lite_sb_f3) ) {
                        echo '<div class="col-md-6 col-sm-12 col-xs-12">';
                        dynamic_sidebar( $upside_lite_sb_f3 );
                        echo '</div>';
                    }
                    ?>
                </div>

            </div>
            <!-- col-md-4 -->
            <?php
            if ( is_active_sidebar( $upside_lite_sb_f4 ) ) {
                echo '<div class="col-md-4 col-sm-4 col-xs-12">';
                dynamic_sidebar( $upside_lite_sb_f4 );
                echo '</div>';
            }
            ?>
        </div>
        <!-- row -->
    </div>
    <?php endif; ?>
</div>
<!-- bottom-sidebar -->
<footer id="kopa-page-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php
                    if ( is_active_sidebar( $upside_lite_sb_copyright ) ) {
                        dynamic_sidebar( $upside_lite_sb_copyright );
                    }
                ?>
            </div>
            <!-- col-md-12 -->
        </div>
        <!-- row -->
    </div>
    <!-- container -->
    <p id="back-top">
        <a href="#top"><i class="fa fa-arrow-up"></i></a>
    </p>
</footer>
<!-- kopa-page-footer -->
<?php wp_footer(); ?>
</body>
</html>

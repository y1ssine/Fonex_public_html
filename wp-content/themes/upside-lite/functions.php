<?php
#Common API
require get_template_directory() . '/api/tgm-plugin-activation.class.php';
require get_template_directory() . '/api/kopa-customization.php';

#Common function
require get_template_directory() . '/inc/helper.php';

#Extra field
require get_template_directory() . '/inc/fields/widget/link-icon.php';
require get_template_directory() . '/inc/config.php';
require get_template_directory() . '/woocommerce/woocommerce.php';

#Function
require get_template_directory() . '/inc/hook.php';

#Kopa framwork
require get_template_directory() . '/inc/sidebar.php';

if ( class_exists('Kopa_Framework') ) {
    #FEATURED
    require get_template_directory() . '/inc/layout.php';

    #POST TYPE SETTING
    require get_template_directory() . '/inc/post-types/bbpress.php';
    if ( class_exists('Upside_Lite_Toolkit') ) {
        require get_template_directory() . '/inc/post-types/course/course-config.php';
        require get_template_directory() . '/inc/post-types/member/member-config.php';
    }

}

#Page builder
require get_template_directory() . '/page-builder/init.php';

#Customize
require get_template_directory() . '/inc/customize.php';
<?php

add_action('after_setup_theme', array('Upside_Lite_Customization', 'get_instance'));

class Upside_Lite_Customization{

    public function __construct(){
        add_action('customize_register', array($this, 'customize_register'));
    }

    public static function get_instance(){
        new Upside_Lite_Customization();
    }

    public function customize_register($wp_customize){
        $wp_customize->get_setting('blogname')->transport        ='refresh';
        $wp_customize->get_setting('blogdescription')->transport ='refresh';


        $options = apply_filters('kopa_customization_init_options', array());
        if($options){

            #Add panels
            if(isset($options['panels']) && !empty($options['panels'])){
                $panels = $options['panels'];
                foreach($panels as $panel){
                    $wp_customize->add_panel($panel['id'], $panel);
                }
            }

            #Add sections
            if(isset($options['sections']) && !empty($options['sections'])){
                $sections = $options['sections'];
                foreach($sections as $section){
                    $wp_customize->add_section($section['id'], $section);
                }
            }

            #Add settings & controls
            if(isset($options['settings']) && !empty($options['settings'])){
                $settings = $options['settings'];
                foreach($settings as $setting){

                    #set default sanitize callback
                    if(!isset($setting['sanitize_callback']) || empty($setting['sanitize_callback'])){
                        switch ($setting['type']) {
                            case 'image':
                                $sanitize_callback = 'upside_lite_sanitize_image';
                                break;

                            case 'upload':
                                $sanitize_callback = 'esc_url_raw';
                                break;

                            case 'color':
                                $sanitize_callback = 'upside_lite_sanitize_hex_color';
                                break;

                            case 'textarea':
                            case 'text':
                                $sanitize_callback = 'wp_filter_post_kses';
                                break;

                            case 'range':
                                $sanitize_callback = 'sanitize_text_field';
                                break;

                            case 'select':
                            case 'radio':
                                $sanitize_callback = 'upside_lite_sanitize_select';
                                break;

                            case 'checkbox':
                                $sanitize_callback = 'upside_lite_sanitize_checkbox';
                                break;

                            default:
                                $sanitize_callback = 'sanitize_text_field';
                                break;
                        }
                    }else{
                        $sanitize_callback = $setting['sanitize_callback'];
                    }

                    #set default capability
                    if(!isset($setting['capability']) || empty($setting['capability'])){
                        $capability = 'manage_options';
                    }else{
                        $capability = $setting['capability'];
                    }

                    #add setting
                    $wp_customize->add_setting($setting['settings'], array(
                        "default"           => $setting['default'],
                        'sanitize_callback' => $sanitize_callback,
                        'capability'        => $capability,
                        "transport"         => isset($setting['transport']) ? $setting['transport'] : "refresh",
                    ));

                    # add control for this setting
                    switch ($setting['type']) {
                        case 'text':
                        case 'textarea':
                        case 'checkbox':
                        case 'radio':
                        case 'select':
                        case 'range':
                            $wp_customize->add_control(
                                $setting['settings'],
                                $setting
                            );
                            break;
                        case 'image':
                            unset($setting['type']);
                            $wp_customize->add_control(
                                new WP_Customize_Image_Control(
                                    $wp_customize,
                                    $setting['settings'],
                                    $setting));
                            break;
                        case 'upload':
                            unset($setting['type']);
                            $wp_customize->add_control(
                                new WP_Customize_Upload_Control (
                                    $wp_customize,
                                    $setting['settings'],
                                    $setting));
                            break;
                        case 'color':
                            unset($setting['type']);
                            $wp_customize->add_control(
                                new WP_Customize_Color_Control(
                                    $wp_customize,
                                    $setting['settings'],
                                    $setting));
                            break;
                        default:
                            if(isset($setting['class_name']) && !empty($setting['class_name'])){
                                $class_name = $setting['class_name'];
                                if(class_exists($class_name)){
                                    $obj = new $class_name($wp_customize, $setting['settings'], $setting);
                                    $wp_customize->add_control($obj);
                                }
                            }

                            break;
                    }
                }
            }
        }
    }

}

/**
 * https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function upside_lite_sanitize_checkbox( $checked ) {
    // Boolean check.
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * - Sanitization: hex_color
 * - Control: text, WP_Customize_Color_Control
 *
 * Note: sanitize_hex_color_no_hash() can also be used here, depending on whether
 * or not the hash prefix should be stored/retrieved with the hex color value.
 *
 * @see sanitize_hex_color() https://developer.wordpress.org/reference/functions/sanitize_hex_color/
 * @link sanitize_hex_color_no_hash() https://developer.wordpress.org/reference/functions/sanitize_hex_color_no_hash/
 *
 * @param string               $hex_color HEX color to sanitize.
 * @param WP_Customize_Setting $setting   Setting instance.
 * @return string The sanitized hex color if not null; otherwise, the setting default.
 */
function upside_lite_sanitize_hex_color( $hex_color, $setting ) {
    // Sanitize $input as a hex value without the hash prefix.
    $hex_color = sanitize_hex_color( $hex_color );

    // If $input is a valid hex value, return it; otherwise, return the default.
    return ( ! null( $hex_color ) ? $hex_color : $setting->default );
}

/**
 * HTML sanitization callback
 *
 * - Sanitization: html
 * - Control: text, textarea
 *
 * Sanitization callback for 'html' type text inputs. This callback sanitizes `$html`
 * for HTML allowable in posts.
 *
 * NOTE: wp_filter_post_kses() can be passed directly as `$wp_customize->add_setting()`
 * 'sanitize_callback'. It is wrapped in a callback here merely for example purposes.
 *
 * @see wp_filter_post_kses() https://developer.wordpress.org/reference/functions/wp_filter_post_kses/
 *
 * @param string $html HTML to sanitize.
 * @return string Sanitized HTML.
 */
function upside_lite_sanitize_html( $html ) {
    return wp_filter_post_kses( $html );
}

/**
 * Image sanitization callback
 *
 * Checks the image's file extension and mime type against a whitelist. If they're allowed,
 * send back the filename, otherwise, return the setting default.
 *
 * - Sanitization: image file extension
 * - Control: text, WP_Customize_Image_Control
 *
 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
 *
 * @param string               $image   Image filename.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string The image filename if the extension is allowed; otherwise, the setting default.
 */
function upside_lite_sanitize_image( $image, $setting ) {
    /*
      * Array of valid image file types.
      *
      * The array includes image mime types that are included in wp_get_mime_types()
      */
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon'
    );
    // Return an array with file extension and mime_type.
    $file = wp_check_filetype( $image, $mimes );
    // If $image has a valid mime_type, return it; otherwise, return the default.
    return ( $file['ext'] ? $image : $setting->default );
}

/**
 * Select sanitization callback
 *
 * - Sanitization: select
 * - Control: select, radio
 *
 * Sanitization callback for 'select' and 'radio' type controls. This callback sanitizes `$input`
 * as a slug, and then validates `$input` against the choices defined for the control.
 *
 * @see sanitize_key()               https://developer.wordpress.org/reference/functions/sanitize_key/
 * @see $wp_customize->get_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/get_control/
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function upside_lite_sanitize_select( $input, $setting ) {

    // Ensure input is a slug.
    $input = sanitize_key( $input );

    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;

    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
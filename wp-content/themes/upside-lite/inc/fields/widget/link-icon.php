<?php
function upside_lite_widget_field_link_icon($html, $wrap_start, $wrap_end, $field, $value){
    ob_start();

    echo $wrap_start;

    $value = wp_parse_args((array) $value, array('icon'=> NULL, 'text' => NULL, 'url' => NULL));
    extract($value );
    ?>
    <label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html( $field['label'] ); ?></label>
    <br/>
    <div class="upside-row">

    <div class="upside-col-xs-12">
        <?php $t_icons = upside_lite_get_icons_for_education(); ?>
            <p class="upside-block upside-block-first">
                <select id="<?php echo esc_attr($field['id']); ?>" name="<?php printf("%s[icon]", esc_attr($field['name']));?>" class="upside-icon-picker-select">
                    <?php
                    foreach( $t_icons as $k => $v ){ ?>
                        <option value="<?php echo esc_attr($k); ?>" <?php selected( $k, $icon ); ?>><?php echo esc_attr($v); ?></option>
                        <?php }
                    ?>
                </select>
                <span class="upside-icon-picker-preview"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
            </p>

            <p class="upside-block upside-block-first">
                <input class="widefat"
                   id="<?php echo esc_attr($field['id']); ?>"
                   name="<?php printf("%s[text]", esc_attr($field['name']));?>"
                   type="text"
                   placeholder="<?php esc_attr_e('Link text', 'upside-lite'); ?>"
                   autocomplete="off"
                   value="<?php echo esc_attr( $text ); ?>" />
            </p>

            <p class="upside-block">
                <input class="widefat"
                 id="<?php echo esc_attr($field['id']); ?>"
                 name="<?php printf("%s[url]", $field['name']);?>"
                 type="url"
                 placeholder="<?php esc_attr_e('Link URL', 'upside-lite'); ?>"
                 autocomplete="off"
                 value="<?php echo esc_url( $url ); ?>" />
            </p>

        </div>
    </div>
    <?php

    echo $wrap_end;

    $html = ob_get_clean();

    return $html;
}
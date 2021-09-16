<?php

if ( !function_exists( 'trusted_translation_wc' ) ) {
	function trusted_translation_wc( $string ) {
		if ( $string == 'hello' ) {
			/* translators: using woocommerce translations. %1$s: user display name, %2$s: logout url */
			printf( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ), '<strong>' . esc_html( wp_get_current_user()->display_name ) . '</strong>', esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );
		} elseif ( $string == 'login' ) {
			return esc_html__( 'Login', 'woocommerce' );
		} elseif ( $string == 'register' ) {
			return esc_html__( 'Register', 'woocommerce' );
		} else {
			echo '';
			return NULL;
		}
	}
}

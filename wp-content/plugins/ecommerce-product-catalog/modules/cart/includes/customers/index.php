<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'AL_CUSTOMERS_BASE_FILE', plugin_basename( __FILE__ ) );
define( 'AL_CUSTOMERS_BASE_URL', plugins_url( '/', __FILE__ ) );
define( 'AL_CUSTOMERS_BASE_PATH', dirname( __FILE__ ) );



add_action( 'digital_product_orders_addons', 'start_transaction_digital_customers', 20 );

/**
 * Initialize transaction related files
 */
function start_transaction_digital_customers() {
	require_once(AL_CUSTOMERS_BASE_PATH . '/includes/transactions/index.php');
	require_once(AL_CUSTOMERS_BASE_PATH . '/functions/transactions/index.php');
}

add_action( 'ecommerce-prodct-catalog-addons', 'start_digital_customers', 30 );

/**
 * Initialize core extension files
 */
function start_digital_customers() {
	require_once(AL_CUSTOMERS_BASE_PATH . '/functions/index.php');
	require_once(AL_CUSTOMERS_BASE_PATH . '/includes/index.php');
	require_once(AL_CUSTOMERS_BASE_PATH . '/ext/index.php');
	do_action( 'digital_customers_addons' );
//require_once('ext/index.php');
}

add_action( 'register_catalog_styles', 'register_digital_customers_styles' );

function register_digital_customers_styles() {
	wp_register_style( "jquery-ui-css", AL_CUSTOMERS_BASE_URL . 'css/jquery-ui.css' );
	wp_register_style( 'al_digital_customers_styles', plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) ) . '/css/digital-customers.css?' . filemtime( plugin_dir_path( __FILE__ ) . '/css/digital-customers.css' ), array( 'dashicons', 'jquery-ui-css' ) );
	wp_register_script( 'front_edit_customer_panel', AL_CUSTOMERS_BASE_URL . 'js/customer-panel.js', array( 'jquery-ui-tabs' ) );
	wp_register_script( 'front_digital_customers', AL_CUSTOMERS_BASE_URL . 'js/digital-customers.js', array( 'jquery-effects-slide', 'jquery-ui-tabs' ) );
}

add_action( 'enqueue_catalog_scripts', 'digital_customers_styles' );

function digital_customers_styles() {
	wp_enqueue_style( 'al_digital_customers_styles' );
	wp_enqueue_script( 'front_digital_customers' );
	if ( is_ic_digital_customer() ) {
		wp_enqueue_script( 'front_edit_customer_panel' );
	}
}

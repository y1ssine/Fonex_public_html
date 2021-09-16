<?php
/*
Plugin Name: Starter Sites
Plugin URI: https://wpstartersites.com/plugin/
Description: Ready to go WordPress starter sites and demos, created with the visual blocks (Gutenberg) editor. Quickly import demo content, widgets, and theme customizer settings.
Version: 1.5.1
Author: WP Starter Sites
Author URI: https://wpstartersites.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: starter-sites
Requires at least: 5
Requires PHP: 5.6
*/

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Display admin error message if PHP version is older than 5.6
 * Otherwise execute the main plugin class.
 */
if ( version_compare( phpversion(), '5.6', '<' ) ) {

	/**
	 * Display an admin error notice when PHP is older the version 5.6.
	 * Hook it to the 'admin_notices' action.
	 */
	function WPSS_old_php_admin_error_notice() {
		$message = sprintf( esc_html__( 'The %2$sStarter Sites%3$s plugin requires %2$sPHP 5.6+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.6.%4$s Your current version of PHP: %2$s%1$s%3$s', 'starter-sites' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}
	add_action( 'admin_notices', 'WPSS_old_php_admin_error_notice' );
}
else {

	// Current version of the plugin.
	define( 'WPSS_VERSION', '1.5.1' );

	// Path/URL to root of this plugin, with trailing slash.
	define( 'WPSS_PATH', plugin_dir_path( __FILE__ ) );
	define( 'WPSS_URL', plugin_dir_url( __FILE__ ) );
	define( 'WPSS_BASENAME', plugin_basename( __FILE__ ) );

	function starter_sites_activate() {
		add_option( 'starter_sites_do_activation_redirect', true );
	}
	register_activation_hook( __FILE__, 'starter_sites_activate' );


	function starter_sites_deactivate() {
		// nothing to do
	}
	register_deactivation_hook( __FILE__ , 'starter_sites_deactivate' );


	function starter_sites_delete() {
		delete_option( 'starter_sites_do_activation_redirect' );
	}
	register_uninstall_hook( __FILE__ , 'starter_sites_delete' );

	function starter_sites_redirect() {
		if ( get_option( 'starter_sites_do_activation_redirect', false ) ) {
			delete_option( 'starter_sites_do_activation_redirect' );
			if ( !isset($_GET['activate-multi'] ) ) {
				wp_redirect( 'themes.php?page=starter-sites' );
			}
		}
	}
	add_action( 'admin_init', 'starter_sites_redirect' );

	// Require main plugin file.
	require WPSS_PATH . 'inc/class-wpss-main.php';

	// Instantiate the main plugin class *Singleton*.
	$Starter_Sites = Starter_Sites::getInstance();
}
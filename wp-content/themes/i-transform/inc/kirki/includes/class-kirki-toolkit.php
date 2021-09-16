<?php
/**
 * The main Kirki object
 *
 * @package     Kirki
 * @category    Core
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2015, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Early exit if the class already exists
if ( class_exists( 'Kirki_Toolkit' ) ) {
	return;
}

class Kirki_Toolkit {

	/** @var Kirki The only instance of this class */
	public static $instance = null;

	public static $version = '1.0.2';

	public $font_registry = null;
	public $scripts       = null;
	public $api           = null;
	public $styles        = array();

	/**
	 * Access the single instance of this class
	 * @return Kirki
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new Kirki_Toolkit();
		}
		return self::$instance;
	}

	/**
	 * Shortcut method to get the translation strings
	 */
	public static function i18n() {

		$i18n = array(
			'background-color'      => __( 'Background Color', 'i-transform' ),
			'background-image'      => __( 'Background Image', 'i-transform' ),
			'no-repeat'             => __( 'No Repeat', 'i-transform' ),
			'repeat-all'            => __( 'Repeat All', 'i-transform' ),
			'repeat-x'              => __( 'Repeat Horizontally', 'i-transform' ),
			'repeat-y'              => __( 'Repeat Vertically', 'i-transform' ),
			'inherit'               => __( 'Inherit', 'i-transform' ),
			'background-repeat'     => __( 'Background Repeat', 'i-transform' ),
			'cover'                 => __( 'Cover', 'i-transform' ),
			'contain'               => __( 'Contain', 'i-transform' ),
			'background-size'       => __( 'Background Size', 'i-transform' ),
			'fixed'                 => __( 'Fixed', 'i-transform' ),
			'scroll'                => __( 'Scroll', 'i-transform' ),
			'background-attachment' => __( 'Background Attachment', 'i-transform' ),
			'left-top'              => __( 'Left Top', 'i-transform' ),
			'left-center'           => __( 'Left Center', 'i-transform' ),
			'left-bottom'           => __( 'Left Bottom', 'i-transform' ),
			'right-top'             => __( 'Right Top', 'i-transform' ),
			'right-center'          => __( 'Right Center', 'i-transform' ),
			'right-bottom'          => __( 'Right Bottom', 'i-transform' ),
			'center-top'            => __( 'Center Top', 'i-transform' ),
			'center-center'         => __( 'Center Center', 'i-transform' ),
			'center-bottom'         => __( 'Center Bottom', 'i-transform' ),
			'background-position'   => __( 'Background Position', 'i-transform' ),
			'background-opacity'    => __( 'Background Opacity', 'i-transform' ),
			'ON'                    => __( 'ON', 'i-transform' ),
			'OFF'                   => __( 'OFF', 'i-transform' ),
			'all'                   => __( 'All', 'i-transform' ),
			'cyrillic'              => __( 'Cyrillic', 'i-transform' ),
			'cyrillic-ext'          => __( 'Cyrillic Extended', 'i-transform' ),
			'devanagari'            => __( 'Devanagari', 'i-transform' ),
			'greek'                 => __( 'Greek', 'i-transform' ),
			'greek-ext'             => __( 'Greek Extended', 'i-transform' ),
			'khmer'                 => __( 'Khmer', 'i-transform' ),
			'latin'                 => __( 'Latin', 'i-transform' ),
			'latin-ext'             => __( 'Latin Extended', 'i-transform' ),
			'vietnamese'            => __( 'Vietnamese', 'i-transform' ),
			'serif'                 => _x( 'Serif', 'font style', 'i-transform' ),
			'sans-serif'            => _x( 'Sans Serif', 'font style', 'i-transform' ),
			'monospace'             => _x( 'Monospace', 'font style', 'i-transform' ),
		);

		$config = apply_filters( 'kirki/config', array() );

		if ( isset( $config['i18n'] ) ) {
			$i18n = wp_parse_args( $config['i18n'], $i18n );
		}

		return $i18n;

	}

	/**
	 * Shortcut method to get the font registry.
	 */
	public static function fonts() {
		return self::get_instance()->font_registry;
	}

	/**
	 * Constructor is private, should only be called by get_instance()
	 */
	private function __construct() {
	}

}

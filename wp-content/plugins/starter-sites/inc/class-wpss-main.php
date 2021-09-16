<?php
/**
 * Main Starter Sites plugin class/file.
 *
 * @package wpss
 */

// Include files.
require WPSS_PATH . 'inc/class-wpss-helpers.php';
require WPSS_PATH . 'inc/class-wpss-importer.php';
require WPSS_PATH . 'inc/class-wpss-widget-importer.php';
require WPSS_PATH . 'inc/class-wpss-customizer-importer.php';
require WPSS_PATH . 'inc/class-wpss-logger.php';

/**
 * Starter Sites class, so we don't have to worry about namespaces.
 */
class Starter_Sites {

	/**
	 * @var $instance the reference to *Singleton* instance of this class
	 */
	private static $instance;

	/**
	 * Private variables used throughout the plugin.
	 */
	private $importer, $plugin_page, $import_files, $logger, $log_file_path, $selected_index, $selected_import_files, $microtime, $frontend_error_messages, $ajax_call_number;


	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Starter_Sites the *Singleton* instance.
	 */
	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * Class construct function, to initiate the plugin.
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct() {

		// Actions.
		add_action( 'admin_menu', array( $this, 'create_plugin_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_WPSS_import_demo_data', array( $this, 'import_demo_data_ajax_callback' ) );
		add_action( 'after_setup_theme', array( $this, 'setup_plugin_with_filter_data' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'plugin_action_links_' . WPSS_BASENAME, array( $this, 'plugin_settings_link' ) );
	}


	/**
	 * Private clone method to prevent cloning of the instance of the *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone() {}


	/**
	 * Private unserialize method to prevent unserializing of the *Singleton* instance.
	 *
	 * @return void
	 */
	private function __wakeup() {}


	/**
	 * Creates the plugin page and a submenu item in WP Appearance menu.
	 */
	public function create_plugin_page() {
		$plugin_page_setup = apply_filters( 'starter-sites/plugin_page_setup', array(
				'parent_slug' => 'themes.php',
				'page_title'  => esc_html__( 'Starter Sites' , 'starter-sites' ),
				'menu_title'  => esc_html__( 'Starter Sites' , 'starter-sites' ),
				'capability'  => 'import',
				'menu_slug'   => 'starter-sites',
			)
		);

		$this->plugin_page = add_submenu_page( $plugin_page_setup['parent_slug'], $plugin_page_setup['page_title'], $plugin_page_setup['menu_title'], $plugin_page_setup['capability'], $plugin_page_setup['menu_slug'], array( $this, 'display_plugin_page' ) );
	}


	/**
	 * Plugin page link on plugins page.
	 */
	public function plugin_settings_link( array $links ) : array {
		$settings_page_link = '<a href="' . admin_url( 'themes.php?page=starter-sites' ) . '">' . esc_attr__( 'Preview Sites', 'starter-sites' ) . '</a>';
		array_unshift( $links, $settings_page_link );

		return $links;
	}


	/**
	 * Plugin page display.
	 */
	public function display_plugin_page() {

		$plugin_install_button = isset( $_POST["plugin-install-button"] );
		if ( $plugin_install_button ) {

			if ( isset( $_POST["installtheme"] ) ) {
				$install_theme = $_POST["installtheme"];
			} else {
				$install_theme = false;
			}

			if ( isset( $_POST["activatetheme"] ) ) {
				$activate_theme = $_POST["activatetheme"];
			} else {
				$activate_theme = false;
			}

			if ( isset( $_POST["installplugins"] ) ) {
				$install_plugins = $_POST["installplugins"];
			} else {
				$install_plugins = false;
			}

			if ( isset( $_POST["activateplugins"] ) ) {
				$activate_plugins = $_POST["activateplugins"];
			} else {
				$activate_plugins = false;
			}

			if ( $activate_theme || $install_theme ) {
				echo '<div class="WPSS__installing-theme">
					<h2>' . esc_html__( 'Activating Theme...', 'starter-sites' ) . '</h2>';

				if ( $activate_theme ) {
					if ( current_user_can( 'switch_themes' ) ) {
						foreach ( $activate_theme as $theme_to_activate ) {
							$activate_theme_data = explode( ',', $theme_to_activate);
							$this->theme_to_activate( $activate_theme_data[0], $activate_theme_data[1] );
						}
					} else {
						echo '<div class="notice-error">' . esc_html__( 'Your user role is not high enough. You do not have permission to activate themes.', 'starter-sites' ) . '</div>';
					}
				}

				if ( $install_theme ) {
					if ( current_user_can( 'install_themes' ) ) {
						foreach ( $install_theme as $theme_to_install ) {
							$install_theme_data = explode( ',', $theme_to_install);
							require_once( ABSPATH . 'wp-admin/includes/theme-install.php' );
							$this->install_theme( $install_theme_data[0], $install_theme_data[1] );
						}
					} else {
						echo '<div class="notice-error">' . esc_html__( 'Your user role is not high enough. You do not have permission to install themes.', 'starter-sites' ) . '</div>';
					}
				}

				echo '</div>';
			}

			if ( $activate_plugins || $install_plugins ) {
				echo '<div class="WPSS__installing-plugins">
					<h2>' . esc_html__( 'Activating Plugins...', 'starter-sites' ) . '</h2>';

				if ( $activate_plugins ) {
					if ( current_user_can( 'activate_plugins' ) ) {
						foreach ( $activate_plugins as $activate_plugin ) {
							$activate_plugin_data = explode( ',', $activate_plugin);
							$this->plugin_to_activate( $activate_plugin_data[0], $activate_plugin_data[2] );
						}
					} else {
						echo '<div class="notice-error">' . esc_html__( 'Your user role is not high enough. You do not have permission to activate plugins.', 'starter-sites' ) . '</div>';
					}
				}

				if ( $install_plugins ) {
					if ( current_user_can( 'install_plugins' ) ) {
						foreach ( $install_plugins as $install_plugin ) {
							$install_plugin_data = explode( ',', $install_plugin);
							require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
							$this->install_plugin( $install_plugin_data[0], $install_plugin_data[1] );
						}
					} else {
						echo '<div class="notice-error">' . esc_html__( 'Your user role is not high enough. You do not have permission to install plugins.', 'starter-sites' ) . '</div>';
					}
				}

				echo '</div>';
			}

		}
		?>

		<div id="WPSS__response" class="WPSS__response  js-wpss-ajax-response"></div>

		<div class="wpss wrap about-wrap">

			<h1 class="WPSS__title"><?php esc_html_e( 'Starter Sites', 'starter-sites' ); ?></h1>

			<?php
			// Display warrning if PHP safe mode is enabled, since we wont be able to change the max_execution_time.
			if ( ini_get( 'safe_mode' ) ) {
				printf(
					esc_html__( '%sWarning: your server is using %sPHP safe mode%s. This means that you might experience server timeout errors.%s', 'starter-sites' ),
					'<div class="notice  notice-warning  is-dismissible"><p>',
					'<strong>',
					'</strong>',
					'</p></div>'
				);
			}
			?>

			<div class="WPSS__intro-text">

				<div>
					<p>
						<?php esc_html_e( 'Ready to go WordPress starter sites and demos.', 'starter-sites' ); ?>
					</p>
					<p>
						<?php esc_html_e( 'Quickly import live demo content, widgets and settings.', 'starter-sites' ); ?>
					</p>
					<p>
						<?php esc_html_e( 'This will provide you with a basic layout to build your website and speed up the development process.', 'starter-sites' ); ?>
					</p>
				</div>

				<div>
					<p><?php esc_html_e( 'The following data will be imported:', 'starter-sites' ); ?></p>
					<ul>
						<li><?php esc_html_e( 'Posts', 'starter-sites' ); ?></li>
						<li><?php esc_html_e( 'Pages', 'starter-sites' ); ?></li>
						<li><?php esc_html_e( 'Products (if e-commerce)', 'starter-sites' ); ?></li>
						<li><?php esc_html_e( 'Images', 'starter-sites' ); ?></li>
						<li><?php esc_html_e( 'Widgets', 'starter-sites' ); ?></li>
						<li><?php esc_html_e( 'Menus', 'starter-sites' ); ?></li>
						<li><?php esc_html_e( 'Theme Settings', 'starter-sites' ); ?></li>
					</ul>
				</div>

				<div>
					<p><?php esc_html_e( 'It is recommended to import demo content to a brand new, fresh WordPress site.', 'starter-sites' ); ?></p>
					<p><?php esc_html_e( 'Your existing content will NOT be deleted or modified, and your existing theme customizations will NOT be overwritten.', 'starter-sites' ); ?></p>
				</div>

			</div>

			<?php
				// Get installed plugins info
				$plugins_installed = array();
				$all_plugins = get_plugins();

				foreach ($all_plugins as $this_plugin_path => $this_plugin) {
					$this_plugin_dir = strtok( $this_plugin_path, '/' );
					if ( is_plugin_active( $this_plugin_path ) ) {
						$this_plugin_status = 'yes';
					} else {
						$this_plugin_status = 'no';
					}

					$plugins_installed[ $this_plugin_dir ] = array(
						'pathto' => $this_plugin_path,
						'active' => $this_plugin_status,
					);
				}
			?>

			<?php if ( empty( $this->import_files ) ) : ?>

							<div class="notice  notice-info  is-dismissible">
					<p><?php esc_html_e( 'There seems to be a connection error. Please try again later.', 'starter-sites' ); ?></p>
				</div>

			<?php elseif ( 1 <= count( $this->import_files ) ) : ?>

				<?php
				$current_theme = wp_get_theme()->stylesheet;
				$the_sites = $this->import_files;
				$themes_available_all = array();
				foreach ( $the_sites as $index => $this_site ) {
					if ( $this_site['import_theme_slug'] !== '' ) {
						$themes_available_all[] = $this_site['import_theme_slug'];
					} else {
						if ( $this_site['pro_theme_slug'] !== '' ) {
							$themes_available_all[] = $this_site['pro_theme_slug'];
						}
					}
				}
				$themes_available_all = array_unique( $themes_available_all );
				sort($themes_available_all);

				if ( $themes_available_all ) {
					echo '<div class="wpss-theme-picker-wrap">
					<div class="theme">
						<div class="button-wrap">
							<p>' . esc_html__( 'Browse by theme:', 'starter-sites' ) . '</p>
						</div>
					</div>
					<div class="theme all-themes">
						<div class="button-wrap selected">
							<button id="theme-picker" class="theme-picker button all-themes" value="all-themes" data-themeid="all-themes">' . esc_html__( 'All Themes', 'starter-sites' ) . '</button>
						</div>
					</div>';
					foreach ( $themes_available_all as $theme_avail ) {
						$theme_avail_name = str_ireplace('-', ' ', $theme_avail);
						$theme_avail_name = ucwords( $theme_avail_name );
						if ( $theme_avail === $current_theme ) {
							$class_current = ' current';
						} else {
							$class_current = '';
						}
						echo '<div class="theme ' . esc_attr( $theme_avail ) . '">
							<div class="button-wrap' . $class_current . '">
								<button id="theme-picker" class="theme-picker button ' . esc_attr( $theme_avail ) . '" value="' . esc_attr( $theme_avail ) . '" data-themeid="' . esc_attr( $theme_avail ) . '">' . esc_html( $theme_avail_name ) . '</button>
							</div>
						</div>';
					}
					echo '</div>';
				}

				$all_theme_demos = $this->import_files;
				?>

				<div class="WPSS__select-import">

					<div class="demo-preview-wrap">

						<?php
						foreach ( $all_theme_demos as $index => $import_file ) {
							$do_button_import_theme = false;
							$do_button_import_plugin = false;
							$buttons_off = false;

							$install_activate_form = '';

							$pro_banner = '';

							if ( $import_file['import_theme_name'] === '' && $import_file['pro_theme_name'] === '' ) {
								$preview_class = '';
								$preview_class_slug = '';
								$theme_message = esc_html__( 'This demo will be available very soon!', 'starter-sites' );
								$class_req = '';
								$buttons_off = true;
							} elseif ( $import_file['pro_theme_name'] !== '' ) {
								/* pro themes */


								/*check if demo content exists in pro theme*/
								$import_file['local_import_file'];
								if ( file_exists( $import_file['local_import_file'] ) ) {
									$pro_update_prompt = '';
									$pro_update_button = false;
								} else {
									$pro_update_prompt = ' - <a href="' . admin_url( 'themes.php' ) . '">' . esc_html__( 'update required', 'starter-sites' ) . '</a>';
									$pro_update_button = true;
								}



								$pro_banner = '<div class="pro-tag">' . esc_html__( 'PRO', 'starter-sites' ) . '</div>';
								$preview_class_slug = esc_attr( $import_file['pro_theme_slug'] );
								if ( $import_file['pro_theme_slug'] && wp_get_theme( $import_file['pro_theme_slug'] )->exists() ) {
									$is_theme_installed = true;
									//now check if theme is active
									if ( wp_get_theme()->stylesheet === $import_file['pro_theme_slug'] ) {
										$is_theme_active = true;
									} else {
										$is_theme_active = false;
									}
								} else {
									$is_theme_installed = false;
									$is_theme_active = false;
								}

								if ( $is_theme_installed ) {
									if ( $is_theme_active ) {
										$preview_class = ' is-active-theme ';
										$theme_message = '<i class="dashicons dashicons-yes"></i>' . esc_html( $import_file['pro_theme_name'] );
										$do_button_import_theme = true;
										$class_req = '';
									} else {
										$preview_class = '';
										$theme_message = '<i class="dashicons dashicons-no"></i>' . esc_html( $import_file['pro_theme_name'] ) . '';
										$class_req = ' required';
										$install_activate_form .= '<input type="hidden" name="activatetheme[]" value="' . $import_file['pro_theme_name'] . ',' . $import_file['pro_theme_slug'] . '">';
									}
								} else {
									$preview_class = ' pro-not-installed ';
									$pro_update_prompt = '';
									$theme_message = '<i class="dashicons dashicons-no"></i>' . esc_html( $import_file['pro_theme_name'] ) . '';
									$class_req = ' required';
									$install_activate_form = '';
								}
							} else {
								/* free themes */
								$pro_update_prompt = '';
								$pro_update_button = false;
								$preview_class_slug = esc_attr( $import_file['import_theme_slug'] );
								if ( $import_file['import_theme_slug'] && wp_get_theme( $import_file['import_theme_slug'] )->exists() ) {
									$is_theme_installed = true;
									//now check if theme is active
									if ( wp_get_theme()->stylesheet === $import_file['import_theme_slug'] ) {
										$is_theme_active = true;
									} else {
										$is_theme_active = false;
									}
								} else {
									$is_theme_installed = false;
									$is_theme_active = false;
								}

								if ( $is_theme_installed ) {
									if ( $is_theme_active ) {
										$preview_class = ' is-active-theme ';
										$theme_message = '<i class="dashicons dashicons-yes"></i>' . esc_html( $import_file['import_theme_name'] );
										$do_button_import_theme = true;
										$class_req = '';
									} else {
										$preview_class = '';
										$theme_message = '<i class="dashicons dashicons-no"></i>' . esc_html( $import_file['import_theme_name'] ) . '';
										$class_req = ' required';
										$install_activate_form .= '<input type="hidden" name="activatetheme[]" value="' . $import_file['import_theme_name'] . ',' . $import_file['import_theme_slug'] . '">';
									}
								} else {
									$preview_class = '';
									$theme_message = '<i class="dashicons dashicons-no"></i>' . esc_html( $import_file['import_theme_name'] ) . '';
									$class_req = ' required';
									$install_activate_form .= '<input type="hidden" name="installtheme[]" value="' . $import_file['import_theme_name'] . ',' . $import_file['import_theme_slug'] . '">';
								}
							}

						?>
							<div id="site-id-<?php echo esc_attr( $index ) ; ?>" class="demo-preview <?php echo $preview_class . $preview_class_slug; ?>">
								<?php echo $pro_banner; ?>
								<img id="WPSS__demo-import-preview-image" class="js-wpss-preview-image" src="<?php echo esc_html( $import_file['import_preview_image_url'] ); ?>">
								<h3><?php echo esc_html( $import_file['import_file_name'] ); ?></h3>

								<div class="theme-required<?php echo $class_req; ?>">
									<?php if ( !$buttons_off ) { ?>
									<p><?php esc_html_e( 'Theme:', 'starter-sites' ); ?></p>
									<?php }?>
									<p><?php echo $theme_message; ?><?php echo $pro_update_prompt; ?></p>
								</div>

								<?php
								if ( !$buttons_off && $import_file['import_plugins'] ) {
								?>
								<div class="plugins-required required">
									<p><?php esc_html_e( 'Plugins:', 'starter-sites' ); ?></p>

									<?php
									$required_plugins_count = 0;
									foreach ( $import_file['import_plugins'] as $import_plugin ) {
										$import_plugin = array( explode(',', $import_plugin) );
										foreach ( $import_plugin as $plugin_info ) {

											if ( array_key_exists( $plugin_info[1], $plugins_installed ) ) {
												if ( $plugins_installed[$plugin_info[1]]['active'] === 'no' ) {
													$required_plugins_count++;
													echo '<p><i class="dashicons dashicons-no"></i>' . $plugin_info[0] . '</p>';
													$install_activate_form .= '<input type="hidden" name="activateplugins[]" value="' . $plugin_info[0] . ',' . $plugin_info[1] . ',' . $plugins_installed[$plugin_info[1]]['pathto'] . '">';
												} else {
													echo '<p><i class="dashicons dashicons-yes"></i>' . $plugin_info[0] . '</p>';
												}
											} else {
												$required_plugins_count++;
												echo '<p><i class="dashicons dashicons-no"></i>' . $plugin_info[0] . '</p>';
												$install_activate_form .= '<input type="hidden" name="installplugins[]" value="' . $plugin_info[0] . ',' . $plugin_info[1] . '">';
											}

										}
									}
									if ( $required_plugins_count == 0 ) {
										$do_button_import_plugin = true;
									}
									?>
									
								</div>
								<?php
								} else {
									$do_button_import_plugin = true;
								}

								if ( $do_button_import_theme && $do_button_import_plugin ) {
									$button_import = '<button class="button button-primary js-wpss-import-data" value="' . esc_attr( $index ) . '" data-siteid="' . esc_attr( $index ) . '">' .esc_html__( 'Import', 'starter-sites' ) . '</button>';
									$button_install = '';
								} else {
									$button_import = '';
									if ( !$do_button_import_theme && !$do_button_import_plugin ) {
										$button_install = '<input type="submit" name="plugin-install-button" value="' . esc_attr__( 'Activate Theme & Plugins', 'starter-sites' ) . '" class="button" />';
									} elseif ( !$do_button_import_theme && $do_button_import_plugin ) {
										$button_install = '<input type="submit" name="plugin-install-button" value="' . esc_attr__( 'Activate Theme', 'starter-sites' ) . '" class="button" />';
									} elseif ( $do_button_import_theme && !$do_button_import_plugin ) {
										$button_install = '<input type="submit" name="plugin-install-button" value="' . esc_attr__( 'Activate Plugins', 'starter-sites' ) . '" class="button" />';
									} else {
										$button_install = '';
									}
									
								}
								?>

								<?php if ( !$buttons_off ) { ?>
									<div class="buttons-wrap">
										<?php
										if ( $install_activate_form !== '' ) {
											if ( $preview_class == ' pro-not-installed ' ) { ?>
												<a class="button pro" href="https://uxlthemes.com/theme/<?php echo esc_html( $import_file['pro_theme_slug'] ); ?>/" target="_blank"><?php esc_html_e( 'GO PRO', 'starter-sites' ); ?> <i class="dashicons dashicons-plus-alt"></i></a>
											<?php } else {
												echo '<div><form action="' . esc_url( get_admin_url() ) . 'themes.php?page=starter-sites" method="post" class="demo-preview-form">' . $install_activate_form . $button_install . '</form></div>';
											}
										} else {
											if ( $pro_banner !== '' && $button_import === '' ) { ?>
												<a class="button pro" href="https://uxlthemes.com/theme/<?php echo esc_html( $import_file['pro_theme_slug'] ); ?>/" target="_blank"><?php esc_html_e( 'GO PRO', 'starter-sites' ); ?> <i class="dashicons dashicons-plus-alt"></i></a>
											<?php }
										}
										?>
										<?php
										if ( $pro_update_button ) {
											echo '<div></div>';
										} else {
											echo $button_import;
										}
										?>
										<a class="button" href="<?php echo esc_html( $import_file['preview_url'] ); ?>" target="_blank"><?php esc_html_e( 'View Demo', 'starter-sites' ); ?> <i class="dashicons dashicons-external"></i></a>
									</div>
								<?php } ?>

							</div>
						<?php
						}
						?>
							<div class="demo-preview empty">
								<p><?php esc_html_e( 'More sites and demos here soon.', 'starter-sites' ); ?></p>
							</div>
					</div>

				</div>

			<?php endif; ?>

			<div class="WPSS__demo-import-notice  js-wpss-demo-import-notice"><?php
				if ( is_array( $this->import_files ) && ! empty( $this->import_files[0]['import_notice'] ) ) {
					echo wp_kses_post( $this->import_files[0]['import_notice'] );
				}
			?></div>

			<p class="WPSS__ajax-loader  js-wpss-ajax-loader">
				<span class="spinner"></span> <?php esc_html_e( 'Importing, please wait!', 'starter-sites' ); ?>
			</p>

		</div>

	<?php
	}


	/**
	 * Enqueue admin scripts (JS and CSS)
	 *
	 * @param string $hook holds info on which admin page you are currently loading.
	 */
	public function admin_enqueue_scripts( $hook ) {

		// Enqueue the scripts only on the plugin page.
		if ( $this->plugin_page === $hook ) {
			wp_enqueue_script( 'wpss-main-js', WPSS_URL . 'assets/js/main.js' , array( 'jquery', 'jquery-form' ), WPSS_VERSION );

			wp_localize_script( 'wpss-main-js', 'wpss',
				array(
					'ajax_url'     => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'   => wp_create_nonce( 'wpss-ajax-verification' ),
					'import_files' => $this->import_files,
					'texts'        => array(
						'missing_preview_image' => esc_html__( 'No preview image defined for this import.', 'starter-sites' ),
					),
				)
			);

			wp_enqueue_style( 'wpss-main-css', WPSS_URL . 'assets/css/main.css', array() , WPSS_VERSION );

		}
	}


	/**
	 * Main AJAX callback function for:
	 * 1. prepare import files (uploaded or predefined via filters)
	 * 2. import content
	 * 3. before widgets import setup (optional)
	 * 4. import widgets (optional)
	 * 5. import customizer options (optional)
	 * 6. after import setup (optional)
	 */
	public function import_demo_data_ajax_callback() {

		// Try to update PHP memory limit (so that it does not run out of it).
		ini_set( 'memory_limit', apply_filters( 'starter-sites/import_memory_limit', '350M' ) );

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		WPSS_Helpers::verify_ajax_call();

		// Is this a new AJAX call to continue the previous import?
		$use_existing_importer_data = $this->get_importer_data();

		if ( ! $use_existing_importer_data ) {

			// Set the AJAX call number.
			$this->ajax_call_number = empty( $this->ajax_call_number ) ? 0 : $this->ajax_call_number;

			// Error messages displayed on front page.
			$this->frontend_error_messages = '';

			// Create a date and time string to use for demo and log file names.
			$demo_import_start_time = date( apply_filters( 'starter-sites/date_format_for_file_names', 'Y-m-d__H-i-s' ) );

			// Define log file path.
			$this->log_file_path = WPSS_Helpers::get_log_path( $demo_import_start_time );

			// Get selected file index or set it to 0.
			$this->selected_index = empty( $_POST['selected'] ) ? 0 : absint( $_POST['selected'] );

			/**
			 * 1. Prepare import files.
			 * Manually uploaded import files or predefined import files via filter: starter-sites/import_files
			 */
			if ( ! empty( $_FILES ) ) { // Using manual file uploads?

				// Get paths for the uploaded files.
				$this->selected_import_files = WPSS_Helpers::process_uploaded_files( $_FILES, $this->log_file_path );

				// Set the name of the import files, because we used the uploaded files.
				$this->import_files[ $this->selected_index ]['import_file_name'] = esc_html__( 'Manually uploaded files', 'starter-sites' );
			}
			elseif ( ! empty( $this->import_files[ $this->selected_index ] ) ) { // Use predefined import files from wp filter: starter-sites/import_files.

				// Download the import files (content and widgets files) and save it to variable for later use.
				$this->selected_import_files = WPSS_Helpers::download_import_files(
					$this->import_files[ $this->selected_index ],
					$demo_import_start_time
				);

				// Check Errors.
				if ( is_wp_error( $this->selected_import_files ) ) {

					// Write error to log file and send an AJAX response with the error.
					WPSS_Helpers::log_error_and_send_ajax_response(
						$this->selected_import_files->get_error_message(),
						$this->log_file_path,
						esc_html__( 'Downloaded files', 'starter-sites' )
					);
				}

				// Add this message to log file.
				$log_added = WPSS_Helpers::append_to_file(
					sprintf(
						__( 'The import files for: %s were successfully downloaded!', 'starter-sites' ),
						$this->import_files[ $this->selected_index ]['import_file_name']
					) . WPSS_Helpers::import_file_info( $this->selected_import_files ),
					$this->log_file_path,
					esc_html__( 'Downloaded files' , 'starter-sites' )
				);
			}
			else {

				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'starter-sites' ) );
			}
		}

		/**
		 * 2. Import content.
		 * Returns any errors greater then the "error" logger level, that will be displayed on front page.
		 */
		$this->frontend_error_messages .= $this->import_content( $this->selected_import_files['content'] );

		/**
		 * 3. Before widgets import setup.
		 */
		$action = 'starter-sites/before_widgets_import';
		if ( ( false !== has_action( $action ) ) && empty( $this->frontend_error_messages ) ) {

			// Run the before_widgets_import action to setup other settings.
			$this->do_import_action( $action, $this->import_files[ $this->selected_index ] );
		}

		/**
		 * 4. Import widgets.
		 */
		if ( ! empty( $this->selected_import_files['widgets'] ) && empty( $this->frontend_error_messages ) ) {
			$this->import_widgets( $this->selected_import_files['widgets'] );
		}

		/**
		 * 5. Import customize options.
		 */
		if ( ! empty( $this->selected_import_files['customizer'] ) && empty( $this->frontend_error_messages ) ) {
			$this->import_customizer( $this->selected_import_files['customizer'] );
		}

		/**
		 * 6. After import setup.
		 */
		if ( empty( $this->frontend_error_messages ) ) {
			$this->assign_menu_home_blog_page();
		}

		// Display final messages (success or error messages).
		if ( empty( $this->frontend_error_messages ) ) {
			$response['message'] = sprintf(
				__( '%1$s%3$sThat\'s it, all done!%4$s%2$sThe import has finished. Please %6$scheck your site%7$s and make sure that everything has imported correctly.%5$s', 'starter-sites' ),
				'<div class="notice  notice-success is-dismissible"><p>',
				'<br>',
				'<strong>',
				'</strong>',
				'</p></div>',
				'<a href="' . esc_url( home_url( '/' ) ) . '">',
				'</a>'
			);
		}
		else {
			$response['message'] = $this->frontend_error_messages . '<br>';
			$response['message'] .= sprintf(
				__( '%1$sThe demo import has finished, but there were some import errors.%2$sMore details about the errors can be found in this %3$s%5$slog file%6$s%4$s%7$s', 'starter-sites' ),
				'<div class="notice  notice-error is-dismissible"><p>',
				'<br>',
				'<strong>',
				'</strong>',
				'<a href="' . WPSS_Helpers::get_log_url( $this->log_file_path ) .'" target="_blank">',
				'</a>',
				'</p></div>'
			);
		}

		wp_send_json( $response );
	}


	/**
	 * Import content from an WP XML file.
	 *
	 * @param string $import_file_path path to the import file.
	 */
	private function import_content( $import_file_path ) {

		$this->microtime = microtime( true );

		// This should be replaced with multiple AJAX calls (import in smaller chunks)
		// so that it would not come to the Internal Error, because of the PHP script timeout.
		// Also this function has no effect when PHP is running in safe mode
		// http://php.net/manual/en/function.set-time-limit.php.
		// Increase PHP max execution time.
		set_time_limit( apply_filters( 'starter-sites/set_time_limit_for_demo_data_import', 300 ) );

		// Disable import of authors.
		add_filter( 'wxr_importer.pre_process.user', '__return_false' );

		// Check, if we need to send another AJAX request and set the importing author to the current user.
		add_filter( 'wxr_importer.pre_process.post', array( $this, 'new_ajax_request_maybe' ) );

		// Disables generation of multiple image sizes (thumbnails) in the content import step.
		if ( ! apply_filters( 'starter-sites/regenerate_thumbnails_in_content_import', true ) ) {
			add_filter( 'intermediate_image_sizes_advanced',
				function() {
					return null;
				}
			);
		}

		// Import content.
		if ( ! empty( $import_file_path ) ) {
			ob_start();
				$this->importer->import( $import_file_path );
			$message = ob_get_clean();

			// Add this message to log file.
			$log_added = WPSS_Helpers::append_to_file(
				$message . PHP_EOL . esc_html__( 'Max execution time after content import = ' , 'starter-sites' ) . ini_get( 'max_execution_time' ),
				$this->log_file_path,
				esc_html__( 'Importing content' , 'starter-sites' )
			);
		}

		// Delete content importer data for current import from DB.
		delete_transient( 'WPSS_importer_data' );

		// Return any error messages for the front page output (errors, critical, alert and emergency level messages only).
		return $this->logger->error_output;
	}


	/**
	 * Import widgets from WIE or JSON file.
	 *
	 * @param string $widget_import_file_path path to the widget import file.
	 */
	private function import_widgets( $widget_import_file_path ) {

		// Widget import results.
		$results = array();

		// Create an instance of the Widget Importer.
		$widget_importer = new WPSS_Widget_Importer();

		// Import widgets.
		if ( ! empty( $widget_import_file_path ) ) {

			// Import widgets and return result.
			$results = $widget_importer->import_widgets( $widget_import_file_path );
		}

		// Check for errors.
		if ( is_wp_error( $results ) ) {

			// Write error to log file and send an AJAX response with the error.
			WPSS_Helpers::log_error_and_send_ajax_response(
				$results->get_error_message(),
				$this->log_file_path,
				esc_html__( 'Importing widgets', 'starter-sites' )
			);
		}

		ob_start();
			$widget_importer->format_results_for_log( $results );
		$message = ob_get_clean();

		// Add this message to log file.
		$log_added = WPSS_Helpers::append_to_file(
			$message,
			$this->log_file_path,
			esc_html__( 'Importing widgets' , 'starter-sites' )
		);
	}


	/**
	 * Import customizer from a DAT file, generated by the Customizer Export/Import plugin.
	 *
	 * @param string $customizer_import_file_path path to the customizer import file.
	 */
	private function import_customizer( $customizer_import_file_path ) {

		// Try to import the customizer settings.
		$results = WPSS_Customizer_Importer::import_customizer_options( $customizer_import_file_path );

		// Check for errors.
		if ( is_wp_error( $results ) ) {

			// Write error to log file and send an AJAX response with the error.
			WPSS_Helpers::log_error_and_send_ajax_response(
				$results->get_error_message(),
				$this->log_file_path,
				esc_html__( 'Importing customizer settings', 'starter-sites' )
			);
		}

		// Add this message to log file.
		$log_added = WPSS_Helpers::append_to_file(
			esc_html__( 'Customizer settings import finished!', 'starter-sites' ),
			$this->log_file_path,
			esc_html__( 'Importing customizer settings' , 'starter-sites' )
		);
	}


	/**
	 * Setup other things in the passed wp action.
	 *
	 * @param string $action the action name to be executed.
	 * @param array  $selected_import with information about the selected import.
	 */
	private function do_import_action( $action, $selected_import ) {

		ob_start();
			do_action( $action, $selected_import );
		$message = ob_get_clean();

		// Add this message to log file.
		$log_added = WPSS_Helpers::append_to_file(
			$message,
			$this->log_file_path,
			$action
		);
	}


	/**
	 * Check if we need to create a new AJAX request, so that server does not timeout.
	 *
	 * @param array $data current post data.
	 * @return array
	 */
	public function new_ajax_request_maybe( $data ) {
		$time = microtime( true ) - $this->microtime;

		// We should make a new ajax call, if the time is right.
		if ( $time > apply_filters( 'starter-sites/time_for_one_ajax_call', 25 ) ) {
			$this->ajax_call_number++;
			$this->set_importer_data();

			$response = array(
				'status'  => 'newAJAX',
				'message' => 'Time for new AJAX request!: ' . $time,
			);

			// Add any output to the log file and clear the buffers.
			$message = ob_get_clean();

			// Add message to log file.
			$log_added = WPSS_Helpers::append_to_file(
				__( 'Completed AJAX call number: ' , 'starter-sites' ) . $this->ajax_call_number . PHP_EOL . $message,
				$this->log_file_path,
				''
			);

			wp_send_json( $response );
		}

		// Set importing author to the current user.
		// Fixes the [WARNING] Could not find the author for ... log warning messages.
		$current_user_obj    = wp_get_current_user();
		$data['post_author'] = $current_user_obj->user_login;

		return $data;
	}

	/**
	 * Set current state of the content importer, so we can continue the import with new AJAX request.
	 */
	private function set_importer_data() {
		$data = array(
			'frontend_error_messages' => $this->frontend_error_messages,
			'ajax_call_number'        => $this->ajax_call_number,
			'log_file_path'           => $this->log_file_path,
			'selected_index'          => $this->selected_index,
			'selected_import_files'   => $this->selected_import_files,
		);

		$data = array_merge( $data, $this->importer->get_importer_data() );

		set_transient( 'WPSS_importer_data', $data, 0.5 * HOUR_IN_SECONDS );
	}

	/**
	 * Get content importer data, so we can continue the import with this new AJAX request.
	 */
	private function get_importer_data() {
		if ( $data = get_transient( 'WPSS_importer_data' ) ) {
			$this->frontend_error_messages                = empty( $data['frontend_error_messages'] ) ? '' : $data['frontend_error_messages'];
			$this->ajax_call_number                       = empty( $data['ajax_call_number'] ) ? 1 : $data['ajax_call_number'];
			$this->log_file_path                          = empty( $data['log_file_path'] ) ? '' : $data['log_file_path'];
			$this->selected_index                         = empty( $data['selected_index'] ) ? 0 : $data['selected_index'];
			$this->selected_import_files                  = empty( $data['selected_import_files'] ) ? array() : $data['selected_import_files'];
			$this->importer->set_importer_data( $data );

			return true;
		}
		return false;
	}

	/**
	 * Load the plugin textdomain, so that translations can be made.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'starter-sites', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}


	/**
	 * Get data from filters, after the theme has loaded and instantiate the importer.
	 */
	public function setup_plugin_with_filter_data() {

		// Get info of import data files and filter it.
		$this->import_files = WPSS_Helpers::validate_import_file_info( $this->get_demo_sites_info() );

		// Importer options array.
		$importer_options = apply_filters( 'starter-sites/importer_options', array(
			'fetch_attachments' => true,
		) );

		// Logger options for the logger used in the importer.
		$logger_options = apply_filters( 'starter-sites/logger_options', array(
			'logger_min_level' => 'warning',
		) );

		// Configure logger instance and set it to the importer.
		$this->logger            = new WPSS_Logger();
		$this->logger->min_level = $logger_options['logger_min_level'];

		// Create importer instance with proper parameters.
		$this->importer = new WPSS_Importer( $importer_options, $this->logger );
	}


	/**
	 * Get info from the demo site.
	 */

	private function get_demo_sites_info() {

		$domain = 'https://wpstartersites.com';
		$domain_demo = 'https://demo.wpstartersites.com';
		$domain_file_dir = 'sites';

		$request = get_transient( 'WPSS_demo_sites_info' );

		if ( false === $request ) {

			$request = wp_remote_get( $domain . '/demos.json' );

			if ( is_wp_error( $request ) ) {
				$transient_timeout = 30;
			} else {
				$transient_timeout = 3600;
			}
			set_transient( 'WPSS_demo_sites_info', $request, $transient_timeout );

		}

		$raw_posts = array();

		if ( ! is_wp_error( $request ) && 200 === $request['response']['code'] ) {
			$raw_posts = json_decode( $request['body'], true );
		}

		if ( !$raw_posts ) {
			$raw_posts = json_decode( file_get_contents( WPSS_PATH . 'assets/json/demos-fallback.json' ), true );
		}

		$import_sites = array();

		foreach ( $raw_posts['items'] as $item ) {

			$demo_name = $item['title'];
			$demo_slug = $item['slug'];
			$demo_year = $item['year'];
			$demo_month = $item['month'];
			$demo_img = $item['thumbnail'];
			$theme_name = $item['themeName'];
			$theme_slug = $item['themeSlug'];
			$theme_pro = $item['themePro'];

			$plugins = array();
			if ( $item['plugins'] ) {
				foreach ( $item['plugins'] as $plugin ) {
					$plugins[] = $plugin['name'] . ',' . $plugin['slug'];
				}
			}


			if ( $theme_pro === 'yes' ) {
				$demo_file_key = 'local_import_file';
				$demo_widget_file_key = 'local_import_widget_file';
				$demo_customizer_file_key = 'local_import_customizer_file';
				$demo_import_path = get_theme_root( 'cordero-pro' ) . '/cordero-pro/demo-content/' . $demo_slug . '/';
				$pro_theme_slug = $theme_slug;
				$pro_theme_name = $theme_name;
				$theme_slug = '';
				$theme_name = '';
			} else {
				$demo_file_key = 'import_file_url';
				$demo_widget_file_key = 'import_widget_file_url';
				$demo_customizer_file_key = 'import_customizer_file_url';
				$demo_import_path = $domain . '/' . $domain_file_dir . '/' . $demo_slug . '/';
				$pro_theme_slug = '';
				$pro_theme_name = '';
			}

			if ( !$demo_img ) {
				if ( !$demo_year || !$demo_month ) {
					$demo_img = WPSS_URL . 'assets/images/placeholder-demo.jpg';
				} else {
					$demo_img = $domain . '/wp-content/uploads/edd/' . $demo_year . '/' . $demo_month . '/' . $demo_slug . '-500x375-1.jpg';
				}
			}

			$import_sites[] = array(
				'import_file_name'				=> $demo_name,
				$demo_file_key					=> $demo_import_path . 'content.txt',
				$demo_widget_file_key			=> $demo_import_path . 'widgets.txt',
				$demo_customizer_file_key		=> $demo_import_path . 'customizer.txt',
				'import_preview_image_url'		=> $demo_img,
				'import_notice'					=> '',
				'preview_url'					=> $domain_demo . '/' . $demo_slug . '/',
				'import_theme_name'				=> $theme_name,
				'import_theme_slug'				=> $theme_slug,
				'import_plugins'				=> $plugins,
				'pro_theme_name'				=> $pro_theme_name,
				'pro_theme_slug'				=> $pro_theme_slug,
			);

		}

		return $import_sites;

	}


	/**
	 * Assign menu, homepage, and blog page.
	 */
	private function assign_menu_home_blog_page() {

		// Assign menus to their locations.
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

		if ( $main_menu && $footer_menu ) {
			set_theme_mod( 'nav_menu_locations', array(
					'primary' => $main_menu->term_id,
					'footer' => $footer_menu->term_id,
				)
			);
		} else {
			if ( $main_menu ) {
				set_theme_mod( 'nav_menu_locations', array(
						'primary' => $main_menu->term_id,
					)
				);
			}	
			if ( $footer_menu ) {
				set_theme_mod( 'nav_menu_locations', array(
						'footer' => $footer_menu->term_id,
					)
				);
			}			
		}

		// Assign front page and posts page.
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );

		if ( $front_page_id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page_id->ID );
		}

		if ( $blog_page_id ) {
			update_option( 'page_for_posts', $blog_page_id->ID );
		}

		// Assign WooCommerce pages.
		$shop_page_id     = get_page_by_title( 'Shop' );
		$cart_page_id     = get_page_by_title( 'Cart' );
		$checkout_page_id = get_page_by_title( 'Checkout' );
		$account_page_id  = get_page_by_title( 'My Account' );

		if ( $shop_page_id ) {
			update_option( 'woocommerce_shop_page_id', $shop_page_id->ID );
		}

		if ( $cart_page_id ) {
			update_option( 'woocommerce_cart_page_id', $cart_page_id->ID );
		}

		if ( $checkout_page_id ) {
			update_option( 'woocommerce_checkout_page_id', $checkout_page_id->ID );
		}

		if ( $account_page_id ) {
			update_option( 'woocommerce_myaccount_page_id', $account_page_id->ID );
		}

	}


	public function get_plugin_pathto( $slug ) {
		$keys = array_keys( get_plugins() );
		foreach ( $keys as $key ) {
			if ( preg_match( '|^' . $slug . '/|', $key ) ) {
				return $key;
			}
		}
	}


	/**
	 * Activate theme.
	 */
	public function theme_to_activate( $theme_name, $theme_slug ) {

		echo '<div class="WPSS__install-theme">
		<span class="theme-install-name">' . $theme_name . '</span>';

			switch_theme( $theme_slug );

			if ( wp_get_theme()->stylesheet === $theme_slug ) {
				echo '<span class="theme-install-result"><i class="dashicons dashicons-yes"></i>' . esc_html__( 'Activated', 'starter-sites' ) . '</span>';
			} else {
				echo '<span class="theme-install-result"><i class="dashicons dashicons-no"></i>' . esc_html__( 'Could not be activated', 'starter-sites' ) . '</span>';
			}

		echo '</div>';

	}


	/**
	 * Install and activate theme.
	 */
	public function install_theme( $theme_name, $theme_slug ) {

		echo '<div class="WPSS__install-theme">
		<span class="theme-install-name">' . $theme_name . '</span>';

			echo '<div class="theme-installer-wrap">';

			$api = themes_api( 'theme_information', array(
				'slug' => $theme_slug,
			));

			require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

			$upgrader = new Theme_Upgrader( new Theme_Installer_Skin( compact('theme_name', 'theme_slug', 'api') ) );
			$upgrader->install($api->download_link);

			echo '</div>';

			switch_theme( $theme_slug );

			if ( wp_get_theme()->stylesheet === $theme_slug ) {
				echo '<span class="theme-install-result"><i class="dashicons dashicons-yes"></i>' . esc_html__( 'Activated', 'starter-sites' ) . '</span>';
			} else {
				echo '<span class="theme-install-result"><i class="dashicons dashicons-no"></i>' . esc_html__( 'Could not be activated', 'starter-sites' ) . '</span>';
			}

		echo '</div>';
	}


	/**
	 * Activate plugin.
	 */
	public function plugin_to_activate( $plugin_name, $plugin_path ) {

		echo '<div class="WPSS__install-plugin">
		<span class="plugin-install-name">' . $plugin_name . '</span>';

			activate_plugin( $plugin_path );

			if ( is_plugin_active( $plugin_path ) ) {
				echo '<span class="plugin-install-result"><i class="dashicons dashicons-yes"></i>' . esc_html__( 'Activated', 'starter-sites' ) . '</span>';
			} else {
				echo '<span class="plugin-install-result"><i class="dashicons dashicons-no"></i>' . esc_html__( 'Could not be activated', 'starter-sites' ) . '</span>';
			}

		echo '</div>';

	}


	/**
	 * Install and activate plugin.
	 */
	public function install_plugin( $plugin_name, $plugin_slug ) {

		echo '<div class="WPSS__install-plugin">
		<span class="plugin-install-name">' . $plugin_name . '</span>';

			echo '<div class="plugin-installer-wrap">';

			$api = plugins_api( 'plugin_information', array(
				'slug' => $plugin_slug,
				'fields' => array(
					'short_description' => false,
					'sections' => false,
					'requires' => false,
					'rating' => false,
					'ratings' => false,
					'downloaded' => false,
					'last_updated' => false,
					'added' => false,
					'tags' => false,
					'compatibility' => false,
					'homepage' => false,
					'donate_link' => false,
				),
			));

			// needed for Plugin_Upgrader and Plugin_Installer_Skin
			require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

			$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin( compact('plugin_name','plugin_slug','api') ) );
			$upgrader->install($api->download_link);

			echo '</div>';

			$plugin_pathto = $this->get_plugin_pathto( $plugin_slug );

			activate_plugin( $plugin_pathto );

			if ( is_plugin_active( $plugin_pathto ) ) {
				echo '<span class="plugin-install-result"><i class="dashicons dashicons-yes"></i>' . esc_html__( 'Activated', 'starter-sites' ) . '</span>';
			} else {
				echo '<span class="plugin-install-result"><i class="dashicons dashicons-no"></i>' . esc_html__( 'Could not be activated', 'starter-sites' ) . '</span>';
			}

		echo '</div>';
	}

}

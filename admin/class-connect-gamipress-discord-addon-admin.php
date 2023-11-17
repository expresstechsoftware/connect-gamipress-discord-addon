<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Gamipress_Discord_Addon
 * @subpackage Connect_Gamipress_Discord_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Connect_Gamipress_Discord_Addon
 * @subpackage Connect_Gamipress_Discord_Addon/admin
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Connect_Gamipress_Discord_Addon_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance of Connect_Gamipress_Discord_Addon_Public class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Connect_Gamipress_Discord_Addon_Public
	 */
	private $gamipress_discord_public_instance;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $gamipress_discord_public_instance ) {

		$this->plugin_name                       = $plugin_name;
		$this->version                           = $version;
		$this->gamipress_discord_public_instance = $gamipress_discord_public_instance;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Gamipress_Discord_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Gamipress_Discord_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$min_css = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '' : '.min';
		wp_register_style( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'css/select2.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . 'discord_tabs_css', plugin_dir_url( __FILE__ ) . 'css/skeletabs.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/connect-gamipress-discord-addon-admin' . $min_css . '.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Gamipress_Discord_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Gamipress_Discord_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $pagenow;
		if ( $pagenow === 'profile.php' || $pagenow === 'user-edit.php' ) {
			$this->gamipress_discord_public_instance->enqueue_scripts();
			return;
		}

		wp_register_script( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'js/select2.js', array( 'jquery' ), $this->version, false );

		wp_register_script( $this->plugin_name . '-tabs-js', plugin_dir_url( __FILE__ ) . 'js/skeletabs.js', array( 'jquery' ), $this->version, false );
		$min_js = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '' : '.min';
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/connect-gamipress-discord-addon-admin' . $min_js . '.js', array( 'jquery' ), $this->version, false );
		$script_params = array(
			'admin_ajax'                  => admin_url( 'admin-ajax.php' ),
			'permissions_const'           => CONNECT_GAMIPRESS_DISCORD_OAUTH_SCOPES,
			'is_admin'                    => is_admin(),
			'ets_gamipress_discord_nonce' => wp_create_nonce( 'ets-gamipress-discord-ajax-nonce' ),
		);
		wp_localize_script( $this->plugin_name, 'etsGamiPressParams', $script_params );

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/connect-gamipress-discord-addon-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Method to add discord setting sub-menu under top level menu of Gamipress
	 *
	 * @since    1.0.0
	 */
	public function ets_gamipress_Discord_add_settings_menu() {
		add_submenu_page( 'gamipress', __( 'Discord Settings', 'connect-gamipress-discord-addon' ), __( 'Discord Settings', 'connect-gamipress-discord-addon' ), 'manage_options', 'connect-gamipress-discord-addon', array( $this, 'ets_gamipress_discord_setting_page' ) );
	}

	/**
	 * Callback to Display settings page
	 *
	 * @since    1.0.0
	 */
	public function ets_gamipress_discord_setting_page() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		wp_enqueue_style( $this->plugin_name . '-select2' );
		wp_enqueue_style( $this->plugin_name . 'discord_tabs_css' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name . '-select2' );
		wp_enqueue_script( $this->plugin_name . '-tabs-js' );
		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'wp-color-picker' );
		require_once CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/connect-gamipress-discord-addon-admin-display.php';
	}

	/**
	 * Save application details
	 *
	 * @since    1.0.0
	 * @return NONE
	 */
	public function ets_gamipress_discord_application_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$ets_gamipress_discord_client_id = isset( $_POST['ets_gamipress_discord_client_id'] ) ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_client_id'] ) ) : '';

		$ets_gamipress_discord_client_secret = isset( $_POST['ets_gamipress_discord_client_secret'] ) ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_client_secret'] ) ) : '';

		$ets_gamipress_discord_bot_token = isset( $_POST['ets_gamipress_discord_bot_token'] ) ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_bot_token'] ) ) : '';

		$ets_gamipress_discord_redirect_url = isset( $_POST['ets_gamipress_discord_redirect_url'] ) ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_redirect_url'] ) ) : '';

		$ets_gamipress_discord_redirect_page_id = isset( $_POST['ets_gamipress_discord_redirect_page_id'] ) ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_redirect_page_id'] ) ) : '';

		$ets_gamipress_discord_admin_redirect_url = isset( $_POST['ets_gamipress_discord_admin_redirect_url'] ) ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_admin_redirect_url'] ) ) : '';

		$ets_gamipress_discord_server_id = isset( $_POST['ets_gamipress_discord_server_id'] ) ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_server_id'] ) ) : '';

		$ets_current_url = sanitize_text_field( trim( $_POST['current_url'] ) );

		if ( isset( $_POST['submit'] ) ) {
			if ( isset( $_POST['ets_gamipress_discord_save_settings'] ) && wp_verify_nonce( $_POST['ets_gamipress_discord_save_settings'], 'save_gamipress_discord_general_settings' ) ) {
				if ( $ets_gamipress_discord_client_id ) {
					update_option( 'ets_gamipress_discord_client_id', $ets_gamipress_discord_client_id );
				}

				if ( $ets_gamipress_discord_client_secret ) {
					update_option( 'ets_gamipress_discord_client_secret', $ets_gamipress_discord_client_secret );
				}

				if ( $ets_gamipress_discord_bot_token ) {
					update_option( 'ets_gamipress_discord_bot_token', $ets_gamipress_discord_bot_token );
				}

				if ( $ets_gamipress_discord_redirect_url ) {
					update_option( 'ets_gamipress_discord_redirect_page_id', $ets_gamipress_discord_redirect_url );
					$ets_gamipress_discord_redirect_url = ets_get_gamipress_discord_formated_discord_redirect_url( $ets_gamipress_discord_redirect_url );
					update_option( 'ets_gamipress_discord_redirect_url', $ets_gamipress_discord_redirect_url );

				}

				if ( $ets_gamipress_discord_server_id ) {
					update_option( 'ets_gamipress_discord_server_id', $ets_gamipress_discord_server_id );
				}
				if ( $ets_gamipress_discord_admin_redirect_url ) {
					update_option( 'ets_gamipress_discord_admin_redirect_url', $ets_gamipress_discord_admin_redirect_url );
				}
				/**
								 * Call function to save bot name option
				 */
				ets_gamipress_discord_update_bot_name_option();

				$message = esc_html__( 'Your settings are saved successfully.', 'connect-gamipress-discord-addon' );

				$pre_location = $ets_current_url . '&save_settings_msg=' . $message . '#ets_gamipress_application_details';
				wp_safe_redirect( $pre_location );
			}
		}
	}

	/**
	 * Method to catch the admin BOT connect action
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_gamipress_discord_connect_bot() {
		if ( isset( $_GET['action'] ) && 'gamipress-discord-connect-to-bot' === $_GET['action'] ) {
			if ( ! current_user_can( 'administrator' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
			}
			$params                    = array(
				'client_id'            => sanitize_text_field( trim( get_option( 'ets_gamipress_discord_client_id' ) ) ),
				'permissions'          => CONNECT_GAMIPRESS_DISCORD_BOT_PERMISSIONS,
				'response_type'        => 'code',
				'scope'                => 'bot',
				'guild_id'             => sanitize_text_field( trim( get_option( 'ets_gamipress_discord_server_id' ) ) ),
				'disable_guild_select' => 'true',
				'redirect_uri'         => sanitize_text_field( trim( get_option( 'ets_gamipress_discord_admin_redirect_url' ) ) ),
			);
			$discord_authorise_api_url = CONNECT_GAMIPRESS_API_URL . 'oauth2/authorize?' . http_build_query( $params );

			wp_redirect( $discord_authorise_api_url, 302, get_site_url() );
			exit;
		}
	}

	/**
	 * Load discord roles from server
	 *
	 * @return OBJECT REST API response
	 */
	public function ets_gamipress_discord_load_discord_roles() {

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_gamipress_discord_nonce'], 'ets-gamipress-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$user_id = get_current_user_id();

		$guild_id          = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_server_id' ) ) );
		$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_bot_token' ) ) );
		$client_id         = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_client_id' ) ) );
		if ( $guild_id && $discord_bot_token ) {
			$discod_server_roles_api = CONNECT_GAMIPRESS_API_URL . 'guilds/' . $guild_id . '/roles';
			$guild_args              = array(
				'method'  => 'GET',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bot ' . $discord_bot_token,
				),
			);
			$guild_response          = wp_remote_post( $discod_server_roles_api, $guild_args );

			ets_gamipress_discord_log_api_response( $user_id, $discod_server_roles_api, $guild_args, $guild_response );

			$response_arr = json_decode( wp_remote_retrieve_body( $guild_response ), true );

			if ( is_array( $response_arr ) && ! empty( $response_arr ) ) {
				if ( array_key_exists( 'code', $response_arr ) || array_key_exists( 'error', $response_arr ) ) {
					Connect_Gamipress_Discord_Add_On_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				} else {
					$response_arr['previous_mapping'] = get_option( 'ets_gamipress_discord_role_mapping' );

					$discord_roles = array();
					foreach ( $response_arr as $key => $value ) {
						$isbot = false;
						if ( is_array( $value ) ) {
							if ( array_key_exists( 'tags', $value ) ) {
								if ( array_key_exists( 'bot_id', $value['tags'] ) ) {
									$isbot = true;
									if ( $value['tags']['bot_id'] === $client_id ) {
										$response_arr['bot_connected'] = 'yes';
									}
								}
							}
						}
						if ( $key != 'previous_mapping' && $isbot == false && isset( $value['name'] ) && $value['name'] != '@everyone' ) {
							$discord_roles[ $value['id'] ]       = $value['name'];
							$discord_roles_color[ $value['id'] ] = $value['color'];
						}
					}
					update_option( 'ets_gamipress_discord_all_roles', serialize( $discord_roles ) );
					update_option( 'ets_gamipress_discord_roles_color', serialize( $discord_roles_color ) );
				}
			}
				return wp_send_json( $response_arr );
		}

				exit();

	}

	/**
	 * Save Role mapping settings
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_gamipress_discord_save_role_mapping() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$ets_discord_roles = isset( $_POST['ets_gamipress_discord_role_mapping'] ) ? sanitize_textarea_field( trim( $_POST['ets_gamipress_discord_role_mapping'] ) ) : '';

		$ets_gamipress_discord_default_role_id = isset( $_POST['gamipress_defaultRole'] ) ? sanitize_textarea_field( trim( $_POST['gamipress_defaultRole'] ) ) : '';
		$ets_discord_roles                     = stripslashes( $ets_discord_roles );
		$save_mapping_status                   = update_option( 'ets_gamipress_discord_role_mapping', $ets_discord_roles );
		$ets_current_url                       = sanitize_text_field( trim( $_POST['current_url'] ) );
		if ( isset( $_POST['ets_gamipress_discord_role_mappings_nonce'] ) && wp_verify_nonce( $_POST['ets_gamipress_discord_role_mappings_nonce'], 'gamipress_discord_role_mappings_nonce' ) ) {
			if ( ( $save_mapping_status || isset( $_POST['ets_gamipress_discord_role_mapping'] ) ) && ! isset( $_POST['flush'] ) ) {
				if ( $ets_gamipress_discord_default_role_id ) {
					update_option( 'ets_gamipress_discord_default_role_id', $ets_gamipress_discord_default_role_id );
				}

				$message = esc_html__( 'Your mappings are saved successfully.', 'connect-gamipress-discord-addon' );
			}
			if ( isset( $_POST['flush'] ) ) {
				delete_option( 'ets_gamipress_discord_role_mapping' );
				delete_option( 'ets_gamipress_discord_default_role_id' );

				$message = esc_html__( 'Your settings flushed successfully.', 'connect-gamipress-discord-addon' );
			}
			$pre_location = $ets_current_url . '&save_settings_msg=' . $message . '#ets_gamipress_discord_role_mapping';
			wp_safe_redirect( $pre_location );
		}
	}

	/**
	 * Update redirect url
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_gamipress_discord_update_redirect_url() {

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_gamipress_discord_nonce'], 'ets-gamipress-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		$page_id = sanitize_text_field( $_POST['ets_gamipress_page_id'] );
		if ( isset( $page_id ) ) {
			$formated_discord_redirect_url = ets_get_gamipress_discord_formated_discord_redirect_url( $page_id );
			update_option( 'ets_gamipress_discord_redirect_page_id', $page_id );
			update_option( 'ets_gamipress_discord_redirect_url', $formated_discord_redirect_url );
			$res = array(
				'formated_discord_redirect_url' => $formated_discord_redirect_url,
			);
			wp_send_json( $res );

		}
		exit();

	}

	/**
	 * Save advanced settings
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_gamipress_discord_save_advance_settings() {

		if ( ! current_user_can( 'administrator' ) || ! wp_verify_nonce( $_POST['ets_gamipress_discord_advance_settings_nonce'], 'gamipress_discord_advance_settings_nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

			$ets_gamipress_discord_send_welcome_dm            = isset( $_POST['ets_gamipress_discord_send_welcome_dm'] ) ? sanitize_textarea_field( trim( $_POST['ets_gamipress_discord_send_welcome_dm'] ) ) : '';
			$ets_gamipress_discord_welcome_message            = isset( $_POST['ets_gamipress_discord_welcome_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_gamipress_discord_welcome_message'] ) ) : '';
			$ets_gamipress_discord_award_rank_message         = isset( $_POST['ets_gamipress_discord_award_rank_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_gamipress_discord_award_rank_message'] ) ) : '';
			$ets_gamipress_discord_award_user_points_message  = isset( $_POST['ets_gamipress_discord_award_user_points_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_gamipress_discord_award_user_points_message'] ) ) : '';
			$ets_gamipress_discord_deduct_user_points_message = isset( $_POST['ets_gamipress_discord_deduct_user_points_message'] ) ? sanitize_textarea_field( trim( $_POST['ets_gamipress_discord_deduct_user_points_message'] ) ) : '';

			$retry_failed_api     = isset( $_POST['retry_failed_api'] ) ? sanitize_textarea_field( trim( $_POST['retry_failed_api'] ) ) : '';
			$kick_upon_disconnect = isset( $_POST['kick_upon_disconnect'] ) ? sanitize_textarea_field( trim( $_POST['kick_upon_disconnect'] ) ) : '';
			$retry_api_count      = isset( $_POST['ets_gamipress_retry_api_count'] ) ? sanitize_textarea_field( trim( $_POST['ets_gamipress_retry_api_count'] ) ) : '';
			$set_job_cnrc         = isset( $_POST['set_job_cnrc'] ) ? sanitize_textarea_field( trim( $_POST['set_job_cnrc'] ) ) : '';
			$set_job_q_batch_size = isset( $_POST['set_job_q_batch_size'] ) ? sanitize_textarea_field( trim( $_POST['set_job_q_batch_size'] ) ) : '';
			$log_api_res          = isset( $_POST['log_api_res'] ) ? sanitize_textarea_field( trim( $_POST['log_api_res'] ) ) : '';
			$ets_current_url      = sanitize_text_field( trim( $_POST['current_url'] ) );

		if ( isset( $_POST['ets_gamipress_discord_advance_settings_nonce'] ) && wp_verify_nonce( $_POST['ets_gamipress_discord_advance_settings_nonce'], 'gamipress_discord_advance_settings_nonce' ) ) {
			if ( isset( $_POST['adv_submit'] ) ) {

				if ( isset( $_POST['ets_gamipress_discord_send_welcome_dm'] ) ) {
					update_option( 'ets_gamipress_discord_send_welcome_dm', true );
				} else {
					update_option( 'ets_gamipress_discord_send_welcome_dm', false );
				}
				if ( isset( $_POST['ets_gamipress_discord_welcome_message'] ) && $_POST['ets_gamipress_discord_welcome_message'] != '' ) {
					update_option( 'ets_gamipress_discord_welcome_message', $ets_gamipress_discord_welcome_message );
				} else {
					update_option( 'ets_gamipress_discord_welcome_message', '' );
				}

				if ( isset( $_POST['ets_gamipress_discord_send_award_rank_dm'] ) ) {
					update_option( 'ets_gamipress_discord_send_award_rank_dm', true );
				} else {
					update_option( 'ets_gamipress_discord_send_award_rank_dm', false );
				}
				if ( isset( $_POST['ets_gamipress_discord_award_rank_message'] ) && $_POST['ets_gamipress_discord_award_rank_message'] != '' ) {
					update_option( 'ets_gamipress_discord_award_rank_message', $ets_gamipress_discord_award_rank_message );
				} else {
					update_option( 'ets_gamipress_discord_award_rank_message', '' );
				}

				if ( isset( $_POST['ets_gamipress_discord_send_award_user_points_dm'] ) ) {
					update_option( 'ets_gamipress_discord_send_award_user_points_dm', true );
				} else {
					update_option( 'ets_gamipress_discord_send_award_user_points_dm', false );
				}
				if ( isset( $_POST['ets_gamipress_discord_award_user_points_message'] ) && $_POST['ets_gamipress_discord_award_user_points_message'] != '' ) {
					update_option( 'ets_gamipress_discord_award_user_points_message', $ets_gamipress_discord_award_user_points_message );
				} else {
					update_option( 'ets_gamipress_discord_award_user_points_message', '' );
				}

				if ( isset( $_POST['ets_gamipress_discord_send_deduct_user_points_dm'] ) ) {
					update_option( 'ets_gamipress_discord_send_deduct_user_points_dm', true );
				} else {
					update_option( 'ets_gamipress_discord_send_deduct_user_points_dm', false );
				}
				if ( isset( $_POST['ets_gamipress_discord_deduct_user_points_message'] ) && $_POST['ets_gamipress_discord_deduct_user_points_message'] != '' ) {
					update_option( 'ets_gamipress_discord_deduct_user_points_message', $ets_gamipress_discord_deduct_user_points_message );
				} else {
					update_option( 'ets_gamipress_discord_deduct_user_points_message', '' );
				}

				if ( isset( $_POST['retry_failed_api'] ) ) {
					update_option( 'ets_gamipress_discord_retry_failed_api', true );
				} else {
					update_option( 'ets_gamipress_discord_retry_failed_api', false );
				}
				if ( isset( $_POST['kick_upon_disconnect'] ) ) {
					update_option( 'ets_gamipress_discord_kick_upon_disconnect', true );
				} else {
					update_option( 'ets_gamipress_discord_kick_upon_disconnect', false );
				}
				if ( isset( $_POST['ets_gamipress_retry_api_count'] ) ) {
					if ( $retry_api_count < 1 ) {
						update_option( 'ets_gamipress_discord_retry_api_count', 1 );
					} else {
						update_option( 'ets_gamipress_discord_retry_api_count', $retry_api_count );
					}
				}
				if ( isset( $_POST['set_job_cnrc'] ) ) {
					if ( $set_job_cnrc < 1 ) {
						update_option( 'ets_gamipress_discord_job_queue_concurrency', 1 );
					} else {
						update_option( 'ets_gamipress_discord_job_queue_concurrency', $set_job_cnrc );
					}
				}
				if ( isset( $_POST['set_job_q_batch_size'] ) ) {
					if ( $set_job_q_batch_size < 1 ) {
						update_option( 'ets_gamipress_discord_job_queue_batch_size', 1 );
					} else {
						update_option( 'ets_gamipress_discord_job_queue_batch_size', $set_job_q_batch_size );
					}
				}
				if ( isset( $_POST['log_api_res'] ) ) {
					update_option( 'ets_gamipress_discord_log_api_response', true );
				} else {
					update_option( 'ets_gamipress_discord_log_api_response', false );
				}

				$message      = esc_html__( 'Your settings are saved successfully.', 'connect-gamipress-discord-addon' );
				$pre_location = $ets_current_url . '&save_settings_msg=' . esc_html( $message ) . '#ets_gamipress_discord_advanced';
				wp_safe_redirect( $pre_location );

			}
		}

	}

	/**
	 * Save apearance settings
	 *
	 * @param NONE
	 * @return NONE
	 */
	public function ets_gamipress_discord_save_appearance_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		$ets_gamipress_discord_connect_button_bg_color    = isset( $_POST['ets_gamipress_discord_connect_button_bg_color'] ) && $_POST['ets_gamipress_discord_connect_button_bg_color'] !== '' ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_connect_button_bg_color'] ) ) : '#77a02e';
		$ets_gamipress_discord_disconnect_button_bg_color = isset( $_POST['ets_gamipress_discord_disconnect_button_bg_color'] ) && $_POST['ets_gamipress_discord_disconnect_button_bg_color'] != '' ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_disconnect_button_bg_color'] ) ) : '#ff0000';
		$ets_gamipress_loggedin_btn_text                  = isset( $_POST['ets_gamipress_loggedin_btn_text'] ) && $_POST['ets_gamipress_loggedin_btn_text'] != '' ? sanitize_text_field( trim( $_POST['ets_gamipress_loggedin_btn_text'] ) ) : 'Connect To Discord';
		$ets_gamipress_loggedout_btn_text                 = isset( $_POST['ets_gamipress_loggedout_btn_text'] ) && $_POST['ets_gamipress_loggedout_btn_text'] != '' ? sanitize_text_field( trim( $_POST['ets_gamipress_loggedout_btn_text'] ) ) : 'Login With Discord';
		$ets_gamipress_discord_disconnect_btn_text        = $_POST['ets_gamipress_discord_disconnect_btn_text'] ? sanitize_text_field( trim( $_POST['ets_gamipress_discord_disconnect_btn_text'] ) ) : 'Disconnect From Discord';

		if ( isset( $_POST['appearance_submit'] ) ) {

			if ( isset( $_POST['ets_gamipress_discord_save_appearance_settings'] ) && wp_verify_nonce( $_POST['ets_gamipress_discord_save_appearance_settings'], 'save_ets_gamipress_discord_appearance_settings' ) ) {
				if ( $ets_gamipress_discord_connect_button_bg_color ) {
					update_option( 'ets_gamipress_discord_connect_button_bg_color', $ets_gamipress_discord_connect_button_bg_color );
				}
				if ( $ets_gamipress_discord_disconnect_button_bg_color ) {
					update_option( 'ets_gamipress_discord_disconnect_button_bg_color', $ets_gamipress_discord_disconnect_button_bg_color );
				}
				if ( $ets_gamipress_loggedout_btn_text ) {
					update_option( 'ets_gamipress_discord_loggedout_btn_text', $ets_gamipress_loggedout_btn_text );
				}
				if ( $ets_gamipress_loggedin_btn_text ) {
					update_option( 'ets_gamipress_discord_loggedin_button_text', $ets_gamipress_loggedin_btn_text );
				}
				if ( $ets_gamipress_discord_disconnect_btn_text ) {
					update_option( 'ets_gamipress_discord_disconnect_button_text', $ets_gamipress_discord_disconnect_btn_text );
				}
				$message = esc_html__( 'Your settings are saved successfully.', 'connect-gamipress-discord-addon' );
				if ( isset( $_POST['current_url'] ) ) {
					$pre_location = sanitize_text_field( $_POST['current_url'] ) . '&save_settings_msg=' . $message . '#ets_gamipress_discord_appearance';
					wp_safe_redirect( $pre_location );
				}
			}
		}

	}


	/**
	 * Add GamiPress Discord Connection column to WP Users listing
	 *
	 * @param array $columns
	 */
	public function ets_gamipress_discord_add_disconnect_discord_column( $columns ) {

		$columns['ets_gamipress_disconnect_discord_connection'] = esc_html__( 'GamiPress Discord Connection', 'connect-gamipress-discord-addon' );
		return $columns;
	}

	/**
	 * Display Discord Disconnect button
	 *
	 * @param string $value Custom column output.
	 * @param string $column_name Column name.
	 * @param int    $user_id ID of the currently-listed user.
	 */
	public function ets_gamipress_discord_disconnect_discord_button( $value, $column_name, $user_id ) {

		if ( $column_name === 'ets_gamipress_disconnect_discord_connection' ) {
			wp_enqueue_script( $this->plugin_name );
			$access_token                    = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_gamipress_discord_access_token', true ) ) );
			$_ets_gamipress_discord_username = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_gamipress_discord_username', true ) ) );
			if ( $access_token ) {
				return '<button  data-user-id="' . esc_attr( $user_id ) . '" class="ets-gamipress-disconnect-discord-user" >' . esc_html__( 'Disconnect from discord ', 'connect-gamipress-discord-addon' ) . ' <i class="fab fa-discord"></i> <span class="spinner"></span> </button><p>' . esc_html__( sprintf( 'Connected account: %s', $_ets_gamipress_discord_username ), 'connect-gamipress-discord-addon' ) . '</p>';
			}
			return esc_html__( 'Not Connected', 'connect-gamipress-discord-addon' );
		}
		return $value;
	}

	/**
	 * Disconnect user from Discord.
	 */
	public function ets_gamipress_discord_disconnect_user() {

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_gamipress_discord_nonce'], 'ets-gamipress-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		$user_id              = sanitize_text_field( trim( $_POST['ets_gamipress_discord_user_id'] ) );
		$kick_upon_disconnect = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_kick_upon_disconnect' ) ) );
		$access_token         = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_gamipress_discord_access_token', true ) ) );
		$refresh_token        = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_gamipress_discord_refresh_token', true ) ) );
		if ( $user_id && $access_token && $refresh_token ) {
			delete_user_meta( $user_id, '_ets_gamipress_discord_access_token' );
			delete_user_meta( $user_id, '_ets_gamipress_discord_refresh_token' );
			$user_roles = ets_gamipress_discord_get_user_roles( $user_id );
			if ( $kick_upon_disconnect ) {

				if ( is_array( $user_roles ) ) {
					foreach ( $user_roles as $user_role ) {
						$this->gamipress_discord_public_instance->delete_discord_role( $user_id, $user_role );
					}
				}
			} else {
				$this->gamipress_discord_public_instance->delete_member_from_guild( $user_id, false );
			}
			$event_res = array(
				'status'  => 1,
				'message' => 'Successfully disconnected',
			);
			wp_send_json( $event_res );
			exit();
		}
		exit();
	}

	/**
	 * Send support message.
	 */
	public function ets_gamipress_discord_send_support_mail() {

		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		if ( isset( $_POST['support_mail_submit'] ) ) {

			// Check for nonce security
			if ( ! wp_verify_nonce( $_POST['ets_discord_send_support_mail'], 'send_support_mail' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
			}
			$etsUserName  = isset( $_POST['ets_user_name'] ) ? sanitize_text_field( trim( $_POST['ets_user_name'] ) ) : '';
			$etsUserEmail = isset( $_POST['ets_user_email'] ) ? sanitize_text_field( trim( $_POST['ets_user_email'] ) ) : '';
			$message      = isset( $_POST['ets_support_msg'] ) ? sanitize_text_field( trim( $_POST['ets_support_msg'] ) ) : '';
			$sub          = isset( $_POST['ets_support_subject'] ) ? sanitize_text_field( trim( $_POST['ets_support_subject'] ) ) : '';

			if ( $etsUserName && $etsUserEmail && $message && $sub ) {

				$subject   = $sub;
				$to        = array(
					'contact@expresstechsoftwares.com',
					'vinod.tiwari@expresstechsoftwares.com',
				);
				$content   = 'Name: ' . $etsUserName . '<br>';
				$content  .= 'Contact Email: ' . $etsUserEmail . '<br>';
				$content  .= 'GamiPress Support Message: ' . $message;
				$headers   = array();
				$blogemail = get_bloginfo( 'admin_email' );
				$headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . $blogemail . '>' . "\r\n";
				$mail      = wp_mail( $to, $subject, $content, $headers );

				if ( $mail ) {
					$message = esc_html__( 'Your request have been successfully submitted!', 'connect-gamipress-discord-addon' );
				} else {
					$message = esc_html__( 'failure to send email!', 'connect-gamipress-discord-addon' );
				}
				if ( isset( $_POST['current_url'] ) ) {
					$pre_location = sanitize_text_field( $_POST['current_url'] ) . '&save_settings_msg=' . $message . '#ets_gamipress_discord_support';
					wp_safe_redirect( $pre_location );
				}
			}
		}
	}

	/**
	 * Send DM message when Admin deduct points to a user.
	 *
	 * @param integer        $user_id        The given user's ID.
	 * @param integer        $points         The points the user is being awarded.
	 * @param string|WP_Post $points_type    The points type.
	 * @param array          $args           Array of extra arguments.
	 */
	public function ets_gamipress_deduct_points_to_user( $user_id, $points, $points_type, $args ) {

		// update_option( 'gamipress_revoke_user_points_' . time(), ' user_id : ' . $user_id . '  points : ' . $points . ' points_type : ' . $points_type . ' raison : ' . $args['reason'] . ' achievement_id :' . $args['achievement_id'] );
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}

		if ( isset( $user_id ) ) {
			$access_token                                     = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_gamipress_discord_access_token', true ) ) );
			$ets_gamipress_discord_send_deduct_user_points_dm = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_send_deduct_user_points_dm' ) ) );
			if ( $access_token && $ets_gamipress_discord_send_deduct_user_points_dm == true ) {
				as_schedule_single_action( ets_gamipress_discord_get_random_timestamp( ets_gamipress_discord_get_highest_last_attempt_timestamp() ), 'ets_gamipress_discord_as_send_dm', array( $user_id, $points_type, 'deduct_points', $points ), GAMIPRESS_DISCORD_AS_GROUP_NAME );
			}
		}
	}


	/**
	 *
	 * Update user meta notification
	 *
	 * @since 1.0.4
	 */
	public function ets_gamipress_discord_notice_dismiss() {

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}

		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_gamipress_discord_nonce'], 'ets-gamipress-discord-ajax-nonce' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
		}

		update_user_meta( get_current_user_id(), '_ets_gamipress_discord_dismissed_notification', true );
		$event_res = array(
			'status'  => 1,
			'message' => __( 'success', 'connect-gamipress-discord-addon' ),
		);
		return wp_send_json( $event_res );

		exit();
	}

}

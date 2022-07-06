<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Gamipress_Discord_Addon
 * @subpackage Connect_Gamipress_Discord_Addon/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Connect_Gamipress_Discord_Addon
 * @subpackage Connect_Gamipress_Discord_Addon/includes
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Connect_Gamipress_Discord_Addon_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option( 'ets_gamipress_discord_send_welcome_dm', true );
		update_option( 'ets_gamipress_discord_welcome_message', 'Hi [GP_USER_NAME] ([GP_USER_EMAIL]), Welcome, Your ranks [GP_RANKS] at [SITE_URL] Thanks, Kind Regards, [BLOG_NAME]' );                
		update_option( 'ets_gamipress_discord_retry_failed_api', true );
		update_option( 'ets_gamipress_discord_connect_button_bg_color', '#7bbc36' );
		update_option( 'ets_gamipress_discord_disconnect_button_bg_color', '#ff0000' );
		update_option( 'ets_gamipress_discord_loggedin_button_text', 'Connect With Discord' );
		update_option( 'ets_gamipress_discord_non_login_button_text', 'Login With Discord' );
		update_option( 'ets_gamipress_discord_disconnect_button_text', 'Disconnect From Discord' );
		update_option( 'ets_gamipress_discord_kick_upon_disconnect', false ); 
		update_option( 'ets_gamipress_discord_retry_api_count', 5 );
		update_option( 'ets_gamipress_discord_job_queue_concurrency', 1 );
		update_option( 'ets_gamipress_discord_job_queue_batch_size', 6 );
		update_option( 'ets_gamipress_discord_log_api_response', false );
		update_option( 'ets_gamipress_discord_uuid_file_name', wp_generate_uuid4() );
	}

}

<?php
/*
* common functions file.
*/

/**
 * To check settings values saved or not
 *
 * @param NONE
 * @return BOOL $status
 */
function gamipress_discord_check_saved_settings_status() {
	$ets_gamipress_discord_client_id     = sanitize_text_field ( trim ( get_option( 'ets_gamipress_discord_client_id' ) ) );
	$ets_gamipress_discord_client_secret = sanitize_text_field ( trim ( get_option( 'ets_gamipress_discord_client_secret' ) ) );
	$ets_gamipress_discord_bot_token     = sanitize_text_field ( trim ( get_option( 'ets_gamipress_discord_bot_token' ) ) );
	$ets_gamipress_discord_redirect_url  = sanitize_text_field ( trim ( get_option( 'ets_gamipress_discord_redirect_url' ) ) );
	$ets_gamipress_discord_server_id      = sanitize_text_field ( trim ( get_option( 'ets_gamipress_discord_server_id' ) ) );

	if ( $ets_gamipress_discord_client_id && $ets_gamipress_discord_client_secret && $ets_gamipress_discord_bot_token && $ets_gamipress_discord_redirect_url && $ets_gamipress_discord_server_id ) {
			$status = true;
	} else {
			 $status = false;
	}

		 return $status;
}

/**
 * Get current screen URL
 *
 * @param NONE
 * @return STRING $url
 */
function ets_gamipress_discord_get_current_screen_url() {
	$parts           = parse_url( home_url() );
	$current_uri = "{$parts['scheme']}://{$parts['host']}" . ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) . add_query_arg( null, null );
	
        return $current_uri;
}

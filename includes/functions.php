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

 /*
 * Get BOT name
 * 
 * @param NONE
 * @return NONE 
 */
function ets_gamipress_discord_update_bot_name_option ( ){
 
	$guild_id          = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_server_id' ) ) );
	$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_bot_token' ) ) );
	if ( $guild_id && $discord_bot_token ) {
            
                $discod_current_user_api = CONNECT_GAMIPRESS_API_URL . 'users/@me';
                
		$app_args              = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
		);                
                
		$app_response = wp_remote_post( $discod_current_user_api, $app_args );

		$response_arr =  json_decode ( wp_remote_retrieve_body( $app_response ), true );
                
		if( is_array( $response_arr ) && array_key_exists( 'username', $response_arr ) ){
                    
			update_option( 'ets_gamipress_discord_connected_bot_name', $response_arr ['username'] );
		}else{
			delete_option( 'ets_gamipress_discord_connected_bot_name' );
                }
                        
                
	}

}

 /**
 * Get WP Pages list
 * @param INT $ets_gamipress_discord_redirect_page_id
 * @return STRING $options
 */
function ets_gamipress_discord_pages_list( $ets_gamipress_discord_redirect_page_id ){
	$args = array(
		'sort_order' => 'asc',
		'sort_column' => 'post_title',
		'hierarchical' => 1,
		'exclude' => '',
		'include' => '',
		'meta_key' => '',
		'meta_value' => '',
		'exclude_tree' => '',
		'number' => '',
		'offset' => 0,
		'post_type' => 'page',
		'post_status' => 'publish'
	); 
	$pages = get_pages( $args );
	$options = '<option value="">-</option>';
	foreach( $pages as $page ){ 
		$selected = ( esc_attr( $page->ID ) === $ets_gamipress_discord_redirect_page_id  ) ? ' selected="selected"' : '';
		$options .= '<option data-page-url="' . ets_get_gamipress_discord_formated_discord_redirect_url ( $page->ID ) .'" value="' . esc_attr( $page->ID ) . '" '. $selected .'> ' . $page->post_title . ' </option>';
	}
    
	return $options;
}

/*
 * function to get formated redirect url
 * @param INT $page_id
 * @return STRING $url
 */
function ets_get_gamipress_discord_formated_discord_redirect_url( $page_id ) {
	$url = esc_url( get_permalink( $page_id ) );
    
	$parsed = parse_url( $url, PHP_URL_QUERY );
	if ( $parsed === null ) {
		return $url .= '?via=connect-gamipress-discord-addon';
	} else {
		if ( stristr( $url, 'via=connect-gamipress-discord-addon' ) !== false ) {
			return $url;
		} else {
			return $url .= '&via=connect-gamipress-discord-addon';
		}
	}
}

  /**
   * Log API call response
   *
   * @param INT          $user_id
   * @param STRING       $api_url
   * @param ARRAY        $api_args
   * @param ARRAY|OBJECT $api_response
   */
function ets_gamipress_discord_log_api_response( $user_id, $api_url = '', $api_args = array(), $api_response = '' ) {
	$log_api_response = get_option( 'ets_learndash_discord_log_api_response' );
	if ( $log_api_response == true ) {
		$log_string  = '==>' . $api_url;
		$log_string .= '-::-' . serialize( $api_args );
		$log_string .= '-::-' . serialize( $api_response );

		$logs = new Connect_Gamipress_Discord_Add_On_Logs;
		$logs->write_api_response_logs( $log_string, $user_id );
	}
}

  /**
   * Get GamiPress published ranks
   *
   * @return ARRAY|NULL 
   */
function ets_gamipress_discord_get_ranks(  ) {
	$ets_gamipress_ranks = array();
	$rank_types = gamipress_get_rank_types();
	foreach( $rank_types as $rank_type => $data ) :
		global $wpdb;
		$ranks = $wpdb->prepare(
			"SELECT p.ID, p.post_title
			FROM wp_posts AS p
			WHERE p.post_type = %s
			 AND p.post_status = %s
			ORDER BY menu_order ASC
			",
			$rank_type,
			'publish'
		);
		$ranks_result = $wpdb->get_results( $ranks, ARRAY_A );
		if( is_array( $ranks_result ) && count( $ranks_result ) ) {
			foreach( $ranks_result as $rank_result ) {
				$ets_gamipress_ranks[ $rank_result['ID'] ] = $rank_result['post_title'];
                
			}
		}              
	endforeach;
	if( is_array( $ets_gamipress_ranks )  ){
		return $ets_gamipress_ranks;
	} else {
		return null;    
	}
}

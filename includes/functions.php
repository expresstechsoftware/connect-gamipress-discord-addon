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
	$log_api_response = get_option( 'ets_gamipress_discord_log_api_response' );
	if ( $log_api_response == true ) {
		$log_string  = '==>' . $api_url;
		$log_string .= '-::-' . serialize( $api_args );
		$log_string .= '-::-' . serialize( $api_response );

		$logs = new Connect_Gamipress_Discord_Add_On_Logs();
		$logs->write_api_response_logs( $log_string, $user_id );
	}
}

/**
 * Check API call response and detect conditions which can cause of action failure and retry should be attemped.
 *
 * @param ARRAY|OBJECT $api_response
 * @param BOOLEAN
 */
function ets_gamipress_discord_check_api_errors( $api_response ) {
	// check if response code is a WordPress error.
	if ( is_wp_error( $api_response ) ) {
		return true;
	}

	// First Check if response contain codes which should not get re-try.
	$body = json_decode( wp_remote_retrieve_body( $api_response ), true );
	if ( isset( $body['code'] ) && in_array( $body['code'], GAMIPRESS_DISCORD_DONOT_RETRY_THESE_API_CODES ) ) {
		return false;
	}

	$response_code = strval( $api_response['response']['code'] );
	if ( isset( $api_response['response']['code'] ) && in_array( $response_code, GAMIPRESS_DISCORD_DONOT_RETRY_HTTP_CODES ) ) {
		return false;
	}

	// check if response code is in the range of HTTP error.
	if ( ( 400 <= absint( $response_code ) ) && ( absint( $response_code ) <= 599 ) ) {
		return true;
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
                        FROM {$wpdb->prefix}posts AS p
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

  /**
   * Get User's ranks
   *
   * @return ARRAY|NULL 
   */
function ets_gamipress_discord_get_user_ranks_ids( $user_id ) {
	$ets_gamipress_user_ranks_ids = array();
	$rank_types = gamipress_get_rank_types();
	foreach( $rank_types as $rank_type => $data ) :
            
		$user_rank = gamipress_get_user_rank( $user_id, $rank_type ); 
		if( $user_rank ){
			array_push( $ets_gamipress_user_ranks_ids, $user_rank->ID );
            
		}
	endforeach;
	if( is_array( $ets_gamipress_user_ranks_ids ) && count( $ets_gamipress_user_ranks_ids ) > 0 ){
		return $ets_gamipress_user_ranks_ids;
	} else {
		return null;    
	}    
}

/*
  Get message for what role is assigned to the member.
  @param STRING $mapped_role_name
  @param STRING $default_role_name
  @param STRING $restrictcontent_discord
*/

function ets_gamipress_discord_roles_assigned_message ( $mapped_role_name, $default_role_name, $restrictcontent_discord ) {
    
	if ( $mapped_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';
					
		$restrictcontent_discord .= esc_html__( 'Following Roles will be assigned to you in Discord: ', 'connect-gamipress-discord-addon' );
		$restrictcontent_discord .=  $mapped_role_name  ;
		if ( $default_role_name ) {
			$restrictcontent_discord .=   $default_role_name  ; 
                                                
		}
					
		$restrictcontent_discord .= '</p>';
	} elseif( $default_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';
					
		$restrictcontent_discord .= esc_html__( 'Following Role will be assigned to you in Discord: ', 'connect-gamipress-discord-addon' );
		$restrictcontent_discord .= $default_role_name  ; 
					
		$restrictcontent_discord .= '</p>';
                                         
	}
	return $restrictcontent_discord;
}

/**
 * Get allowed html using Wordpress API function wp_kses
 *
 * @param STRING $html_message
 * @return STRING $html_message
 */

function ets_gamipress_discord_allowed_html( ) {
	$allowed_html = array(
		'div' => array(
			'class' => array()
		),
		'p' => array(               
			'class' => array()
		),
		'a' => array(                                
			'id' => array(),
			'data-user-id' => array(),                    
			'href' => array(), 
			'class' => array(),
			'style' => array(),                    
		),
		'label' => array(
			'class'=>array() 
		),
		'h3' => array(),            
		'span' => array(
			'class' => array()
		),
		'i' => array(
			'style' => array(),
			'class' => array()                    
		),
		'button' => array(
			'class' => array(),
			'data-user-id' => array(),
			'id' => array(),                    
		)            
	);

	return $allowed_html;
}

/**
 * Get Action data from table `actionscheduler_actions`
 *
 * @param INT $action_id
 */
function ets_gamipress_discord_as_get_action_data( $action_id ) {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.hook, aa.status, aa.args, ag.slug AS as_group FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id=ag.group_id WHERE `action_id`=%d AND ag.slug=%s', $action_id, GAMIPRESS_DISCORD_AS_GROUP_NAME ), ARRAY_A );
        
	if ( ! empty( $result ) ) {
		return $result[0];
	} else {
		return false;
	}
}

/**
 * Get how many times a hook is failed in a particular day.
 *
 * @param STRING $hook
 */
function ets_gamipress_discord_count_of_hooks_failures( $hook ) {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT count(last_attempt_gmt) as hook_failed_count FROM ' . $wpdb->prefix . 'actionscheduler_actions WHERE `hook`=%s AND status="failed" AND DATE(last_attempt_gmt) = %s', $hook, date( 'Y-m-d' ) ), ARRAY_A );
	
        if ( ! empty( $result ) ) {
		return $result['0']['hook_failed_count'];
	} else {
		return false;
	}
}

/**
 * Get randon integer between a predefined range.
 *
 * @param INT $add_upon
 */
function ets_gamipress_discord_get_random_timestamp( $add_upon = '' ) {
	if ( $add_upon != '' && $add_upon !== false ) {
		return $add_upon + random_int( 5, 15 );
	} else {
		return strtotime( 'now' ) + random_int( 5, 15 );
	}
}

/**
 * Get the highest available last attempt schedule time
 */

function ets_gamipress_discord_get_highest_last_attempt_timestamp() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.last_attempt_gmt FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s ORDER BY aa.last_attempt_gmt DESC limit 1', GAMIPRESS_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return strtotime( $result['0']['last_attempt_gmt'] );
	} else {
		return false;
	}
}

/**
 * Get pending jobs 
 */
function ets_gamipress_discord_get_all_pending_actions() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.* FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s AND aa.status="pending" ', GAMIPRESS_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result['0'];
	} else {
		return false;
	}
}

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
	$ets_gamipress_discord_client_id     = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_client_id' ) ) );
	$ets_gamipress_discord_client_secret = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_client_secret' ) ) );
	$ets_gamipress_discord_bot_token     = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_bot_token' ) ) );
	$ets_gamipress_discord_redirect_url  = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_redirect_url' ) ) );
	$ets_gamipress_discord_server_id     = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_server_id' ) ) );

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
	$parts       = parse_url( home_url() );
	$current_uri = "{$parts['scheme']}://{$parts['host']}" . ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) . add_query_arg( null, null );

		return $current_uri;
}

 /*
 * Get BOT name
 *
 * @param NONE
 * @return NONE
 */
function ets_gamipress_discord_update_bot_name_option() {

	$guild_id          = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_server_id' ) ) );
	$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_bot_token' ) ) );
	if ( $guild_id && $discord_bot_token ) {

				$discod_current_user_api = CONNECT_GAMIPRESS_API_URL . 'users/@me';

		$app_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
		);

		$app_response = wp_remote_post( $discod_current_user_api, $app_args );

		$response_arr = json_decode( wp_remote_retrieve_body( $app_response ), true );

		if ( is_array( $response_arr ) && array_key_exists( 'username', $response_arr ) ) {

			update_option( 'ets_gamipress_discord_connected_bot_name', $response_arr ['username'] );
		} else {
			delete_option( 'ets_gamipress_discord_connected_bot_name' );
		}
	}

}

 /**
  * Get WP Pages list
  *
  * @param INT $ets_gamipress_discord_redirect_page_id
  * @return STRING $options
  */
function ets_gamipress_discord_pages_list( $ets_gamipress_discord_redirect_page_id ) {
	$args    = array(
		'sort_order'   => 'asc',
		'sort_column'  => 'post_title',
		'hierarchical' => 1,
		'exclude'      => '',
		'include'      => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'exclude_tree' => '',
		'number'       => '',
		'offset'       => 0,
		'post_type'    => 'page',
		'post_status'  => 'publish',
	);
	$pages   = get_pages( $args );
	$options = '<option value="">-</option>';
	foreach ( $pages as $page ) {
		$selected = ( esc_attr( $page->ID ) === $ets_gamipress_discord_redirect_page_id ) ? ' selected="selected"' : '';
		$options .= '<option data-page-url="' . ets_get_gamipress_discord_formated_discord_redirect_url( $page->ID ) . '" value="' . esc_attr( $page->ID ) . '" ' . $selected . '> ' . $page->post_title . ' </option>';
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
function ets_gamipress_discord_get_ranks() {
	$ets_gamipress_ranks = array();
	$rank_types          = gamipress_get_rank_types();
	foreach ( $rank_types as $rank_type => $data ) :
		global $wpdb;
		$ranks        = $wpdb->prepare(
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
		if ( is_array( $ranks_result ) && count( $ranks_result ) ) {
			foreach ( $ranks_result as $rank_result ) {
				$ets_gamipress_ranks[ $rank_result['ID'] ] = $rank_result['post_title'];

			}
		}
	endforeach;
	if ( is_array( $ets_gamipress_ranks ) ) {
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
	$rank_types                   = gamipress_get_rank_types();
	foreach ( $rank_types as $rank_type => $data ) :

		$user_rank = gamipress_get_user_rank( $user_id, $rank_type );
		if ( $user_rank ) {
			array_push( $ets_gamipress_user_ranks_ids, $user_rank->ID );

		}
	endforeach;
	if ( is_array( $ets_gamipress_user_ranks_ids ) && count( $ets_gamipress_user_ranks_ids ) > 0 ) {
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

function ets_gamipress_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord ) {

	if ( $mapped_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';

		$restrictcontent_discord .= esc_html__( 'Following Roles will be assigned to you in Discord: ', 'connect-gamipress-discord-addon' );
		$restrictcontent_discord .= $mapped_role_name;
		if ( $default_role_name ) {
			$restrictcontent_discord .= $default_role_name;

		}

		$restrictcontent_discord .= '</p>';
	} elseif ( $default_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';

		$restrictcontent_discord .= esc_html__( 'Following Role will be assigned to you in Discord: ', 'connect-gamipress-discord-addon' );
		$restrictcontent_discord .= $default_role_name;

		$restrictcontent_discord .= '</p>';

	}
	return $restrictcontent_discord;
}

/**
 * Get allowed html using WordPress API function wp_kses
 *
 * @param STRING $html_message
 * @return STRING $html_message
 */

function ets_gamipress_discord_allowed_html() {
	$allowed_html = array(
		'div'    => array(
			'class' => array(),
		),
		'p'      => array(
			'class' => array(),
		),
		'a'      => array(
			'id'           => array(),
			'data-user-id' => array(),
			'href'         => array(),
			'class'        => array(),
			'style'        => array(),
		),
		'label'  => array(
			'class' => array(),
		),
		'h3'     => array(),
		'span'   => array(
			'class' => array(),
		),
		'i'      => array(
			'style' => array(),
			'class' => array(),
		),
		'button' => array(
			'class'        => array(),
			'data-user-id' => array(),
			'id'           => array(),
		),
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

/**
 * Get student's roles ids
 *
 * @param INT $user_id
 * @return ARRAY|NULL $roles
 */
function ets_gamipress_discord_get_user_roles( $user_id ) {
	global $wpdb;

	$usermeta_table     = $wpdb->prefix . 'usermeta';
	$user_roles_sql     = 'SELECT * FROM ' . $usermeta_table . " WHERE `user_id` = %d AND ( `meta_key` like '_ets_gamipress_discord_role_id_for_%' OR `meta_key` = 'ets_gamipress_discord_default_role_id' OR `meta_key` = '_ets_gamipress_discord_last_default_role' ); ";
	$user_roles_prepare = $wpdb->prepare( $user_roles_sql, $user_id );

	$user_roles = $wpdb->get_results( $user_roles_prepare, ARRAY_A );

	if ( is_array( $user_roles ) && count( $user_roles ) ) {
		$roles = array();
		foreach ( $user_roles as  $role ) {

			array_push( $roles, $role['meta_value'] );
		}

		return $roles;

	} else {
		return null;
	}
}

/**
 * Get formatted message to send in DM
 *
 * @param INT   $user_id
 * @param ARRAY $ranks the user's ranks
 * Merge fields: [GP_USER_NAME], [GP_USER_EMAIL], [GP_RANKS], [SITE_URL], [BLOG_NAME]
 */
function ets_gamipress_discord_get_formatted_welcome_dm( $user_id, $ranks_user, $message ) {

	$user_obj   = get_user_by( 'id', $user_id );
	$USERNAME   = $user_obj->user_login;
	$USER_EMAIL = $user_obj->user_email;
	$SITE_URL   = get_bloginfo( 'url' );
	$BLOG_NAME  = get_bloginfo( 'name' );

	$RANKS = '';
	if ( is_array( $ranks_user ) ) {
		$args_ranks = array(
			'orderby'     => 'title',
			'order'       => 'ASC',
			'numberposts' => count( $ranks_user ),
			'post__in'    => $ranks_user,
			'post_type'   => 'any',
		);
		$ranks      = get_posts( $args_ranks );
		$lastKey    = array_key_last( $ranks );
		$commas     = ', ';
		foreach ( $ranks as $key => $rank ) {
			if ( $lastKey === $key ) {
				$commas = ' ';
			}
				$RANKS .= esc_html( $rank->post_title ) . $commas;
		}
	}

		$find    = array(
			'[GP_RANKS]',
			'[GP_USER_NAME]',
			'[GP_USER_EMAIL]',
			'[SITE_URL]',
			'[BLOG_NAME]',
		);
		$replace = array(
			$RANKS,
			$USERNAME,
			$USER_EMAIL,
			$SITE_URL,
			$BLOG_NAME,
		);

		return str_replace( $find, $replace, $message );

}

/**
 * Get formatted award user points message to send in DM.
 *
 * @param INT $user_id The user ID.
 * @param INT $achievement_id The achievement ID.
 * Merge fields: [GP_USER_NAME], [GP_USER_EMAIL], [GP_POINTS],[GP_ACHIEVEMENT_TYPE], [GP_ACHIEVEMENT], [SITE_URL], [BLOG_NAME]
 */
function ets_gamipress_discord_get_formatted_award_points_dm( $user_id, $achievement_id, $points, $message ) {
	$user_obj   = get_user_by( 'id', $user_id );
	$USERNAME   = $user_obj->user_login;
	$USER_EMAIL = $user_obj->user_email;
	$SITE_URL   = get_bloginfo( 'url' );
	$BLOG_NAME  = get_bloginfo( 'name' );

	$achievement       = get_post( $achievement_id );
	$ACHIEVEMENT_TITLE = $achievement->post_title;

	$achievement_steps       = gamipress_get_achievement_steps( $achievement_id );
	$ACHIEVEMENT_STEP_TITLES = '';
	if ( is_array( $achievement_steps ) && count( $achievement_steps ) > 0 ) {

		foreach ( $achievement_steps as $achievement_step ) {
			$ACHIEVEMENT_STEP_TITLES .= ' ' . $achievement_step->post_title;
		}
	}

	$POINTS = $points;

	$ACHIEVEMENT_TYPE = '';
	$args             = array(
		'name'        => $achievement->post_type,
		'post_type'   => 'achievement-type',
		'post_status' => 'publish',
		'numberposts' => 1,
	);
	$achievement_type = get_posts( $args );
	if ( is_array( $achievement_type ) && count( $achievement_type ) > 0 ) {
		$ACHIEVEMENT_TYPE = $achievement_type[0]->post_title;
	}

	$find    = array(
		'[GP_USER_NAME]',
		'[GP_USER_EMAIL]',
		'[GP_POINTS]',
		'[GP_ACHIEVEMENT_TYPE]',
		'[GP_ACHIEVEMENT]',
		'[GP_ACHIEVEMENT_STEPS]',
		'[SITE_URL]',
		'[BLOG_NAME]',
	);
	$replace = array(
		$USERNAME,
		$USER_EMAIL,
		$POINTS,
		$ACHIEVEMENT_TYPE,
		$ACHIEVEMENT_TITLE,
		$ACHIEVEMENT_STEP_TITLES,
		$SITE_URL,
		$BLOG_NAME,
	);

	return str_replace( $find, $replace, $message );

}

/**
 * Get formatted deduct user points message to send in DM.
 *
 * @param INT $user_id The user ID.
 * @param STRING 	$points_type 	The points type
 * @param INT			$points 		The points the user is being revoked
 * Merge fields: [GP_USER_NAME], [GP_USER_EMAIL], [GP_DEDUCT_POINTS], [GP_POINTS_TYPE], [GP_POINTS_LABEL], [GP_POINTS_BALANCE], [SITE_URL], [BLOG_NAME]
 */
function ets_gamipress_discord_get_formatted_deduct_points_dm( $user_id, $points_type, $points, $message ) {
	$user_obj   = get_user_by( 'id', $user_id );
	$USERNAME   = $user_obj->user_login;
	$USER_EMAIL = $user_obj->user_email;
	$SITE_URL   = get_bloginfo( 'url' );
	$BLOG_NAME  = get_bloginfo( 'name' );

	$DEDUCT_POINTS = $points;
	$POINTS_TYPE = $points_type;
	$POINTS_LABEL = '';

	$POINTS_BALANCE = absint( gamipress_get_user_points( $user_id, $points_type ) );

	$find    = array(
		'[GP_USER_NAME]',
		'[GP_USER_EMAIL]',
		'[GP_DEDUCT_POINTS]',
		'[GP_POINTS_TYPE]',
		'[GP_POINTS_LABEL]',
		'[GP_POINTS_BALANCE]',
		'[SITE_URL]',
		'[BLOG_NAME]',
	);
	$replace = array(
		$USERNAME,
		$USER_EMAIL,
		$DEDUCT_POINTS,
		$POINTS_TYPE,
		$POINTS_LABEL,
		$POINTS_BALANCE,
		$SITE_URL,
		$BLOG_NAME,
	);

	return str_replace( $find, $replace, $message );

}

/**
 * Get formatted award user Rank message to send in DM.
 *
 * @param INT $user_id The user ID.
 * @param INT $rank_id The rank ID.
 * Merge fields: [GP_USER_NAME], [GP_USER_EMAIL], [GP_RANK_TYPE], [GP_RANK], [GP_RANK_REQUIREMENTS], [SITE_URL], [BLOG_NAME]
 */
function ets_gamipress_discord_get_formatted_award_rank_dm( $user_id, $rank_id, $message ) {
	$user_obj   = get_user_by( 'id', $user_id );
	$USERNAME   = $user_obj->user_login;
	$USER_EMAIL = $user_obj->user_email;
	$SITE_URL   = get_bloginfo( 'url' );
	$BLOG_NAME  = get_bloginfo( 'name' );

	$rank       = get_post( $rank_id );
	$RANK_TITLE = $rank->post_title;

	$RANK_TYPE = '';
	$args      = array(
		'name'        => $rank->post_type,
		'post_type'   => 'rank-type',
		'post_status' => 'publish',
		'numberposts' => 1,
	);
	$rank_type = get_posts( $args );
	if ( is_array( $rank_type ) && count( $rank_type ) > 0 ) {
		$RANK_TYPE = $rank_type[0]->post_title;
	}

	$RANK_REQUIREMENTS = '';
	$rank_requirements = gamipress_get_rank_requirements( $rank_id );
	if ( is_array( $rank_requirements ) && count( $rank_requirements ) > 0 ) {
		foreach ( $rank_requirements as $rank_requirement ) {
			$RANK_REQUIREMENTS .= ' ' . $rank_requirement->post_title;
		}
	}
	$find    = array(
		'[GP_USER_NAME]',
		'[GP_USER_EMAIL]',
		'[GP_RANK_TYPE]',
		'[GP_RANK]',
		'[GP_RANK_REQUIREMENTS]',
		'[SITE_URL]',
		'[BLOG_NAME]',
	);
	$replace = array(
		$USERNAME,
		$USER_EMAIL,
		$RANK_TYPE,
		$RANK_TITLE,
		$RANK_REQUIREMENTS,
		$SITE_URL,
		$BLOG_NAME,
	);

	return str_replace( $find, $replace, $message );
}

/**
 * Remove all usermeta created by this plugin.
 *
 * @param INT $user_id
 */
function ets_gamipress_discord_remove_usermeta( $user_id ) {

	global $wpdb;

	$usermeta_table      = $wpdb->prefix . 'usermeta';
	$usermeta_sql        = 'DELETE FROM ' . $usermeta_table . " WHERE `user_id` = %d AND  `meta_key` LIKE '_ets_gamipress_discord%'; ";
	$delete_usermeta_sql = $wpdb->prepare( $usermeta_sql, $user_id );
	$wpdb->query( $delete_usermeta_sql );
}

/**
 * Check if it's a Rank post type earned.
 *
 * @param INT $post_id The Post ID.
 * @return BOOL.
 */
function ets_gamipress_discord_is_rank_earning( $post_id ) {

	$earn           = get_post( $post_id );
	$args           = array(
		'name'        => $earn->post_type,
		'post_type'   => 'rank-type',
		'post_status' => 'publish',
		'numberposts' => 1,
	);
	$earn_post_type = get_posts( $args );
	if ( is_array( $earn_post_type ) && count( $earn_post_type ) > 0 ) {

		return true;
	} else {
		return false;
	}

}

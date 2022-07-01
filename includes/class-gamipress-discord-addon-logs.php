<?php
/**
 * Class to handle log of API errors
 */
class Connect_Gamipress_Discord_Add_On_Logs {
	function __construct() {
		// Clear all existing logs.
		add_action( 'wp_ajax_ets_gamipress_discord_clear_logs', array( $this, 'ets_gamipress_discord_clear_logs' ) );
	}

	/**
	 * Static property to define log file name
	 *
	 * @param None
	 * @return string $log_file_name
	 */
	public static $log_file_name = 'gamipress_discord_api_logs.txt';

	/**
	 * Clear previous logs history
	 *
	 * @param None
	 * @return None
	 */
	public function ets_gamipress_discord_clear_logs() {


		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_gamipress_discord_nonce'], 'ets-gamipress-discord-ajax-nonce' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		try {
			$uuid      = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_uuid_file_name' ) ) );
			$file_name = $uuid . $this::$log_file_name;
			if ( fopen( WP_CONTENT_DIR . '/' . $file_name, 'w' ) ) {
				$myfile = fopen( WP_CONTENT_DIR . '/' . $file_name, 'w' );
				$txt    = current_time( 'mysql' ) . " => Clear logs Successfully\n";
				fwrite( $myfile, $txt );
				fclose( $myfile );
			} else {
				throw new Exception( 'Could not open the file!' );
			}
		} catch ( Exception $e ) {
			return wp_send_json(
				array(
					'error' => array(
						'msg'  => $e->getMessage(),
						'code' => $e->getCode(),
					),
				)
			);
		}
		exit();
	}

	/**
	 * Add API error logs into log file
	 *
	 * @param array  $response_arr
	 * @param array  $backtrace_arr
	 * @param string $error_type
	 * @return None
	 */
	static function write_api_response_logs( $response_arr, $user_id, $backtrace_arr = array() ) {

		$error        = current_time( 'mysql' );
		$user_details = '';
		if ( $user_id ) {
			$user_details = '::User Id:' . $user_id;
		}
		$log_api_response = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_log_api_response' ) ) );
		$uuid             = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_uuid_file_name' ) ) );
		$log_file_name    = $uuid . self::$log_file_name;
		if( is_array( $response_arr ) ){
                        if (  array_key_exists( 'code', $response_arr ) ) {
                                $error .= '==>File:' . $backtrace_arr['file'] . $user_details . '::Line:' . $backtrace_arr['line'] . '::Function:' . $backtrace_arr['function'] . '::' . $response_arr['code'] . ':' . $response_arr['message'];
                                if ( $response_arr['code'] == '50001' ) {
                                        $error .= '<br><b> Solution: The BOT role need to the TOP priority among the other roles. discord.com > Server Settings > Roles > Drag the BOT role to the TOP priority</b>';
                                }
                                file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
                        } elseif (  array_key_exists( 'error', $response_arr ) ) {
                                $error .= '==>File:' . $backtrace_arr['file'] . $user_details . '::Line:' . $backtrace_arr['line'] . '::Function:' . $backtrace_arr['function'] . '::' . $response_arr['error'];
                                file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
                        } elseif ( $log_api_response == true ) {
                                $error .= json_encode( $response_arr ) . '::' . $user_id;
                                file_put_contents( WP_CONTENT_DIR . '/' . $log_file_name, $error . PHP_EOL, FILE_APPEND | LOCK_EX );
                        }                    
		}


	}
}
// instantiating the class needed for the Ajax call
new Connect_Gamipress_Discord_Add_On_Logs();

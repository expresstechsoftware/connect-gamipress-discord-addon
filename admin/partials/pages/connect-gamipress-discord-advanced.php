<?php
$ets_gamipress_discord_send_welcome_dm = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_send_welcome_dm' ) ) );
$ets_gamipress_discord_welcome_message = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_welcome_message' ) ) );

$ets_gamipress_discord_send_award_rank_dm = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_send_award_rank_dm' ) ) );
$ets_gamipress_discord_award_rank_message = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_award_rank_message' ) ) );

$ets_gamipress_discord_send_award_user_points_dm = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_send_award_user_points_dm' ) ) );
$ets_gamipress_discord_award_user_points_message = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_award_user_points_message' ) ) );

$ets_gamipress_discord_send_deduct_user_points_dm = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_send_deduct_user_points_dm' ) ) );
$ets_gamipress_discord_deduct_user_points_message = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_deduct_user_points_message' ) ) );

$retry_failed_api     = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_retry_failed_api' ) ) );
$kick_upon_disconnect = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_kick_upon_disconnect' ) ) );
$retry_api_count      = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_retry_api_count' ) ) );
$set_job_cnrc         = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_job_queue_concurrency' ) ) );
$set_job_q_batch_size = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_job_queue_batch_size' ) ) );
$log_api_res          = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_log_api_response' ) ) );

echo '<pre>';

var_dump( absint( gamipress_get_user_points( 2, 'point-type-1' ) ));
echo '</pre>';

?>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
 <input type="hidden" name="action" value="gamipress_discord_save_advance_settings">
 <input type="hidden" name="current_url" value="<?php echo esc_url( ets_gamipress_discord_get_current_screen_url() ); ?>">   
<?php wp_nonce_field( 'gamipress_discord_advance_settings_nonce', 'ets_gamipress_discord_advance_settings_nonce' ); ?>
  <table class="form-table" role="presentation">
	<tbody>
	<tr>
		<th scope="row"><?php esc_html_e( 'Shortcode:', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		[gamipress_discord]
		<br/>
		<small><?php esc_html_e( 'Use this shortcode [gamipress_discord] to display connect to discord button on any page.', 'connect-gamipress-discord-addon' ); ?></small>
		</fieldset></td>
	</tr>         
	<tr>
		<th scope="row"><?php esc_html_e( 'Send welcome message', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_gamipress_discord_send_welcome_dm" type="checkbox" id="ets_gamipress_discord_send_welcome_dm" 
		<?php
		if ( $ets_gamipress_discord_send_welcome_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Welcome message', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
			<?php $ets_gamipress_discord_welcome_message_value = ( isset( $ets_gamipress_discord_welcome_message ) ) ? $ets_gamipress_discord_welcome_message : ''; ?>
		<textarea class="ets_gamipress_discord_dm_textarea" name="ets_gamipress_discord_welcome_message" id="ets_gamipress_discord_welcome_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( $ets_gamipress_discord_welcome_message_value ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [GP_USER_NAME], [GP_USER_EMAIL], [GP_RANKS], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Send Award Rank message', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_gamipress_discord_send_award_rank_dm" type="checkbox" id="ets_gamipress_discord_award_rank_dm" 
		<?php
		if ( $ets_gamipress_discord_send_award_rank_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Award Rank message', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<?php $ets_gamipress_discord_award_rank_message_value = ( isset( $ets_gamipress_discord_award_rank_message ) ) ? $ets_gamipress_discord_award_rank_message : ''; ?>
		<textarea class="ets_gamipress_discord_dm_textarea" name="ets_gamipress_discord_award_rank_message" id="ets_gamipress_discord_award_rank_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( $ets_gamipress_discord_award_rank_message_value ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [GP_USER_NAME], [GP_USER_EMAIL], [GP_RANK_TYPE], [GP_RANK], [GP_RANK_REQUIREMENTS], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>	
	<tr>
		<th scope="row"><?php esc_html_e( 'Send Award user points message', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_gamipress_discord_send_award_user_points_dm" type="checkbox" id="ets_gamipress_discord_award_user_points_welcome_dm" 
		<?php
		if ( $ets_gamipress_discord_send_award_user_points_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Award user points message', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<?php $ets_gamipress_discord_award_user_points_message_value = ( isset( $ets_gamipress_discord_award_user_points_message ) ) ? $ets_gamipress_discord_award_user_points_message : ''; ?>
		<textarea class="ets_gamipress_discord_dm_textarea" name="ets_gamipress_discord_award_user_points_message" id="ets_gamipress_discord_award_user_points_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( $ets_gamipress_discord_award_user_points_message_value ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [GP_USER_NAME], [GP_USER_EMAIL], [GP_POINTS],[GP_ACHIEVEMENT_TYPE], [GP_ACHIEVEMENT], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Send Deduct user points message', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="ets_gamipress_discord_send_deduct_user_points_dm" type="checkbox" id="ets_gamipress_discord_deduct_user_points_welcome_dm" 
		<?php
		if ( $ets_gamipress_discord_send_deduct_user_points_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Deduct user points message', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<?php $ets_gamipress_discord_deduct_user_points_message_value = ( isset( $ets_gamipress_discord_deduct_user_points_message ) ) ? $ets_gamipress_discord_deduct_user_points_message : ''; ?>
		<textarea class="ets_gamipress_discord_dm_textarea" name="ets_gamipress_discord_deduct_user_points_message" id="ets_gamipress_discord_deduct_user_points_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( $ets_gamipress_discord_deduct_user_points_message_value ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [GP_USER_NAME], [GP_USER_EMAIL], [GP_DEDUCT_POINTS], [GP_POINTS_TYPE], [GP_POINTS_LABEL], [GP_POINTS_BALANCE], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>		
	  <tr>
		<th scope="row"><?php esc_html_e( 'Retry Failed API calls', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="retry_failed_api" type="checkbox" id="retry_failed_api" 
		<?php
		if ( $retry_failed_api == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	  <tr>
		<th scope="row"><?php esc_html_e( 'Don\'t kick users upon disconnect', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="kick_upon_disconnect" type="checkbox" id="kick_upon_disconnect" 
		<?php
		if ( $kick_upon_disconnect == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'How many times a failed API call should get re-try', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<?php $retry_api_count_value = ( isset( $retry_api_count ) ) ? $retry_api_count : 1; ?>			
		<input name="ets_gamipress_retry_api_count" type="number" min="1" id="ets_gamipress_retry_api_count" value="<?php echo esc_attr( intval( $retry_api_count_value ) ); ?>">
		</fieldset></td>
	  </tr> 
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue concurrency', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<?php $set_job_cnrc_value = ( isset( $set_job_cnrc ) ) ? $set_job_cnrc : 1; ?>			
		<input name="set_job_cnrc" type="number" min="1" id="set_job_cnrc" value="<?php echo esc_attr( intval( $set_job_cnrc ) ); ?>">
		</fieldset></td>
	  </tr>
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue batch size', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<?php $set_job_q_batch_size_value = ( isset( $set_job_q_batch_size ) ) ? $set_job_q_batch_size : 10; ?>			
		<input name="set_job_q_batch_size" type="number" min="1" id="set_job_q_batch_size" value="<?php echo esc_attr( intval( $set_job_q_batch_size_value ) ); ?>">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Log API calls response (For debugging purpose)', 'connect-gamipress-discord-addon' ); ?></th>
		<td> <fieldset>
		<input name="log_api_res" type="checkbox" id="log_api_res" 
		<?php
		if ( $log_api_res == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
			
	</tbody>
  </table>
  <div class="bottom-btn">
	<button type="submit" name="adv_submit" value="ets_submit" class="ets-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'connect-gamipress-discord-addon' ); ?>
	</button>
  </div>
</form>

<?php
$ets_gamipress_discord_client_id     = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_client_id' ) ) );
$ets_gamipress_discord_client_secret = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_client_secret' ) ) );
$ets_gamipress_discord_bot_token     = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_bot_token' ) ) );
$ets_gamipress_discord_redirect_url  = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_redirect_url' ) ) );
$ets_gamipress_discord_roles         = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_role_mapping' ) ) );
$ets_gamipress_discord_server_id     = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_server_id' ) ) );
$ets_gamipress_discord_connected_bot_name     = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_connected_bot_name' ) ) );
$ets_gamipress_discord_redirect_page_id  = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_redirect_page_id' ) ) );
?>
<form method="post" action="<?php echo get_site_url() . '/wp-admin/admin-post.php'; ?>">
  <input type="hidden" name="action" value="gamipress_discord_application_settings">
  <input type="hidden" name="current_url" value="<?php echo ets_gamipress_discord_get_current_screen_url()?>">   
	<?php wp_nonce_field( 'save_gamipress_discord_general_settings', 'ets_gamipress_discord_save_settings' ); ?>
  <div class="ets-discord-input-group">
	<label><?php echo __( 'Client ID', 'connect-gamipress-discord-addon' ); ?> :</label>
	<input type="text" class="ets-input" name="ets_gamipress_discord_client_id" value="<?php
	if ( isset( $ets_gamipress_discord_client_id ) ) {
		echo esc_attr( $ets_gamipress_discord_client_id ); }
	?>" required placeholder="Discord Client ID">
  </div>
	<div class="ets-discord-input-group">
	  <label><?php echo __( 'Client Secret', 'connect-gamipress-discord-addon' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_gamipress_discord_client_secret" value="<?php
		if ( isset( $ets_gamipress_discord_client_secret ) ) {
			echo esc_attr( $ets_gamipress_discord_client_secret ); }
    ?>" required placeholder="Discord Client Secret">
	</div>
	<div class="ets-discord-input-group">
    <label><?php echo __( 'Redirect URL', 'connect-gamipress-discord-addon' ); ?> :</label>

    <p class="redirect-url"><b><?php echo $ets_gamipress_discord_redirect_url ?></b></p>
		<select class= "ets-input ets_gamipress_discord_redirect_url" id="ets_gamipress_discord_redirect_url" name="ets_gamipress_discord_redirect_url" style="max-width: 100%" required>
		<?php //echo ets_gamipress_discord_pages_list( $ets_gamipress_discord_redirect_page_id ) ; ?>
		</select>                
                
		<p class="description"><?php echo __( 'Registered discord app url', 'connect-gamipress-discord-addon' ); ?><span class="spinner"></span></p>
                <p class="description ets-discord-update-message"><?php echo sprintf( __( 'Redirect URL updated, kindly add/update the same in your discord.com application link <a href="https://discord.com/developers/applications/%s/oauth2/general">https://discord.com/developers</a>', 'connect-gamipress-discord-addon' ),  $ets_gamipress_discord_client_id ); ?></p>
	</div>
	<div class="ets-discord-input-group">
            <label><?php echo __( 'Admin Redirect URL Connect to bot', 'connect-gamipress-discord-addon' ); ?> :</label>
            <input type="text" class="ets-input" name="ets_gamipress_discord_admin_redirect_url" value="<?php echo get_admin_url('', 'admin.php').'?page=gamipress-discord&via=gamipress-discord-bot'; ?>" readonly required />
        </div>   
	<div class="ets-discord-input-group">
            <?php
            if ( isset( $ets_gamipress_discord_connected_bot_name ) && !empty( $ets_gamipress_discord_connected_bot_name ) ){
                echo sprintf(__( '<p class="description">Make sure the Bot %1$s &nbsp;<span class="discord-bot"><b>BOT</b></span>&nbsp;have the high priority than the roles it has to manage. Open <a href="https://discord.com/channels/%2$s">Discord Server</a></p>', 'connect-gamipress-discord-addon' ), $ets_gamipress_discord_connected_bot_name, $ets_gamipress_discord_server_id );
            }
            ?>            
	  <label><?php echo __( 'Bot Token', 'connect-gamipress-discord-addon' ); ?> :</label>
          <input type="password" class="ets-input" name="ets_gamipress_discord_bot_token" value="<?php
		if ( isset( $ets_gamipress_discord_bot_token ) ) {
			echo esc_attr( $ets_gamipress_discord_bot_token ); }
		?>" required placeholder="Discord Bot Token">
	</div>
	<div class="ets-discord-input-group">
	  <label><?php echo __( 'Server ID', 'connect-gamipress-discord-addon' ); ?> :</label>
		<input type="text" class="ets-input" name="ets_gamipress_discord_server_id"
		placeholder="Discord Server Id" value="<?php
		if ( isset( $ets_gamipress_discord_server_id ) ) {
			echo esc_attr( $ets_gamipress_discord_server_id ); }
		?>" required>
	</div>
	<?php if ( empty( $ets_gamipress_discord_client_id ) || empty( $ets_gamipress_discord_client_secret ) || empty( $ets_gamipress_discord_bot_token ) || empty( $ets_gamipress_discord_redirect_url ) || empty( $ets_gamipress_discord_server_id ) ) { ?>
	  <p class="ets-danger-text description">
		<?php echo __( 'Please save your form', 'connect-gamipress-discord-addon' ); ?>
	  </p>
	<?php } ?>
	<p>
	  <button type="submit" name="submit" value="ets_discord_submit" class="ets-btn-submit ets-bg-green">
		<?php echo __( 'Save Settings', 'connect-gamipress-discord-addon' ); ?>
	  </button>
	  <?php if ( get_option( 'ets_gamipress_discord_client_id' ) ) : ?>
            <a href="?action=discord-connect-to-bot" class="ets-btn-submit gamipress-btn-connect-to-bot" id="gamipress-connect-discord-bot"><?php echo __( 'Connect your Bot', 'connect-gamipress-discord-addon' ) . Ultimate_Member_Discord_Add_On::get_discord_logo_white(); ?> </a>
	  <?php endif; ?>
	</p>
</form>

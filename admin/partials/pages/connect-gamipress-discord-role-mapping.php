<?php

$args_rank_types = array(
    'orderby'          => 'title',
    'order'            => 'ASC',
    'post_status'    => 'publish',
    'numberposts' => -1,
    'post_type'   => 'rank-type'
);
$rank_types = get_posts( $args_rank_types );

$connect_gamipress_default_role        = sanitize_text_field( trim( get_option( 'ets_gamipress_discord_default_role_id' ) ) );
?>
<div class="notice notice-warning ets-notice">
    <p><i class='fas fa-info'></i> <?php esc_html_e ( 'Drag and Drop the Discord Roles over to the GamiPress ranks', 'connect-gamipress-discord-addon' ); ?></p>
</div>

<div class="row-container">
  <div class="ets-column gamipress-discord-roles-col">
	<h2><?php esc_html_e( 'Discord Roles', 'connect-gamipress-discord-addon' ); ?></h2>
	<hr>
	<div class="gamipress-discord-roles">
	  <span class="spinner"></span>
	</div>
  </div>
  <div class="ets-column">
	<h2><?php esc_html_e( 'Ranks', 'connect-gamipress-discord-addon' ); ?></h2>
	<hr>
	<div class="gamipress-discord-rank-type">
	<?php
	foreach ( $rank_types as $rank_type ) {
		
			?>
		  <div class="makeMeDroppable" data-gamipress_rank_type_id="<?php echo esc_attr( $rank_type->ID ); ?>" ><span><?php echo esc_html( $rank_type->post_title ); ?></span></div>
			<?php
		
	}
	?>
	</div>
  </div>
</div>
<form method="post" action="<?php echo esc_url( get_site_url().'/wp-admin/admin-post.php' ) ?>">
 <input type="hidden" name="action" value="gamipress_discord_save_role_mapping">
 <input type="hidden" name="current_url" value="<?php echo esc_url( ets_gamipress_discord_get_current_screen_url() );?>">   
  <table class="form-table" role="presentation">
	<tbody>
	  <tr>
		<th scope="row"><label for="gamipress-defaultRole"><?php esc_html_e( 'Default Role', 'connect-gamipress-discord-addon' ); ?></label></th>
		<td>
		  <?php wp_nonce_field( 'gamipress_discord_role_mappings_nonce', 'ets_gamipress_discord_role_mappings_nonce' ); ?>
		  <input type="hidden" id="selected_default_role" value="<?php echo esc_attr( $connect_gamipress_default_role ); ?>">
		  <select id="gamipress-defaultRole" name="gamipress_defaultRole">
			<option value="none"><?php esc_html_e( '-None-', 'connect-gamipress-discord-addon' ); ?></option>
		  </select>
		  <p class="description"><?php esc_html_e( 'This Role will be assigned to all', 'connect-gamipress-discord-addon' ); ?></p>
		</td>
	  </tr>        

	</tbody>
  </table>
	<br>
  <div class="mapping-json">
	<textarea id="ets_gamipress_mapping_json_val" name="ets_gamipress_discord_role_mapping">
	<?php
	if ( isset( $ets_discord_roles ) ) {
		echo stripslashes( esc_html( $ets_discord_roles ));}
	?>
	</textarea>
  </div>
  <div class="bottom-btn">
	<button type="submit" name="submit" value="ets_submit" class="ets-submit ets-btn-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'connect-gamipress-discord-addon' ); ?>
	</button>
	<button id="revertMapping" name="flush" class="ets-submit ets-btn-submit ets-bg-red">
	  <?php esc_html_e( 'Flush Mappings', 'connect-gamipress-discord-addon' ); ?>
	</button>
  </div>
</form>
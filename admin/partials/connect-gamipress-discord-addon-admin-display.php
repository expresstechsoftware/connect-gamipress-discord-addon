<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Gamipress_Discord_Addon
 * @subpackage Connect_Gamipress_Discord_Addon/admin/partials
 */
?>
<?php
if ( isset( $_GET['save_settings_msg'] ) ) {
	?>
	<div class="notice notice-success is-dismissible support-success-msg">
		<p><?php echo esc_html( $_GET['save_settings_msg'] ); ?></p>
	</div>
	<?php
}
?>
<h1><?php esc_html_e( 'GamiPress Discord Add On Settings', 'connect-gamipress-discord-addon' ); ?></h1>
		<div id="gamipress-discord-outer" class="skltbs-theme-light" data-skeletabs='{ "startIndex": 0 }'>
			<ul class="skltbs-tab-group">
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="settings" ><?php esc_html_e( 'Application Details', 'connect-gamipress-discord-addon' ); ?><span class="initialtab spinner"></span></button>
				</li>
				<li class="skltbs-tab-item">
				<?php if ( gamipress_discord_check_saved_settings_status() ) : ?>
				<button class="skltbs-tab" data-identity="level-mapping" ><?php esc_html_e( 'Role Mappings', 'connect-gamipress-discord-addon' ); ?></button>
				<?php endif; ?>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="advanced" ><?php esc_html_e( 'Advanced', 'connect-gamipress-discord-addon' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="appearance" ><?php esc_html_e( 'Appearance', 'connect-gamipress-discord-addon' ); ?>	
				</button>
				</li>                                
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="logs" ><?php esc_html_e( 'Logs', 'connect-gamipress-discord-addon' ); ?>	
				</button>
				</li>    
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="documentation" ><?php esc_html_e( 'Documentation', 'connect-gamipress-discord-addon' ); ?>	
				</button>
				</li> 
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="support" ><?php esc_html_e( 'Support', 'connect-gamipress-discord-addon' ); ?>	
				</button>
				</li>				                            
			</ul>
			<div class="skltbs-panel-group">
				<div id="ets_gamipress_application_details" class="gamipress-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-gamipress-discord-application-details.php'; ?>
				</div>
				<?php if ( gamipress_discord_check_saved_settings_status() ) : ?>      
				<div id="ets_gamipress_discord_role_mapping" class="gamipress-discord-tab-conetent skltbs-panel">
					<?php require_once CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-gamipress-discord-role-mapping.php'; ?>
				</div>
				<?php endif; ?>
				<div id='ets_gamipress_discord_advanced' class="gamipress-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-gamipress-discord-advanced.php'; ?>
				</div>
				<div id='ets_gamipress_discord_appearance' class="gamipress-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-gamipress-discord-appearance.php'; ?>
				</div>                            
				<div id='ets_gamipress_discord_logs' class="gamipress-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-gamipress-discord-error-log.php'; ?>
				</div> 
				<div id='ets_gamipress_discord_documentation' class="gamipress-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-gamipress-discord-documentation.php'; ?>
				</div>
				<div id='ets_gamipress_discord_support' class="gamipress-discord-tab-conetent skltbs-panel">
				<?php require_once CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-gamipress-discord-support.php'; ?>
				</div>				                           
			</div>  
		</div>

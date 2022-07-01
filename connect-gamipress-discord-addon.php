<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.expresstechsoftwares.com
 * @since             1.0.0
 * @package           Connect_Gamipress_Discord_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Connect GamiPress and Discord
 * Plugin URI:         https://www.expresstechsoftwares.com/connect-gamipress-and-discord/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            ExpressTech Softwares Solutions Pvt Ltd
 * Author URI:        https://www.expresstechsoftwares.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connect-gamipress-discord-addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CONNECT_GAMIPRESS_DISCORD_ADDON_VERSION', '1.0.0' );

/**
 * Define plugin directory path
 */
define( 'CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Discord API call scopes
 */
define( 'CONNECT_GAMIPRESS_DISCORD_OAUTH_SCOPES', 'identify email guilds guilds.join' );

/**
 * Discord API url. 
 */
define( 'CONNECT_GAMIPRESS_API_URL', 'https://discord.com/api/v10/' );

/**
 * Discord BOT Permissions
 */
define( 'CONNECT_GAMIPRESS_DISCORD_BOT_PERMISSIONS', 8 );

/**
 * Define group name for action scheduler actions
 */
define( 'GAMIPRESS_DISCORD_AS_GROUP_NAME', 'ets-gamipress-discord' );

/**
 * Follwing response codes not cosider for re-try API calls.
 */
define( 'GAMIPRESS_DISCORD_DONOT_RETRY_THESE_API_CODES', array( 0, 10003, 50033, 10004, 50025, 10013, 10011 ) );

/**
 * Define plugin directory url
 */
define( 'GAMIPRESS_DISCORD_DONOT_RETRY_HTTP_CODES', array( 400, 401, 403, 404, 405, 502 ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-connect-gamipress-discord-addon-activator.php
 */
function activate_connect_gamipress_discord_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-connect-gamipress-discord-addon-activator.php';
	Connect_Gamipress_Discord_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-connect-gamipress-discord-addon-deactivator.php
 */
function deactivate_connect_gamipress_discord_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-connect-gamipress-discord-addon-deactivator.php';
	Connect_Gamipress_Discord_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_connect_gamipress_discord_addon' );
register_deactivation_hook( __FILE__, 'deactivate_connect_gamipress_discord_addon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-connect-gamipress-discord-addon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_connect_gamipress_discord_addon() {

	$plugin = new Connect_Gamipress_Discord_Addon();
	$plugin->run();

}
run_connect_gamipress_discord_addon();

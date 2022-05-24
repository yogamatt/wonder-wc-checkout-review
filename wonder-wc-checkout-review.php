<?php
/**
 * Plugin Name: Wonder WC Checkout Review
 * Plugin URI:  mediaworksweb.com
 * Description: Review your checkout before the order submits.
 * Version:     0.1
 * Author:      Wonderjar Creative
 * Author URI:  https://wonderjarcreative.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access
defined( 'ABSPATH' ) or die( 'No direct access!' );

$wwcr_path = plugin_dir_path( __FILE__ );
$wwcr_url = plugin_dir_url( __FILE__ );

define( 'WWCR_VERSION', '1.0' );
define( 'WWCR_PATH', $wwcr_path );
define( 'WWCR_URL', $wwcr_url );

function activate_wwcr() {
	require_once WWCR_PATH . 'includes/class-wwcr-activator.php';
	WWCR_Activator::activate();
}

function deactivate_wwcr() {
	require_once WWCR_PATH . 'includes/class-wwcr-deactivator.php';
	WWCR_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wwcr' );
register_deactivation_hook( __FILE__, 'deactivate_wwcr' );

/**
 * Core plugin class
 */
require WWCR_PATH . 'includes/class-wwcr.php';

/**
 * Begin execution of plugin
 * 
 * The rest of the plugin is registered via hooks,
 * so we can start the plugin here.
 *
 * @since 1.0.0
 */
function run_wwcr() {
	$wwcr = new WWCR();
    $wwcr->init();
}
run_wwcr();
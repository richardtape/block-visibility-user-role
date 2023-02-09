<?php
/**
 * Content Visibility User Role
 *
 * @package     ContentVisibilityUserRole
 * @author      Richard Tape
 * @copyright   2021 Richard Tape
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Content Visibility User Role
 * Plugin URI:  https://richardtape.com/content-visibility/
 * Description: [Content Visibility Add-On] Determine which user role(s) can see the content blocks of your choosing.
 * Version:     0.1.6
 * Author:      Richard Tape
 * Author URI:  https://richardtape.com
 * Text Domain: content-visibility-user-role
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/block-visibility-user-role.php';

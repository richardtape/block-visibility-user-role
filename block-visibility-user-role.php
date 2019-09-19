<?php

/**
 * Block Visibility User Role Add-On
 *
 * @package     BlockVisibilityUserRole
 * @author      Richard Tape
 * @copyright   2019 Richard Tape
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Block Visibility User Role Add-On
 * Plugin URI:  https://richardtape.com
 * Description: Determine which user role(s) can see the blocks of your choosing.
 * Version:     1.0.0
 * Author:      Richard Tape
 * Author URI:  https://richardtape.com
 * Text Domain: plugin-name
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/block-visibility-user-role.php';

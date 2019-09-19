<?php

/**
 * Main loader file for Block Visibility User Role Add-on.
 *
 * @package BlockVisibilityUserRole
 */

namespace RichardTape\BlockVisibilityUserRole;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_editor_assets', 30 );

/**
 * Enqueue script and style assets used in the editor.
 *
 * @since 1.0.0
 */
function enqueue_editor_assets() { // phpcs:ignore

	if ( ! is_admin() ) {
		return;
	}

	$screens = array(
		'post',
		'page',
	);

	if ( ! in_array( get_current_screen()->id, array_values( $screens ), true ) ) {
		return;
	}

	wp_register_script(
		'block-visibility-user-role',
		plugins_url( '/build/index.js', dirname( __FILE__ ) ),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-editor',
			'wp-plugins',
			'wp-edit-post',
		),
		filemtime( plugin_dir_path( __DIR__ ) . 'build/index.js' ),
		true
	);

	$roles      = get_editable_roles();
	$role_names = array();

	foreach ( $roles as $role_id => $role_details ) {
		$role_names[] = array(
			'label' => $role_details['name'],
			'value' => sanitize_title_with_dashes( $role_details['name'] )
		);
	}

	$block_visibility_user_role_args = array(
		'roles' => $role_names,
	);

	wp_localize_script( 'block-visibility-user-role', 'BlockVisibilityUserRole', $block_visibility_user_role_args );

	wp_enqueue_script( 'block-visibility-user-role' );

	wp_enqueue_style( 'block-visibility-user-role-panel', plugins_url( 'build/editor.css', dirname( __FILE__ ) ) );

}//end enqueue_editor_assets()

add_filter( 'block_visibility_rule_types_and_callbacks', __NAMESPACE__ . '\\add_rule_type_and_callback' );

/**
 * Register our rule type to enable us to provide the logic callback.
 *
 * @param array $default_rule_types_and_callbacks Existing rules and callbacks.
 * @return array modified rule types and callbacks with ours added.
 */
function add_rule_type_and_callback( $default_rule_types_and_callbacks ) {

	$default_rule_types_and_callbacks['userRole'] = __NAMESPACE__ . 'rule_logic_user_role';

	return $default_rule_types_and_callbacks;

}//end add_rule_type_and_callback()

/**
 * Undocumented function
 *
 * @param string $rule_value What the rule is set to: 'logged-in' or 'logged-out'.
 * @param string $block_visibility Whether the block should be shown or hidden if the rule is true.
 * @param array  $block The full block.
 * @return bool  false if the block is to be removed. true if the block is to be potentially kept.
 */
function rule_logic_user_role( $rule_value, $block_visibility, $block ) {

	$authenticated_user = is_user_logged_in();

	// If a user isn't signed in, they can't have a role.
	if ( false === $authenticated_user ) {

		switch ( $block_visibility ) {
			case 'shown':
				return false;

			case 'hidden':
				return true;
		}
	}

	$user  = wp_get_current_user();
	$roles = (array) $user->roles;

	// @TODO: This!
	return true;

}//end rule_logic_user_role()

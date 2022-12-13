<?php
/**
 * Main loader file for Content Visibility User Role Add-on.
 *
 * @package ContentVisibilityUserRole
 */

namespace RichardTape\ContentVisibilityUserRole;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Use the content_visibility_enqueue_editor_assets action to load our assets so we know we're loading when and where we should be.
add_action( 'content_visibility_enqueue_editor_assets', __NAMESPACE__ . '\\enqueue_editor_assets', 30 );

/**
 * Enqueue script and style assets used in the editor.
 *
 * @since 1.0.0
 */
function enqueue_editor_assets() { // phpcs:ignore

	$prereqs = array(
		'wp-blocks',
		'wp-i18n',
		'wp-element',
		'wp-plugins',
		'wp-dom-ready',
	);

	// The 5.8 widgets screen requires a special editor?! Feelsbadman.
	$CVEditor = new \RichardTape\ContentVisibility\Editor();
	if ( $CVEditor->on_widgets_screen() ) {
		$prereqs[] = 'wp-edit-widgets';
	} else {
		$prereqs[] = 'wp-editor';
	}

	wp_register_script(
		'content-visibility-user-role',
		plugins_url( '/build/index.js', dirname( __FILE__ ) ),
		$prereqs,
		filemtime( plugin_dir_path( __DIR__ ) . 'build/index.js' ),
		true
	);

	// Generate a list of public roles and pass them to our JavaScript.
	$roles      = get_editable_roles();
	$role_names = array();

	foreach ( $roles as $role_id => $role_details ) {
		$role_names[] = array(
			'label' => $role_details['name'],
			'value' => sanitize_title_with_dashes( $role_details['name'] ),
		);
	}

	$block_visibility_user_role_args = array(
		'roles' => $role_names,
	);

	wp_localize_script( 'content-visibility-user-role', 'BlockVisibilityUserRole', $block_visibility_user_role_args );

	wp_enqueue_script( 'content-visibility-user-role' );

	wp_enqueue_style( 'content-visibility-user-role-panel', plugins_url( 'build/index.css', dirname( __FILE__ ) ) );

}//end enqueue_editor_assets()

add_filter( 'content_visibility_rule_types_and_callbacks', __NAMESPACE__ . '\\add_rule_type_and_callback' );

/**
 * Register our rule type to enable us to provide the logic callback.
 *
 * @param array $default_rule_types_and_callbacks Existing rules and callbacks.
 * @return array modified rule types and callbacks with ours added.
 */
function add_rule_type_and_callback( $default_rule_types_and_callbacks ) {

	$default_rule_types_and_callbacks['userRole'] = __NAMESPACE__ . '\rule_logic_user_role';

	return $default_rule_types_and_callbacks;

}//end add_rule_type_and_callback()

/**
 * Determine if the passed block should be shown based on the rules that have been selected.
 * In this case, if there are role-based rules, check to see if the current user is in the
 * roles list approved by this block.
 *
 * @param array  $rule_value Which roles are selected for this block.
 * @param string $block_visibility Whether the block should be shown or hidden if the rule is true.
 * @param array  $block The full block.
 * @return bool  false if the block is to be removed. true if the block is to be potentially kept.
 */
function rule_logic_user_role( $rule_value, $block_visibility, $block ) {

	// Make sure we're not touching this block if no roles are set. keep this block to let others decide.
	if ( ! is_array( $rule_value ) || empty( $rule_value ) ) {
		return true;
	}

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

	$user        = wp_get_current_user();
	$users_roles = (array) $user->roles;

	$users_role_is_in_block_roles_list = false;

	// Test if [any of] the user's role[s] are in the passed block's roles.
	foreach ( $users_roles as $id => $users_role_slug ) {

		if ( in_array( $users_role_slug, array_keys( $rule_value ), true ) && 1 === absint( $rule_value[ $users_role_slug ] ) ) {
			$users_role_is_in_block_roles_list = true;
		}
	}

	switch ( $block_visibility ) {
		case 'shown':
			return $users_role_is_in_block_roles_list;

		case 'hidden':
			return ! $users_role_is_in_block_roles_list;
	}

}//end rule_logic_user_role()

import { Fill, Disabled } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import { addFilter } from '@wordpress/hooks';

import BlockVisibilityUserRolePanelBodyControl from './block-visibility-user-role-panel-body';

export function BlockVisibilityUserRoleControl( data ) {

    let { props } = { ...data };

    let rulesEnabled = props.attributes.blockVisibilityRules.blockVisibilityRulesEnabled;

    if ( ! rulesEnabled ) {
        return (
            <Disabled><BlockVisibilityUserRolePanelBodyControl props={ props } /></Disabled>
        );
    }

    return (
        <BlockVisibilityUserRolePanelBodyControl props={ props } />
    );

}

/**
 * Render the <BlockVisibilityUserRoleControl> component by adding
 * it to the block-visibility-extra-controls Fill.
 *
 * @return {Object} A Fill component wrapping the BlockVisibilityUserRoleControl component.
 */
function BlockVisibilityUserRoleFill() {
    return (
        <Fill name="block-visibility-extra-controls">
            {
                ( fillProps ) => {
                    return (
                        <BlockVisibilityUserRoleControl props={ fillProps } />
                    )
                }
            }
        </Fill>
    );

}

// Add our component to the Slot provided by BlockVisibilityControls
registerPlugin( 'block-visibility-02-user-role-fill', { render: BlockVisibilityUserRoleFill } );


// Register our visibility rule with the main plugin
addFilter( 'blockVisibility.defaultBlockVisibilityRules', 'block-visibility-user-role/block-visibility-rules', registerBlockVisibilityRule );

function registerBlockVisibilityRule( defaultRules ) {

    defaultRules.userRole = {};

    return defaultRules;

}

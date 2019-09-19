import { Fill, Disabled } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';

import { BlockVisibilityUserRolePanelBodyControl } from './block-visibility-user-role-panel-body';

export const BlockVisibilityUserRoleControl = withState( {
    option: '',
} )( ( { option, setState, props } ) => {

    let rulesEnabled = props.attributes.blockVisibilityRules.blockVisibilityRulesEnabled;

    if ( ! rulesEnabled ) {
        return (
            <Disabled><BlockVisibilityUserRolePanelBodyControl props={ props } /></Disabled>
        );
    }

    return (
        <BlockVisibilityUserRolePanelBodyControl props={ props } />
    );

} );

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

import { Fill, Disabled } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import { addFilter } from '@wordpress/hooks';

import ContentVisibilityUserRolePanelBodyControl from './content-visibility-user-role-panel-body';

export function ContentVisibilityUserRoleControl( data ) {

    let { props } = { ...data };

    let rulesEnabled    = props.attributes.contentVisibilityRules.contentVisibilityRulesEnabled;
    let contentVisibility = props.attributes.hasOwnProperty( 'contentVisibility' );

    if ( ! rulesEnabled || ! contentVisibility ) {
        return (
            <Disabled><ContentVisibilityUserRolePanelBodyControl props={ props } /></Disabled>
        );
    }

    return (
        <ContentVisibilityUserRolePanelBodyControl props={ props } />
    );

}

/**
 * Render the <ContentVisibilityUserRoleControl> component by adding
 * it to the block-visibility-extra-controls Fill.
 *
 * @return {Object} A Fill component wrapping the ContentVisibilityUserRoleControl component.
 */
function ContentVisibilityUserRoleFill() {
    return (
        <Fill name="content-visibility-extra-controls">
            {
                ( fillProps ) => {
                    return (
                        <ContentVisibilityUserRoleControl props={ fillProps } />
                    )
                }
            }
        </Fill>
    );

}

// Add our component to the Slot provided by BlockVisibilityControls
registerPlugin( 'content-visibility-02-user-role-fill', { render: ContentVisibilityUserRoleFill } );


// Register our visibility rule with the main plugin
addFilter( 'contentVisibility.defaultContentVisibilityRules', 'content-visibility-user-role/block-visibility-rules', registerContentVisibilityRule );

function registerContentVisibilityRule( defaultRules ) {

    defaultRules.userRole = {};

    return defaultRules;

}

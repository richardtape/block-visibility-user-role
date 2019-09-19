import { RadioControl, PanelBody, PanelRow } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import { doAction } from '@wordpress/hooks';

export const BlockVisibilityUserRolePanelBodyControl = withState( {
    option: '',
} )( ( { option, setState, props } ) => {

    // Fetch the  roles from PHP
    const userRoles = BlockVisibilityUserRole.roles;

    return (

        <PanelBody
            title={ __( 'User Role', 'block-visibility-user-role' ) }
            initialOpen={ false }
            className="block-visibility-control-panel block-visibility-user-role-controls"
        >
            <PanelRow>
                <RadioControl
                    label=''
                    help=''
                    className="block-visibility-user-role-control"
                    selected={ props.attributes.blockVisibilityRules.userRole || option }
                    options={ userRoles }
                    onChange={ ( option ) => {

                        // Set the state and props.
                        setState( { option } );

                        let newBVRules = { ...props.attributes.blockVisibilityRules };
                        newBVRules.userRole = option;

                        props.setAttributes( {
                            blockVisibilityRules: newBVRules,
                        } );

                        // Fire an action so we can see what's happened in other controls. This can be useful,
                        // for example when setting rules for roles - pointless if a user isn't signed in.
                        doAction( 'blockVisibility.onChange.userRole', 'block-visibility/onChange', option, props );

                    } }
                />
            </PanelRow>
        </PanelBody>

    );

} );
import { PanelBody, PanelRow } from '@wordpress/components';
import { withInstanceId } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

import { CheckboxGroupCheckbox }  from './content-visibility-role-checkbox';

function ContentVisibilityUserRolePanelBodyControl( { instanceId, props } ) {

    // Fetch the  roles from PHP
    const userRoles = BlockVisibilityUserRole.roles;

    // userRoles is an array of objects, for each registered user role.
    // Each role object looks like: {label: "Administrator", value: "administrator"}

    const id = `bv-roles-${ instanceId }`;

    return (
        <PanelBody
            title={ __( 'User Role', 'content-visibility-user-role' ) }
            initialOpen={ false }
            className="content-visibility-control-panel content-visibility-user-role-controls"
        >
            <PanelRow>
                <ul>
                {
                    userRoles.map( role => (
                        <CheckboxGroupCheckbox name={ id } id={ id } props={ props } role={ role } key={ props.clientId + role.value } />
                    ) )
                }
                </ul>
            </PanelRow>

            { props.attributes.contentVisibility && (
                <p className="user-role-help-intro content-visibility-help-text">{ __( 'Select one more more roles to whom this block will be ' + props.attributes.contentVisibility + '. If no roles are selected, this block will be ' + props.attributes.contentVisibility + ' regardless of a user\'s role.', 'content-visibility-user-role' ) }</p>
            ) }
        </PanelBody>
    );

}

export default withInstanceId( ContentVisibilityUserRolePanelBodyControl );
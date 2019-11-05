import { CheckboxControl } from '@wordpress/components';

export const CheckboxGroupCheckbox = ( data ) => {
    let { props, role } = { ...data };

    // Which role is this for?
    let roleSlug = role.value;

    // What data has been persisted in the db?
    let persistedData = props.attributes.blockVisibilityRules;

    // If we have persisted data for this role, and it is set to "1" then the checkbox should be checked
    // otherwise we fall back to whatever isChecked is which will change when someone alters the value of the checkbox
    let thisChecked = persistedData.userRole.hasOwnProperty( roleSlug ) && '1' === props.attributes.blockVisibilityRules.userRole[ roleSlug ];

    return (
        <CheckboxControl
            label={ role.label }
            checked={ thisChecked }
            onChange={ ( isChecked ) => {
                props.setAttributes( {
                    blockVisibilityRules: {
                        ...props.attributes.blockVisibilityRules,
                        userRole: {
                            ...props.attributes.blockVisibilityRules.userRole,
                            [roleSlug]: isChecked ? '1' : '0'
                        }
                    },
                } );
            }}
        />
    );

};
'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRows from './FormRows'
import FormPanel from './FormPanel'

export default function FormContainer(props) {
    const {
        template,
        ...otherProps
    } = props

    if (template.panel !== false){
        return (
            <FormPanel
                template={template.panel}
                {...otherProps}
            />
        )
    }

    return (
        <div className={template.class}>
            <FormRows
                template={template.rows}
                {...otherProps}
            />
        </div>
    )
}

FormContainer.propTypes = {
    template: PropTypes.object.isRequired,
}

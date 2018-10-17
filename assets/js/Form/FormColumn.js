'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormColumnContent from './FormColumnContent'

export default function FormColumn(props) {
    const {
        template,
        ...otherProps
    } = props

    return (
        <div className={template.class}>
            <FormColumnContent
                template={template}
                {...otherProps}
            />
        </div>
    )
}

FormColumn.propTypes = {
    template: PropTypes.object.isRequired,
}

'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormColumnContent from './FormColumnContent'

export default function FormColumn(props) {
    const {
        column,
        ...otherProps
    } = props

    return (
        <div className={column.class}>
            <FormColumnContent
                column={column}
                {...otherProps}
            />
        </div>
    )
}

FormColumn.propTypes = {
    column: PropTypes.object.isRequired,
}

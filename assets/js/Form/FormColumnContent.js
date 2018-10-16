'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormElementSelect from './FormElementSelect'

export default function FormColumnContent(props) {
    const {
        column,
        ...otherProps
    } = props

    if (column.form !== false)
    {
        const formElements = Object.keys(column.form).map(key => {
            const style = column.form[key]
            return (
                <FormElementSelect
                    style={style}
                    name={key}
                    {...otherProps}
                    key={key}
                />
            )
        })
        return (<span>{formElements}</span>)
    }

    return (
        <div>Form Column Content</div>
    )
}

FormColumnContent.propTypes = {
    column: PropTypes.object.isRequired,
}

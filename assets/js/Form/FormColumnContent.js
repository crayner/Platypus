'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormElementSelect from './FormElementSelect'

export default function FormColumnContent(props) {
    const {
        template,
        ...otherProps
    } = props

    if (template.form !== false)
    {
        const formElements = Object.keys(template.form).map(key => {
            const style = template.form[key]
            return (
                <FormElementSelect
                    style={style}
                    name={key}
                    key={key}
                    {...otherProps}
                />
            )
        })
        return (<span>{formElements}</span>)
    }

    console.log(template,otherProps)
    return (
        <div>Form Column Content</div>
    )
}

FormColumnContent.propTypes = {
    template: PropTypes.object.isRequired,
}

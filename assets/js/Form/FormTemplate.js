'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function FormTemplate(props) {
    const {
        form,
        ...otherProps
    } = props

    console.log(form)

    return (
        <div>Template</div>
    )
}

FormTemplate.propTypes = {
    form: PropTypes.object.isRequired,
}

'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRows from './FormRows'

export default function FormPage(props) {
    const {
        page,
        ...otherProps
    } = props

    if (page.container !== false)
        return (
            <div>Do a Container</div>
        )

    return (
        <FormRows
            rows={page.rows}
            {...otherProps}
        />
    )
}

FormPage.propTypes = {
    page: PropTypes.object.isRequired,
}

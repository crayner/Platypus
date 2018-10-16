'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRows from './FormRows'
import FormPanel from './FormPanel'

export default function FormPage(props) {
    const {
        page,
        ...otherProps
    } = props

    if (page.container !== false)
        if (page.container.panel !== false)
            return (
                <FormPanel page={page} {...otherProps}/>
            )
        return (
            <div className={page.container.class}><FormRows rows={page.rows} {...otherProps}/></div>
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

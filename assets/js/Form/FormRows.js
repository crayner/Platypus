'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRow from './FormRow'

export default function FormRows(props) {
    const {
        rows,
        ...otherProps
    } = props


    const rowContent = rows.map((row, key) => {
        return (
            <FormRow
                key={key}
                {...otherProps}
                row={row}
            />
        )
    })

    return (
        <span>{rowContent}</span>
    )
}

FormRows.propTypes = {
    rows: PropTypes.array.isRequired,
}

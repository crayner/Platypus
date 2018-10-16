'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormColumn from './FormColumn'

export default function FormRow(props) {
    const {
        row,
        ...otherProps
    } = props

    const columns = row.columns.map((column, key) => {
        return (
            <FormColumn
                key={key}
                column={column}
                {...otherProps}
            />
        )
    })

    return (
        <div className={row.class}>{columns}</div>
    )
}

FormRow.propTypes = {
    row: PropTypes.object.isRequired,
}

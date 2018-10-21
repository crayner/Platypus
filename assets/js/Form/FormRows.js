'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRow from './FormRow'

export default function FormRows(props) {
    const {
        template,
        collectionKey,
        ...otherProps
    } = props

    if (template === false)
        return ''

    const rowContent = template.map((row, key) => {
        let rowKey = key
        if (collectionKey !== '')
            rowKey = collectionKey + '_' + key
        return (
            <FormRow
                template={row}
                key={rowKey}
                {...otherProps}
            />
        )
    })

    return (
        <span>{rowContent}</span>
    )
}

FormRows.propTypes = {
    template: PropTypes.oneOfType([
        PropTypes.array,
        PropTypes.bool,
    ]).isRequired,
    collectionKey: PropTypes.string,
}

FormRows.defaultProps = {
    collectionKey: '',
}

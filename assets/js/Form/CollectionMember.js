'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRows from './FormRows'

export default function CollectionMember(props) {
    const {
        template,
        ...otherProps
    } = props

    return (
        <FormRows
            template={template.rows}
            {...otherProps}
        />
    )
}

CollectionMember.propTypes = {
    template: PropTypes.object.isRequired,
}

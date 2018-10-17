'use strict';

import React from "react"
import PropTypes from 'prop-types'
import CollectionType from './CollectionType'

export default function RenderCollection(props) {
    const {
        form,
        ...otherProps
    } = props

    console.log(form, otherProps)

    return (
        <span>
            <CollectionType
                form={form}
                {...otherProps}
            />
            <hr/>
        </span>
    )
}

RenderCollection.propTypes = {
    form: PropTypes.object.isRequired,
}

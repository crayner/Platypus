'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function TimetableHeader(props) {
    const {
        header,
    } = props

    return (
        <div className={'row'}>
            <div className={'col-12 text-uppercase'}>
                <h3>{header.title}</h3>
            </div>
        </div>
    )
}

TimetableHeader.propTypes = {
    header: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]),
}

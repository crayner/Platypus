'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function PaginationCell(props) {
    const {
        definition,
        data,
    } = props


    const host = window.location.protocol + '//' + window.location.hostname + '/'

    if (definition.display)
        if (definition.style === 'text')
            return (
                <div className={'col-' + definition.size}>{data[definition.name]}</div>
            )
        else if (definition.style === 'photo') {
            if (data[definition.name] === null)
                return (
                    <div className={'col-' + definition.size}>
                        <img src={host + definition.options.default} width={definition.options.width} />
                    </div>
                )
            else
                return (
                    <div className={'col-' + definition.size}>
                        <img src={host + data[definition.name]} width={definition.options.width} />
                    </div>
                )
        }
    return null
}

PaginationCell.propTypes = {
    definition: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
}

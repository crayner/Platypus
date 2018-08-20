'use strict';

import React from "react"
import PropTypes from 'prop-types'
import PaginationCell from './PaginationCell'

export default function PaginationRow(props) {
    const {
        item,
        columnDefinitions,
    } = props

    var cells = Object.keys(columnDefinitions).map(key =>
        <PaginationCell
            definition={columnDefinitions[key]}
            key={key}
            name={key}
            data={item}
        />
    )

    return (
        <div className={'row'}>
            {cells}
        </div>
    )
}

PaginationRow.propTypes = {
    item: PropTypes.object.isRequired,
    columnDefinitions: PropTypes.object.isRequired,
}

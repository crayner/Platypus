'use strict';

import React from "react"
import PropTypes from 'prop-types'
import PaginationCell from './PaginationCell'

export default function PaginationRow(props) {
    const {
        item,
        columnDefinitions,
        translations,
    } = props

    var cells = Object.keys(columnDefinitions).map(key =>
        <PaginationCell
            definition={columnDefinitions[key]}
            key={key}
            name={key}
            data={item}
            translations={translations}
        />
    )

    return (
        <div className='row row-striped'>
            {cells}
        </div>
    )
}

PaginationRow.propTypes = {
    item: PropTypes.object.isRequired,
    columnDefinitions: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
}
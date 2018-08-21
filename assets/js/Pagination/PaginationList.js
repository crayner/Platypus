'use strict';

import React from "react"
import PropTypes from 'prop-types'
import PaginationRow from './PaginationRow'

export default function PaginationList(props) {
    const {
        rows,
        translations,
        columnDefinitions,
    } = props


    return rows.map(function(item){
        return <PaginationRow
            item={item}
            columnDefinitions={columnDefinitions}
            key={item.id}
            translations={translations}
        />
    })
}


PaginationList.propTypes = {
    rows: PropTypes.array.isRequired,
    translations: PropTypes.object.isRequired,
    columnDefinitions: PropTypes.object.isRequired,
}

'use strict';

import React from "react"
import PropTypes from 'prop-types'
import PaginationRow from './PaginationRow'

export default function PaginationList(props) {
    const {
        rows,
        translations,
        columnDefinitions,
        actions,
        ...otherProps,
    } = props


    return rows.map(function(item){
        return <PaginationRow
            item={item}
            columnDefinitions={columnDefinitions}
            key={item.id}
            translations={translations}
            actions={actions}
            {...otherProps}
        />
    })
}


PaginationList.propTypes = {
    rows: PropTypes.array.isRequired,
    translations: PropTypes.object.isRequired,
    actions: PropTypes.object.isRequired,
    columnDefinitions: PropTypes.object.isRequired,
}

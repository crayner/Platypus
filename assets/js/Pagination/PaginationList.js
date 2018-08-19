'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function PaginationList(props) {
    const {
        rows,
        translations,
        locale,
    } = props


    function buildRows(){
        return rows.map(function(item, i){
            return <div className={'row'} key={item.id}>{item.surname} {item.firstName}</div>
        })
    }

    return (
        buildRows()
    )
}


PaginationList.propTypes = {
    locale: PropTypes.string.isRequired,
    rows: PropTypes.array.isRequired,
    translations: PropTypes.object.isRequired,
}

'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'

export default function PaginationFilterNotices(props) {
    const {
        translations,
        filterValue,
        filterLabels,
    } = props

    const buttons = filterValue.map(function(value) {
        return (
            <span key={value}>
                <span className={'btn-dark'}>&nbsp;{translateMessage(translations, filterLabels[value])}&nbsp;</span>
                <span>&nbsp;</span>
            </span>
        )
    })

    if (filterValue.length > 0)
        return (
            <span className='text-muted'>
                {translateMessage(translations, 'pagination.filter.by')}
                {buttons}
            </span>
        )
    return null
}

PaginationFilterNotices.propTypes = {
    translations: PropTypes.object.isRequired,
    filterValue: PropTypes.array.isRequired,
    filterLabels: PropTypes.object.isRequired,
}

PaginationFilterNotices.defaultProps = {
    filterValue: []
}


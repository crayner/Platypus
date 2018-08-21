'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import {faSortAlphaDown} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'

export default function PaginationColumnHeader(props) {
    const {
        item,
        translations,
        sort,
    } = props

    if (item.display)
        return (
            <div className={'col-' + item.size}>{(sort === item.label)
                ? <FontAwesomeIcon icon={faSortAlphaDown}/>
                : ''}{' '}{translateMessage(translations, item.label)}</div>
        )
    return null
}

PaginationColumnHeader.propTypes = {
    item: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
    sort: PropTypes.string.isRequired,
}

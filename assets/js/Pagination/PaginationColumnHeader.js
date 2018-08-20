'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'

export default function PaginationColumnHeader(props) {
    const {
        item,
        translations,
    } = props

    if (item.display)
        return (
            <div className={'col-' + item.size}>{translateMessage(translations, item.label)}</div>
        )
    return null
}

PaginationColumnHeader.propTypes = {
    item: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
}

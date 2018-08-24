'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import {faSortAlphaDown, faSortAlphaUp} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import LabelSpan from './LabelSpan'

export default function PaginationColumnHeader(props) {
    const {
        item,
        translations,
        sort,
        orderBy,
    } = props

    if (item.class === null)
        item.class = ''

    if (item.display && typeof(item.label) === 'string')
        return (
            <div className={item.class + ' col-' + item.size}>{(sort === item.label)
                ? <FontAwesomeIcon icon={(orderBy === 1 ? faSortAlphaDown : faSortAlphaUp)}/>
                : ''}{' '}{translateMessage(translations, item.label)}</div>
        )
    if (item.display && typeof(item.label) === 'object')
    {
        const title = Object.keys(item.label).map(key => {
            const label = item.label[key]

            return <LabelSpan
                    join={label.join}
                    className={label.class}
                    label={label.label}
                    key={key}
                    translations={translations}
                />
        })
        return (
            <div className={item.class + ' col-' + item.size}>
                {title}
            </div>
        )
    }
    return null
}

PaginationColumnHeader.propTypes = {
    item: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
    sort: PropTypes.string.isRequired,
    orderBy: PropTypes.number.isRequired,
}


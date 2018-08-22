'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import PaginationCombineCell from './PaginationCombineCell'

export default function PaginationCell(props) {
    const {
        definition,
        data,
        translations,
    } = props

    const host = window.location.protocol + '//' + window.location.hostname + '/'

    if (definition.display)
        if (definition.style === 'text')
            return (
                <div className={definition.class + ' card-text col-' + definition.size}>{(definition.translate === false || data[definition.name] === '' ||  data[definition.name] === null ? data[definition.name] : translateMessage(translations, definition.translate + data[definition.name]))}</div>
            )
        else if (definition.style === 'photo') {
            if (data[definition.name] === null)
                return (
                    <div className={definition.class + ' card-img-top col-' + definition.size}>
                        <img src={host + definition.options.default} width={definition.options.width} />
                    </div>
                )
            else
                return (
                    <div className={definition.class + ' col-' + definition.size}>
                        <img src={host + data[definition.name]} width={definition.options.width} />
                    </div>
                )
        } else if (definition.style === 'combine') {
            return (
                <div className={definition.class + ' card-text col-' + definition.size}>
                    <PaginationCombineCell
                        definition={definition}
                        data={data}
                        translations={translations}
                    />
                </div>
            )
        }
        return null
}

PaginationCell.propTypes = {
    definition: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
}

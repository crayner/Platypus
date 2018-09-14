'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import PaginationCombineCell from './PaginationCombineCell'
import Parser from 'html-react-parser';


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
        else if (definition.style === 'html')
            return (
                <div className={definition.class + ' card-text col-' + definition.size}>
                    {Parser(data[definition.name])}
                </div>
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
        } else if (definition.style === 'array') {
            const content = data[definition.name].map((name, i) =>
                <span key={i}>{i > 0 && definition.options.join === '<br />' ? <br />: i > 0 ? definition.options.join : ''}{name}</span>
            )
            return (
                <div className={definition.class + ' col-' + definition.size}>
                    {content}
                </div>
            )
        } else
            return (
                <div className={definition.class + ' col-' + definition.size}>
                    {'Content Style not defined.'}
                </div>
            )
        return ''
}

PaginationCell.propTypes = {
    definition: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
}

'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'

export default function PaginationCombineCell(props) {
    const {
        definition,
        data,
        translations,
    } = props

    const content = definition.options.combine.map(name => getContent(name))
    const display = content.map((text, i) =>
        <span key={i}>
            {text}
            {definition.options.join === 'nl' ? <br/> : definition.options.join}
        </span>
    )

    function getContent(name)
    {
        if (typeof definition.translate[name] !== 'undefined')
            return translateMessage(translations, definition.translate[name] + data[name])
        return data[name];
    }

    return (
        <section>{display}</section>
    )

}

PaginationCombineCell.propTypes = {
    definition: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
}

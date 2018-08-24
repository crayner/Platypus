'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import ContentSpan from './ContentSpan'

export default function PaginationCombineCell(props) {
    const {
        definition,
        data,
        translations,
    } = props

    const content = Object.keys(definition.options.combine).map(name => getContentDetails(name))
    let xxx = Object.keys(content).map(key => content[key])

    const display = xxx.map((content, key) =>
        <ContentSpan
            content={content}
            key={key}
        />
    )

    function getContentDetails(name)
    {
        let content = {}

        content['options'] = definition.options.combine[name]

        if (typeof(content['options'].translate) === 'string')
            content['content'] = translateMessage(translations, content['options'].translate + data[name])
        else
            content['content'] = data[name];

        return content
    }

    return xxx.map(function(content, key) {
        return <ContentSpan
            content={content}
            key={key}
        />
    })

}

PaginationCombineCell.propTypes = {
    definition: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
}

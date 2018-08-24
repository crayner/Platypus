'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'

export default function LabelSpan(props){
    const {
        label,
        className,
        join,
        translations,
    } = props

    if (join === '' && className === '')
        return translateMessage(translations, label)

    return (
        <span>
            {join === '<br />' ? <br /> : join}<span className={className}>{translateMessage(translations, label)}</span>
        </span>
    )
}

LabelSpan.propTypes = {
    label: PropTypes.string.isRequired,
    translations: PropTypes.object.isRequired,
    className: PropTypes.string.isRequired,
    join: PropTypes.string.isRequired,
}

LabelSpan.defaultProps = {
    className: '',
    join: '',
}



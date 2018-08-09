'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import "../Component/MessageTranslator"
import { translateMessage } from "../Component/MessageTranslator"
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faComment } from '@fortawesome/free-solid-svg-icons'

export default function MessageIcon(props) {
    const {
        messageCount,
        translations
    } = props;


    var title = translateMessage(translations, 'message.count', {'%{count}': messageCount})

    return (
        <span style={{float: 'right', top: '10px', left: '4px'}} title={title} className={'fa-layers fa-fw'}>
            <FontAwesomeIcon icon={faComment} />
            <span className="fa-layers-text fa-inverse" data-fa-transform="shrink-9 up-1" style={{fontWeight:300}}>{messageCount}</span>
        </span>
    )
}

MessageIcon.propTypes = {
    messageCount: PropTypes.number.isRequired,
    translations: PropTypes.object.isRequired,
}



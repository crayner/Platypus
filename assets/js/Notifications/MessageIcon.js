'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import "../Component/MessageTranslator"
import { translateMessage } from "../Component/MessageTranslator"
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faBell } from '@fortawesome/free-solid-svg-icons'

export default function MessageIcon(props) {
    const {
        messageCount,
        translations
    } = props;


    var title = translateMessage(translations, 'message.count', {'%{count}': messageCount})

    return (
        <span style={{float: 'right'}} title={title}>
            <FontAwesomeIcon icon={faBell} />
        </span>
    )
}

MessageIcon.propTypes = {
    messageCount: PropTypes.number.isRequired,
    translations: PropTypes.object.isRequired,
}



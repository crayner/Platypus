'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import "../Component/MessageTranslator";
import { translateMessage } from "../Component/MessageTranslator";

export default function MessageIcon(props) {
    const {
        messageCount,
        translations
    } = props;


    var title = translateMessage(translations, 'message.count', {'%{count}': messageCount})

    return (
        <span style={{float: 'right'}} title={title}>
            <span className={"fas fa-bell" }></span>
        </span>
    )
}

MessageIcon.propTypes = {
    messageCount: PropTypes.number.isRequired,
    translations: PropTypes.object.isRequired,
}

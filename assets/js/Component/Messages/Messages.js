'use strict';

import React from "react"
import PropTypes from 'prop-types'
import Message from './Message'

export default function  Messages(props) {
    const {
        translations,
        messages,
        cancelMessage,
    } = props;

    if (Object.keys(messages).length === 0)
        return (
            <section></section>
        )

    const cells = Object.keys(messages).map(function (key) {
        const message = messages[key]
        return <Message
            message={message}
            key={'message_' + message.id}
            translations={translations}
            cancelMessage={cancelMessage}
        />
    })

    return (
        <section>
            {cells}
        </section>
    )
}

Messages.propTypes = {
    messages: PropTypes.oneOfType([
        PropTypes.array,
        PropTypes.object,
    ]).isRequired,
    translations: PropTypes.object.isRequired,
    cancelMessage: PropTypes.func.isRequired,
}

Messages.defaultProps = {
    messages: {},
}


'use strict';

import React from "react"
import PropTypes from 'prop-types'
import Message from './Message'

export default function  Messages(props) {
    const {
        messages,
        ...otherProps
    } = props;

    if (Object.keys(messages).length === 0)
        return ''

    const cells = Object.keys(messages).map(key => {
        const message = messages[key]
        message.id = key
        return <Message
            message={message}
            key={'message_' + message.id}
            {...otherProps}
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
}

Messages.defaultProps = {
    messages: {},
}


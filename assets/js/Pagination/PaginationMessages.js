'use strict';

import React from "react"
import PropTypes from 'prop-types'
import PaginationMessage from './PaginationMessage'

export default function PaginationMessages(props) {
    const {
        messages,
        translations,
        cancelMessage,
    } = props

    if (Object.keys(messages).length === 0)
        return (
            <section></section>
        )

    const cells = Object.keys(messages).map(function(key){
        const message = messages[key]
        return <PaginationMessage
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

PaginationMessages.propTypes = {
    messages: PropTypes.oneOfType([
        PropTypes.array,
        PropTypes.object,
    ]).isRequired,
    translations: PropTypes.object.isRequired,
    cancelMessage: PropTypes.func.isRequired,
}


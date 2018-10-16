'use strict';

import React from "react"
import PropTypes from 'prop-types'
import Messages from '../Component/Messages/Messages'

export default function FormMessages(props) {
    const {
        form,
        translations,
        cancelMessage,
        ...otherProps
    } = props

    return (
        <Messages messages={form.errors} translations={translations} cancelMessage={cancelMessage}/>
    )
}

FormMessages.propTypes = {
    form: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
    cancelMessage: PropTypes.func.isRequired,
}

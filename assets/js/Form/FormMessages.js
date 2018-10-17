'use strict';

import React from "react"
import Messages from '../Component/Messages/Messages'

export default function FormMessages(props) {
    const {
        ...otherProps
    } = props

    return (
        <Messages
            {...otherProps}
        />
    )
}

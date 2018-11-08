'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'
import { faArrowUp } from '@fortawesome/free-solid-svg-icons'

export default function ButtonUp(props) {
    const {
        button,
        upButtonHandler,
        ...otherProps
    } = props

    if (button.colour === '' || typeof button.colour === 'undefined')
        button.colour = 'light'

    if (button.icon === false || typeof button.icon === 'undefined')
        button.icon = faArrowUp

    return (
        <Button
            button={button}
            upButtonHandler={upButtonHandler}
            {...otherProps}
        />
    )
}

ButtonUp.propTypes = {
    button: PropTypes.object.isRequired,
    upButtonHandler: PropTypes.func,
}

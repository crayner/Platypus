'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonDuplicate(props) {
    const {
        button,
        duplicateButtonHandler,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'light'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['fas','clone']

    return (
        <Button
            button={button}
            buttonHandler={duplicateButtonHandler}
            {...otherProps}
        />
    )
}

ButtonDuplicate.propTypes = {
    button: PropTypes.object.isRequired,
    duplicateButtonHandler: PropTypes.func,
}

'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonUp(props) {
    const {
        button,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'light'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['fas','arrow-up']

    return (
        <Button
            button={button}
            {...otherProps}
        />
    )
}

ButtonUp.propTypes = {
    button: PropTypes.object.isRequired,
}

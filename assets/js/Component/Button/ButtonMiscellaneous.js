'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonMiscellaneous(props) {
    const {
        button,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'light'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['far','edit']

    return (
        <Button
            button={button}
            {...otherProps}
        />
    )
}

ButtonMiscellaneous.propTypes = {
    button: PropTypes.object.isRequired,
}

'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonOff(props) {
    const {
        button,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'danger'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['far','thumbs-down']

    return (
        <Button
            button={button}
            {...otherProps}
        />
    )
}

ButtonOff.propTypes = {
    button: PropTypes.object.isRequired,
}

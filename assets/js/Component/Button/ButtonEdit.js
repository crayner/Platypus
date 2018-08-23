'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonEdit(props) {
    const {
        button,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'info'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['far','edit']

    return (
        <Button
            button={button}
            {...otherProps}
        />
    )
}

ButtonEdit.propTypes = {
    button: PropTypes.object.isRequired,
}

'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonOn(props) {
    const {
        button,
        onButtonHandler,
        ...otherProps
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'success'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['far','thumbs-up']

    return (
        <Button
            button={button}
            buttonHandler={onButtonHandler}
            {...otherProps}
        />
    )
}

ButtonOn.propTypes = {
    button: PropTypes.object.isRequired,
    onButtonHandler: PropTypes.func,
}

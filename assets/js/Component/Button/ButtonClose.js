'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonClose(props) {
    const {
        button,
        closeButtonHandler,
        ...otherProps
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'primary'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['far','times-circle']

    return (
        <Button
            button={button}
            buttonHandler={closeButtonHandler}
            {...otherProps}
        />
    )
}

ButtonClose.propTypes = {
    button: PropTypes.object.isRequired,
    closeButtonHandler: PropTypes.func,
}

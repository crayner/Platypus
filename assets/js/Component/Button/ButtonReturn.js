'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonReturn(props) {
    const {
        button,
        returnButtonHandler,
        ...otherProps
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'primary'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['far','hand-point-left']

    return (
        <Button
            button={button}
            buttonHandler={returnButtonHandler}
            {...otherProps}
        />
    )
}

ButtonReturn.propTypes = {
    button: PropTypes.object.isRequired,
    returnButtonHandler: PropTypes.func,
}

'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonEdit(props) {
    const {
        button,
        editButtonHandler,
        ...otherProps
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'info'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['far','edit']

    return (
        <Button
            button={button}
            buttonHandler={editButtonHandler}
            {...otherProps}
        />
    )
}

ButtonEdit.propTypes = {
    button: PropTypes.object.isRequired,
    editButtonHandler: PropTypes.func.isRequired,
}

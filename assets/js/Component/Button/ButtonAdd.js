'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonAdd(props) {
    const {
        button,
        addButtonHandler,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'info'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['fas','plus-circle']

    return (
        <Button
            button={button}
            buttonHandler={addButtonHandler}
            {...otherProps}
        />
    )
}

ButtonAdd.propTypes = {
    button: PropTypes.object.isRequired,
    addButtonHandler: PropTypes.func.isRequired,
}

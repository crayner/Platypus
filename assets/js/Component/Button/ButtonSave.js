'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonSave(props) {
    const {
        button,
        saveFunction,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'success'

    if (button.type === '' || typeof(button.type) === 'undefined')
        button.type = 'button'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['fas','download']

    return (
        <Button
            button={button}
            buttonClickAction={saveFunction}
            {...otherProps}
        />
    )
}

ButtonSave.propTypes = {
    button: PropTypes.object.isRequired,
    saveFunction: PropTypes.func.isRequired,
}

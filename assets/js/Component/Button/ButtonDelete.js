'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonDelete(props) {
    const {
        button,
        deleteButtonHandler,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'warning'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['far','trash-alt']

    return (
        <Button
            button={button}
            buttonHandler={deleteButtonHandler}
            {...otherProps}
        />
    )
}

ButtonDelete.propTypes = {
    button: PropTypes.object.isRequired,
    deleteButtonHandler: PropTypes.func.isRequired,
}

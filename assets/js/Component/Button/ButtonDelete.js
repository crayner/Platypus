'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonDelete(props) {
    const {
        button,
        deleteElement,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'warning'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['far','trash-alt']

    return (
        <Button
            button={button}
            buttonClickAction={deleteElement}
            {...otherProps}
        />
    )
}

ButtonDelete.propTypes = {
    button: PropTypes.object.isRequired,
    deleteElement: PropTypes.func.isRequired,
}

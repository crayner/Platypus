'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonRefresh(props) {
    const {
        button,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'warning'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['fas','redo']

    return (
        <Button
            button={button}
            {...otherProps}
        />
    )
}

ButtonAdd.propTypes = {
    button: PropTypes.object.isRequired,
}

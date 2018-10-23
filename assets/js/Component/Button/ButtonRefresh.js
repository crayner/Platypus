'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonRefresh(props) {
    const {
        button,
        refreshButtonHandler,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'warning'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['fas','redo']

    return (
        <Button
            button={button}
            refreshButtonHandler={refreshButtonHandler}
            {...otherProps}
        />
    )
}

ButtonRefresh.propTypes = {
    button: PropTypes.object.isRequired,
    refreshButtonHandler: PropTypes.func,
}

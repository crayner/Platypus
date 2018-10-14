'use strict';

import React from 'react';
import PropTypes from 'prop-types'
import Button from './Button'

export default function ButtonSave(props) {
    const {
        button,
        saveCollection,
        ...otherProps,
    } = props

    if (button.colour === '' || typeof(button.colour) === 'undefined')
        button.colour = 'success'

    if (button.type === '' || typeof(button.type) === 'undefined')
        button.type = 'submit'

    if (button.icon === false || typeof(button.icon) === 'undefined')
        button.icon = ['fas','download']

    return (
        <Button
            button={button}
            buttonClickAction={saveCollection}
            {...otherProps}
        />
    )
}

ButtonSave.propTypes = {
    button: PropTypes.object.isRequired,
}

'use strict';

import React from 'react';
import PropTypes from 'prop-types';
import ButtonDelete from './ButtonDelete'
import ButtonEdit from './ButtonEdit'

export default function ButtonManager(props) {
    const {
        button,
       ...otherProps,
    } = props

    if (button.type === 'edit')
        return (
            <ButtonEdit
                button={button}
                {...otherProps}
            />
        )
    if (button.type === 'delete')
        return (
            <ButtonDelete
                button={button}
                {...otherProps}
            />
        )
    return (null)
}

ButtonManager.propTypes = {
    button: PropTypes.object.isRequired,
}

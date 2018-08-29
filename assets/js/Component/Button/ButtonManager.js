'use strict';

import React from 'react';
import PropTypes from 'prop-types';
import ButtonDelete from './ButtonDelete'
import ButtonEdit from './ButtonEdit'
import ButtonMiscellaneous from './ButtonMiscellaneous'
import ButtonAdd from './ButtonAdd'

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
    if (button.type === 'add')
        return (
            <ButtonAdd
                button={button}
                {...otherProps}
            />
        )
    return (
        <ButtonMiscellaneous
            button={button}
            {...otherProps}
        />
    )
}

ButtonManager.propTypes = {
    button: PropTypes.object.isRequired,
}

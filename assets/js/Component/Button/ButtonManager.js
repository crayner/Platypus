'use strict';

import React from 'react';
import PropTypes from 'prop-types';
import ButtonDelete from './ButtonDelete'
import ButtonEdit from './ButtonEdit'
import ButtonMiscellaneous from './ButtonMiscellaneous'
import ButtonAdd from './ButtonAdd'
import ButtonRefresh from './ButtonRefresh'
import ButtonReturn from './ButtonReturn'
import ButtonClose from './ButtonClose'
import ButtonSave from './ButtonSave'
import ButtonSubmit from './ButtonSubmit'
import ButtonUp from './ButtonUp'
import ButtonDown from './ButtonDown'
import ButtonDuplicate from './ButtonDuplicate'

export default function ButtonManager(props) {
    const {
        button,
       ...otherProps,
    } = props

    if (typeof button.mergeClass === 'undefined')
        button.mergeClass = ''

    if (button.type === 'edit')
        return (
            <ButtonEdit
                button={button}
                {...otherProps}
            />
        )
    if (button.type === 'save')
        return (
            <ButtonSave
                button={button}
                {...otherProps}
            />
        )
    if (button.type === 'submit')
        return (
            <ButtonSubmit
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
    if (button.type === 'return')
        return (
            <ButtonReturn
                button={button}
                {...otherProps}
            />
        )
    if (button.type === 'refresh')
        return (
            <ButtonRefresh
                button={button}
                {...otherProps}
            />
        )
    if (button.type === 'close')
        return (
            <ButtonClose
                button={button}
                {...otherProps}
            />
        )
    if (button.type === 'up')
        return (
            <ButtonUp
                button={button}
                {...otherProps}
            />
        )
    if (button.type === 'down')
        return (
            <ButtonDown
                button={button}
                {...otherProps}
            />
        )
    if (button.type === 'duplicate')
        return (
            <ButtonDuplicate
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

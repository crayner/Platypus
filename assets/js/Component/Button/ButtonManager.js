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

    if (button.display === false)
        return ''

    let buttonClone = {...button}
    buttonClone.buttonType = button.type

    if (typeof buttonClone.mergeClass === 'undefined')
        buttonClone.mergeClass = ''

    if (buttonClone.type === 'edit')
        return (
            <ButtonEdit
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'save')
        return (
            <ButtonSave
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'submit')
        return (
            <ButtonSubmit
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'delete')
        return (
            <ButtonDelete
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'add')
        return (
            <ButtonAdd
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'return')
        return (
            <ButtonReturn
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'refresh')
        return (
            <ButtonRefresh
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'close')
        return (
            <ButtonClose
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'up')
        return (
            <ButtonUp
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'down')
        return (
            <ButtonDown
                button={buttonClone}
                {...otherProps}
            />
        )
    if (buttonClone.type === 'duplicate')
        return (
            <ButtonDuplicate
                button={buttonClone}
                {...otherProps}
            />
        )
    return (
        <ButtonMiscellaneous
            button={buttonClone}
            {...otherProps}
        />
    )
}

ButtonManager.propTypes = {
    button: PropTypes.object.isRequired,
}

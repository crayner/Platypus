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
       ...otherProps
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
                {...otherProps}
                button={buttonClone}
            />
        )
    if (buttonClone.type === 'save')
        return (
            <ButtonSave
                {...otherProps}
                button={buttonClone}
            />
        )
    if (buttonClone.type === 'submit')
        return (
            <ButtonSubmit
                {...otherProps}
                button={buttonClone}
            />
        )
    if (buttonClone.type === 'delete')
        return (
            <ButtonDelete
                {...otherProps}
                button={buttonClone}
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
                {...otherProps}
                button={buttonClone}
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
                {...otherProps}
                button={buttonClone}
            />
        )
    if (buttonClone.type === 'up')
        return (
            <ButtonUp
                {...otherProps}
                button={buttonClone}
            />
        )
    if (buttonClone.type === 'down')
        return (
            <ButtonDown
                {...otherProps}
                button={buttonClone}
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
            {...otherProps}
            button={buttonClone}
        />
    )
}

ButtonManager.propTypes = {
    button: PropTypes.object.isRequired,
}

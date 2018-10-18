'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormContainer from './FormContainer'
import FormElementSelect from './FormElementSelect'
import ButtonManager from '../Component/Button/ButtonManager'

export default function FormColumn(props) {
    const {
        template,
        allow_up,
        allow_down,
        allow_delete,
        allow_duplicate,
        first,
        last,
        form,
        collection_buttons,
        default_buttons,
        button_merge_class,
        ...otherProps
    } = props

    if (template.container !== false)
        return (
            <FormContainer
                template={template.container}
                form={form}
                {...otherProps}
            />
        )

    let buttons = []
    if (template.buttons !== false) {
        buttons = Object.keys(template.buttons).map(key => {
            const button = Object.assign(template.buttons[key],{mergeClass: button_merge_class})
            return (
                <ButtonManager
                    key={key}
                    button={button}
                    {...otherProps}
                />
            )
        })
    }

    function buildButton(button, key){
        button = Object.assign( button, {mergeClass: button_merge_class})
        if(button.type === 'up')
            console.log(button)
        return (
            <ButtonManager
                button={button}
                key={key}
                {...otherProps}
            />
        )
    }

    if (template.collection_actions){
        let button = {}
        if (allow_delete){
            button = {...default_buttons.delete}
            if (typeof collection_buttons.delete !== 'undefined')
                button = {...collection_buttons.delete}
            buttons.unshift(buildButton(button, 'delete'))
        }
        if (allow_duplicate)
        {
            button = {...default_buttons.duplicate}
            if (typeof collection_buttons.dumplicate !== 'undefined')
                button = {...collection_buttons.duplicate}
            buttons.unshift(buildButton(button, 'duplicate'))
        }
        if (allow_down && last !== form.name)
        {
            button = {...default_buttons.down}
            if (typeof collection_buttons.down !== 'undefined')
                button = {...collection_buttons.down}
            buttons.unshift(buildButton(button, 'down'))
        }
        if (allow_up && first !== form.name)
        {
            button = {...default_buttons.up}
            if (typeof collection_buttons.up !== 'undefined')
                button = {...collection_buttons.up}
            console.log(button)
            buttons.unshift(buildButton(button, 'up'))
        }
    }

    if (template.form !== false) {
        const formElements = Object.keys(template.form).map(key => {
            const style = template.form[key]
            return (
                <FormElementSelect
                    style={style}
                    name={key}
                    key={key}
                    form={form}
                    template={template}
                    {...otherProps}
                />
            )
        })

        return (
            <div className={template.class}>
                {formElements}
                {buttons}
            </div>
        )
    }

    return (
        <div className={template.class}>
            {template.label}
            {buttons}
        </div>
    )
}

FormColumn.propTypes = {
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
    collection_buttons: PropTypes.object,
    default_buttons: PropTypes.object,
    allow_delete: PropTypes.bool,
    allow_up: PropTypes.bool,
    allow_down: PropTypes.bool,
    allow_duplicate: PropTypes.bool,
    first: PropTypes.string,
    last: PropTypes.string,
    button_merge_class: PropTypes.string,
}

FormColumn.defaultProps = {
    button_merge_class: '',
    allow_delete: false,
    allow_up: false,
    allow_down: false,
    allow_duplicate: false,
}


'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'

export default function BootstrapInput(props) {
    const {
        translations,
        type,
        name,
        auto_complete,
        input_class,
        div_class,
        label_class,
        label,
        value,
        placeholder,
        required,
        help,
    } = props

    let id = name.replace('[', '_')
    id = id.replace(']', '')

    return (
        <div className={(div_class !== '' ? ' ' + div_class : div_class) + "form-group"}>
            <input type={type} id={id} name={name} autoComplete={auto_complete}
                   className={(input_class !== '' ? input_class + ' ' : input_class) + "form-control"} defaultValue={value ? value : ''}
                   placeholder={placeholder ? placeholder : ''} required={required} />
            {label !== false ?
                (<label className={"form-control-label" + (label_class? ' ' + label_class : '')} htmlFor={id}>{translateMessage(translations, label)}</label>) : ''}
            {required ? <span className="field-required"> {translateMessage(translations, 'form.required')} </span> : ''}
            {help ? <span className={'small text-muted'}><br />{translateMessage(translations, help)}</span>: ''}
        </div>

    )
}

BootstrapInput.propTypes = {
    translations: PropTypes.object.isRequired,
    type: PropTypes.string,
    name: PropTypes.string.isRequired,
    auto_complete: PropTypes.string,
    input_class: PropTypes.string,
    div_class: PropTypes.string,
    label_class: PropTypes.string,
    label: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.bool,
    ]),
    help: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.bool,
    ]),
    required: PropTypes.bool,
}

BootstrapInput.defaultProps = {
    type: 'text',
    auto_complete: 'off',
    input_class: '',
    label_class: '',
    div_class: '',
    label: false,
    required: false,
    help: false,
}

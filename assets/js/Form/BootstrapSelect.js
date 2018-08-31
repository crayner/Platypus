'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'

export default function BootstrapSelect(props) {
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
        optionList,
        required,
        translate,
        placeholder,
        help,
    } = props

    let id = name.replace('[', '_')
    id = id.replace(']', '')

    const options = optionList.map((option, key) => {
        return (<option value={option['type']} key={key}>{translate ? translateMessage(translations, option['label']) : option['label']}</option>)})

    return (
        <div className={(div_class !== '' ? ' ' + div_class : div_class) + "form-group"}>
            <select type={type} id={id} name={name} autoComplete={auto_complete} className={(input_class !== '' ? ' ' + input_class : input_class) + "form-control"}
                    defaultValue={value ? value : ''} required={required}>
                {placeholder !== false ? <option>{translateMessage(translations, placeholder)}</option>: ''}
                {options}
            </select>
            {label !== false ?
                (<label className={"form-control-label" + (label_class? ' ' + label_class : '')} htmlFor={id}>{translateMessage(translations, label)}</label>) : ''}
            {required === true ? <span className="field-required"> {translateMessage(translations, 'form.required')} </span> : ''}
            {help ? <span className={'small text-muted'}><br />{translateMessage(translations, help)}</span>: ''}
        </div>

    )
}

BootstrapSelect.propTypes = {
    translations: PropTypes.object.isRequired,
    type: PropTypes.string,
    name: PropTypes.string.isRequired,
    auto_complete: PropTypes.string,
    input_classe: PropTypes.string,
    div_class: PropTypes.string,
    label_class: PropTypes.string,
    required: PropTypes.bool,
    label: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.bool,
    ]),
    placeholder: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.bool,
    ]),
    help: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.bool,
    ]),
    optionList: PropTypes.array.isRequired,
    translate: PropTypes.bool,
}

BootstrapSelect.defaultProps = {
    type: 'text',
    auto_complete: 'off',
    input_class: '',
    label_class: '',
    div_class: '',
    label: false,
    required: false,
    translate: true,
    placeholder: false,
    help: false,
}

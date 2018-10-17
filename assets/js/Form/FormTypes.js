'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { FormGroup, ControlLabel, FormControl, HelpBlock } from 'react-bootstrap'
import {translateMessage} from '../Component/MessageTranslator'
import FormLabel from './FormLabel'
import FormHelp from './FormHelp'
import FormRequired from './FormRequired'

export default function FormTypes(props) {
    const {
        form,
        style,
        elementChange,
        getElementData,
        data,
        ...otherProps
    } = props

    const prefix = form.block_prefixes.reverse()

    const content = prefix.filter(type => {
        if (isFunction(type))
            return type
    })

    function textType() {
        if (style === 'widget')
            return textTypeWidget()
        return (
            <FormGroup
                controlId={form.id}
                //                validationState={this.getValidationState()}
            >
                { textTypeWidget() }
                <FormLabel label={form.label}/>
                <FormRequired required={form.required}/>
                <FormHelp help={form.help}/>
            </FormGroup>
        )
    }

    function textTypeWidget(){
        getElementData(form.full_name)
        return (
            <FormControl
                type="text"
                id={form.id}
                defaultValue={getElementData(form.full_name)}
                placeholder="Enter text"
                className={form.attr.class}
                name={form.full_name}
                onChange={((e) => elementChange(e, form.full_name))}
            />
        )
    }

    function formType() {
        if (style === 'widget')
            return formTypeWidget()
        return (
            <FormGroup
                controlId="formBasicText"
                //                validationState={this.getValidationState()}
            >
                { formTypeWidget() }{ showLabelHelp()}
            </FormGroup>
        )
    }

    function formTypeWidget(){
        return (
            <FormControl
                type="text"
                defaultValue={form.value}
                placeholder="Enter text"
                //                    onChange={this.handleChange}
            />
        )
    }

    if (content.length === 0) {
        console.error('No form type found')
        console.log(prefix)
    }

    switch (content[0]) {
        case 'hidden':
            return hiddenType()
        case 'choice':
            return choiceType()
        case 'text':
            return textType()
        case 'time':
            return timeType()
        default:
            return formType()
    }

    function isFunction(type) {
        switch (type) {
            case 'hidden':
            case 'choice':
            case 'time':
            case 'text':
                return true
            default:
                return false
        }
    }

    function timeType() {
        if (style === 'widget')
            return timeTypeWidget()
        return (
            <FormGroup
                controlId={form.id}
                //                validationState={this.getValidationState()}
            >
                { timeTypeWidget() }{ showLabelHelp()}
            </FormGroup>
        )
    }

    function timeTypeWidget(){
        const hour = form.children[0]
        const minute = form.children[1]
        let second = 'undefined'
        if (typeof form.children[2] !== 'undefined')
            second = form.children[2]

        const width = 90 / form.children.length
        return (
            <div id={form.id} autoComplete={'off'} className={'form-inline' + (typeof form.attr.class === 'undefined' ? '' : ' ' + form.attr.class)}>
                <FormControl
                    componentClass="select"
                    id={hour.id}
                    defaultValue={hour.value}
                    className={hour.attr.class}
                    style={{'width': width + '%'}}
                //                    onChange={this.handleChange}
                >
                    {
                        hour.choices.map((option, index) => {
                            return (<option key={index} value={option.value}>{option.label}</option>)
                        })
                    }

                </FormControl>:
                <FormControl
                    componentClass="select"
                    id={minute.id}
                    defaultValue={minute.value}
                    style={{'width': width + '%'}}
                    className={minute.attr.class}
                    //                    onChange={this.handleChange}
                >
                    {
                        minute.choices.map((option, index) => {
                            return (<option key={index} value={option.value}>{option.label}</option>)
                        })
                    }

                </FormControl>
                { second !== 'undefined' ?
                    <span>:<FormControl
                        componentClass="select"
                        id={second.id}
                        defaultValue={second.value}
                        style={{'width': width + '%'}}
                        className={second.attr.class}
                        //                    onChange={this.handleChange}
                    >
                        {
                            second.choices.map((option, index) => {
                                return (<option key={index} value={option.value}>{option.label}</option>)
                            })
                        }

                    </FormControl></span>
                    : ''
                }
            </div>
        )
    }

    function choiceType() {
        if (style === 'widget')
            return choiceTypeWidget()
        return (
            <FormGroup
                controlId={form.id}
                //                validationState={this.getValidationState()}
            >
                { choiceTypeWidget() }
                <FormLabel label={form.label}/>
                <FormRequired required={form.required}/>
                <FormHelp help={form.help}/>
            </FormGroup>
        )
    }

    function getChoiceList(){
        if (typeof form.choices === 'object')
            return Object.keys(form.choices).map(index => {
                const option = form.choices[index]
                return (<option key={index} value={option.value}>{option.label}</option>)
            })

        return form.choices.map((option, index) => {
            return (<option key={index} value={option.value}>{option.label}</option>)
        })
    }

    function choiceTypeWidget(){
        return (
            <FormControl
                componentClass="select"
                defaultValue={form.value}
                placeholder={form.placeholder}
                multiple={form.multiple}
                //                    onChange={this.handleChange}
            >
                {getChoiceList()}
            </FormControl>
        )
    }

    function hiddenType() {
        return (
            <FormControl
                type="hidden"
                defaultValue={form.value}
            />
        )
    }
}

FormTypes.propTypes = {
    elementChange: PropTypes.func.isRequired,
    getElementData: PropTypes.func.isRequired,
    form: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    style: PropTypes.string.isRequired,
}




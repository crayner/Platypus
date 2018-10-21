'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { FormGroup,  FormControl } from 'react-bootstrap'
import FormLabel from './FormLabel'
import FormHelp from './FormHelp'
import FormRequired from './FormRequired'
import FormErrors from './FormErrors'

export default function FormTypes(props) {
    const {
        form,
        style,
        elementChange,
        getElementData,
    } = props

    const prefix = form.block_prefixes.reverse()

    const content = prefix.filter(type => {
        if (isFunction(type))
            return type
    })

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

    function renderFormGroup(content, style, options){
        if (typeof options !== 'object')
            options = {}
        return (
            <FormGroup
                controlId={form.id}
                className={form.errors.length > 0 ? 'has-danger' : ''}
                {...options}
            >
                {content}
                <FormLabel label={form.label}/>
                <FormRequired required={form.required}/><br/>
                <FormErrors errors={form.errors}/>
                <FormHelp help={form.help}/>
            </FormGroup>
        )
    }

    function textType() {
        if (style === 'widget')
            return textTypeWidget()
        return renderFormGroup(textTypeWidget(), 'text')
    }

    function textTypeWidget(){
        getElementData(form.id)
        return (
            <FormControl
                type="text"
                id={form.id}
                defaultValue={getElementData(form.id)}
                placeholder="Enter text"
                className={form.attr.class}
                name={form.full_name}
                onChange={((e) => elementChange(e, form.id))}
            />
        )
    }

    function formType() {
        if (style === 'widget')
            return formTypeWidget()
        return renderFormGroup(formTypeWidget(), 'form')
    }

    function formTypeWidget(){
        return (
            <FormControl
                type="text"
                defaultValue={form.value}
                placeholder="Enter text"
                onChange={((e) => elementChange(e, form.id))}
            />
        )
    }

    function timeType() {
        if (style === 'widget')
            return timeTypeWidget()
        return renderFormGroup(timeTypeWidget(), 'time')
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
                    defaultValue={getElementData(hour.id)}
                    className={hour.attr.class}
                    style={{'width': width + '%'}}
                    onChange={((e) => elementChange(e, hour.id))}
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
                    defaultValue={getElementData(minute.id)}
                    style={{'width': width + '%'}}
                    className={minute.attr.class}
                    onChange={((e) => elementChange(e, minute.id))}
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
                        defaultValue={getElementData(second.id)}
                        style={{'width': width + '%'}}
                        className={second.attr.class}
                        onChange={((e) => elementChange(e, second.id))}
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
        return renderFormGroup(choiceTypeWidget(), 'choice')
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
                defaultValue={getElementData(form.id)}
                placeholder={form.placeholder}
                multiple={form.multiple}
                onChange={((e) => elementChange(e, form.id))}
            >
                {getChoiceList()}
            </FormControl>
        )
    }

    function hiddenType() {
        return (
            <FormControl
                type="hidden"
                defaultValue={getElementData(form.id)}
            />
        )
    }

    function getCollectionForm(container){
        return form.find(element => {
            if (element.name === container.form)
                return element
        })
    }
}

FormTypes.propTypes = {
    elementChange: PropTypes.func.isRequired,
    getElementData: PropTypes.func.isRequired,
    form: PropTypes.object.isRequired,
    style: PropTypes.string.isRequired,
}




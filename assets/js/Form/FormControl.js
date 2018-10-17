'use strict';

import React, { Component } from "react"
import FormRender from './FormRender'
import PropTypes from 'prop-types'
import {fetchJson} from '../Component/fetchJson'


export default class FormControl extends Component {
    constructor(props) {
        super(props)

        this.form = props.form
        this.data = props.data
        this.otherProps = {...props}

        this.messages = []
        this.state = {
            messages: this.messages,
            data: this.data,
            form: this.form
        }

        this.cancelMessage = this.cancelMessage.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.elementChange = this.elementChange.bind(this)
        this.getElementData = this.getElementData.bind(this)
        this.setElementData = this.setElementData.bind(this)
    }

    cancelMessage(id) {
        this.messages.splice(id, 1)
        this.setState({
            messages: this.messages,
            data: this.data,
            form: this.form
        })
    }

    elementChange(event, name){
        let x = name.split('[')
        x.shift()
        const value = event.target.value
        const y = x.map((name,key) => {
            x[key] = name.replace(']', '')
        })
        this.setState({
            data: this.setElementData(x, value, this.data)
        })
    }

    setElementData(keys, value, data){
        let w = keys

        if (w.length > 1) {
            let key = w.shift()
            data[key] = this.setElementData(w, value, data[key])
        } else
            data[w[0]] = value
        return data
    }

    handleSubmit(event) {
        event.preventDefault();

        fetchJson(
            this.otherProps.template.url,
            {
                method: 'post',
                body: JSON.stringify(this.state.data),
            },
            this.otherProps.locale
        ).then(data => {
            this.messages = data.messages
            this.data = data.data
            this.setState({
                messages: this.messages,
                data: this.data,
                form: this.form
            })
        })
    }

    getElementData(name){
        let x = name.split('[')
        x.shift()
        let w = this.state.data
        const y = x.map(key => {
            const q = key.replace(']', '')
            w = w[q]
        })
        return w
    }

    render() {
        return (
            <form name={this.otherProps.form.name} method={this.otherProps.template.method} onSubmit={this.handleSubmit}>
                <FormRender
                    cancelMessage={this.cancelMessage}
                    elementChange={this.elementChange}
                    getElementData={this.getElementData}
                    messages={this.state.messages}
                    data={this.state.data}
                    {...this.otherProps}
                />
            </form>
        )
    }
}

FormControl.PropsTypes = {
    form: PropTypes.object.isRequired,
}

'use strict';

import React, { Component } from "react"
import FormRender from './FormRender'
import PropTypes from 'prop-types'


export default class FormControl extends Component {
    constructor(props) {
        super(props)

        this.otherProps = {...props}
        this.cancelMessage = this.cancelMessage.bind(this)

        this.messages = []
        this.state = {
            messages: this.messages
        }

        this.cancelMessage = this.cancelMessage.bind(this)
    }

    cancelMessage(id) {
        this.messages.splice(id, 1)
        this.setState({
            messages: this.messages
        })
    }

    render() {
        return (
            <form name={this.otherProps.form.name} method={this.otherProps.template.method}>
                <FormRender
                    cancelMessage={this.cancelMessage}
                    messages={this.state.messages}
                    {...this.otherProps}
                />
            </form>
        )
    }
}

FormControl.PropsTypes = {
    form: PropTypes.object.isRequired,
}

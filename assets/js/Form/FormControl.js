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
        this.addCollectionElement = this.addCollectionElement.bind(this)
        this.deleteCollectionElement = this.deleteCollectionElement.bind(this)
        this.moveCollectionElementUp = this.moveCollectionElementUp.bind(this)
        this.moveCollectionElementDown = this.moveCollectionElementDown.bind(this)


        this.collectionFunctions = {
            addCollectionElement: this.addCollectionElement,
            deleteCollectionElement: this.deleteCollectionElement,
            moveCollectionElementUp: this.moveCollectionElementUp,
            moveCollectionElementDown: this.moveCollectionElementDown,
        }
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

    addCollectionElement(){
        const key = this.state.form.children.length
        let prototype = {...this.prototype}
        prototype = this.setCollectionMemberKey(prototype, key)

        let children = this.form.children
        children[key] = prototype
        this.form.children = children
        this.setState({
            form: this.createForm({...this.form}),
            messages: this.messages
        })
    }

    deleteCollectionElement(options){
        const element = this.form.children[options.eid]
        if (typeof element.data_id === 'undefined')
            this.removeElement(options.eid)
        else {
            const delete_url = this.getDeleteUrl(element.data_id)

            if (this.template.actions.delete.url_type === 'json') {
                fetchJson(delete_url, {}, this.locale)
                    .then((data) => {
                        this.messages = typeof data.messages !== 'undefined' ? data.messages : []
                        if (data.status === 'success' || data.status === 'default')
                            this.removeElement(options.eid)
                        else {
                            this.setState({
                                messages: this.messages
                            })
                        }
                    })
            }
        }
    }

    moveCollectionElementUp(url, type, options){
        const element = this.form.children[options.eid]
    }

    moveCollectionElementDown(url, type, options){
        const element = this.form.children[options.eid]
    }

    render() {
        const method = this.otherProps.template.form.method
        if (method === 'post')
            return (
                <form name={this.otherProps.form.name} method={'post'} onSubmit={this.handleSubmit} encType={this.otherProps.template.form.encType}>
                    <FormRender
                        cancelMessage={this.cancelMessage}
                        elementChange={this.elementChange}
                        getElementData={this.getElementData}
                        messages={this.state.messages}
                        data={this.state.data}
                        {...this.otherProps}
                        {...this.collectionFunctions}
                    />
                </form>
            )
        return (
            <form name={this.otherProps.form.name} method={method} onSubmit={this.handleSubmit}>
                <FormRender
                    cancelMessage={this.cancelMessage}
                    elementChange={this.elementChange}
                    getElementData={this.getElementData}
                    messages={this.state.messages}
                    data={this.state.data}
                    {...this.otherProps}
                    {...this.collectionFunctions}
                />
            </form>
        )
    }
}

FormControl.PropsTypes = {
    form: PropTypes.object.isRequired,
}

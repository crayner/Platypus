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

    setFormElementById(element, form) {
        const name = element.id
        let found = false
        if (form.id === name)
            return child

        form.children.map((child, key) => {
            if (element.id === name) {
                form.children[key] = child
                found = true
            }
        })

        if (found)
            return form

        form.children.map((child, key) => {
            form.children[key] = this.setFormElementById(element,child)
        })

        return form
    }

    getFormElementById(name, form) {

        if (form.id === name)
            return form
        const xxx = form.children.find(child => {
            if (child.id === name)
                return child
        })
        if (typeof xxx !== 'undefined')
            return xxx

        form.children.map(child => {
            return this.getFormElementById(name, child)
        })
        console.error('No form named ' + name + ' was found!')
    }

    setCollectionMemberKey(prototype, key)
    {
        let vars = {...prototype}
        vars.children = prototype.children.map(child => {
            return this.setCollectionMemberKey(child,key)
        })

        vars.full_name = vars.full_name.replace('__name__', key)
        vars.id = vars.id.replace('__name__', key)
        vars.name = vars.name.replace('__name__', key)
        vars.label = vars.label.replace('__name__', key)

        return vars;
    }

    addCollectionData(full_name, key, prototype){
        let data = this.getElementData(full_name);
        let x = full_name.split('[')
        x.shift()
        x.map((name,key) => {
            x[key] = name.replace(']', '')
        })
        data[key] = {}
        this.data = this.setElementData(x, data, this.data)

        console.log(prototype,this.data)
        prototype.children.map(child => {
            let x = child.full_name.split('[')
            x.shift()
            const y = x.map((name,key) => {
                x[key] = name.replace(']', '')
            })
            this.data = this.setElementData(x, child.value, this.data)
        })
        return this.data
    }

    addCollectionElement(button,e){
        const name = button.id.replace('_add','')
        let collection = this.getFormElementById(name, {...this.form})
        const key = collection.children.length
        let prototype = {...collection.prototype}
        prototype = this.setCollectionMemberKey(prototype, key)

        let children = collection.children
        children[key] = prototype
        collection.children = children


        this.form = this.setFormElementById(collection,{...this.form})
        this.data = this.addCollectionData(collection.full_name, key, prototype)

        this.setState({
            form: this.form,
            messages: this.messages,
            data: this.data
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

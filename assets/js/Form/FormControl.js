'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import FormRender from './FormRender'

export default class FormControl extends Component {
    constructor(props) {
        super(props)

        this.form = props.form
        this.otherProps = {...props}

        this.messages = []
        this.state = {
            messages: this.messages,
            form: this.form
        }
        this.elementChange = this.elementChange.bind(this)
        this.deleteCollectionElement = this.deleteCollectionElement.bind(this)
        this.addCollectionElement = this.addCollectionElement.bind(this)
        this.saveForm = this.saveForm.bind(this)
        this.getElementData = this.getElementData.bind(this)


        this.formControl = {
            elementChange: this.elementChange,
            getElementData: this.getElementData,
            deleteCollectionElement: this.deleteCollectionElement,
            addCollectionElement: this.addCollectionElement,
            saveFunction: this.saveForm,
        }
        this.elementList = {}
    }


    elementChange(event, id){
        let element = this.getFormElementById(id)
        element.value = event.target.value
        this.setFormElement(element)
        this.setState({
            messages: this.messages,
            form: this.form
        })
    }

    deleteCollectionElement(button){
        const element = button.row
        const eid = parseInt(element.name)
        const collectionId = element.id.replace('_' + eid, '')
        let collection = this.getFormElementById(collectionId, this.form)
        const prototype = {...collection.prototype}
        let counter = 0
        let children = []
        if (typeof collection.children === 'object'){
            Object.keys(collection.children).map(key => {
                if (parseInt(key) !== parseInt(eid)) {
                    let element = {...collection.children[key]}
                    let was = {}
                    was.id = element.id
                    was.full_name = element.full_name
                    was.name = element.name
                    let now = {}
                    now.id = prototype.id.replace('__name__', counter)
                    now.full_name = prototype.full_name.replace('__name__', counter)
                    element.name = prototype.name.replace('__name__', counter)
                    element.full_name = prototype.full_name.replace('__name__', counter)
                    element.id = prototype.id.replace('__name__', counter)
                    element.name = counter.toString()
                    element.label = prototype.label.replace('__name__', counter)
                    element = this.updateCollectionDetails(element,was,now)
                    children[counter++] = element
                }
            })
        } else {
            collection.children.map((element, key) => {
                if (parseInt(key) !== parseInt(eid)) {
                    let was = {}
                    was.id = element.id
                    was.full_name = element.full_name
                    was.name = element.name
                    let now = {}
                    now.id = prototype.id.replace('__name__', counter)
                    now.full_name = prototype.full_name.replace('__name__', counter)
                    element.name = prototype.name.replace('__name__', counter)
                    element.full_name = prototype.full_name.replace('__name__', counter)
                    element.id = prototype.id.replace('__name__', counter)
                    element.name = counter.toString()
                    element.label = prototype.label.replace('__name__', counter)
                    element = this.updateCollectionDetails(element,was,now)
                    children[counter++] = element
                }
            })
        }

        collection.children = children

        this.setFormElement(collection)
        this.getFormElementById(collection.id,true)

        this.setState({
            form: this.form,
            messages: this.messages,
        })
    }

    updateCollectionDetails(element,was,now)
    {
        element.children.map(child => {
            child.full_name = child.full_name.replace(was.full_name,now.full_name)
            child.id = child.id.replace(was.id,now.id)
            child = this.updateCollectionDetails(child,was,now)
         })
        return element
    }

    addCollectionElement(button){
        const id = button.id.replace('_add','')
        let collection = this.getFormElementById(id)
        const key = collection.children.length
        let prototype = {...collection.prototype}
        prototype = this.setCollectionMemberKey(prototype, key)

        let children = collection.children
        children[key] = prototype
        collection.children = children

        this.setFormElement(collection)

        this.setState({
            form: this.form,
            messages: this.messages,
        })
    }

    getFormElementById(id, refresh = false) {
        if (refresh === true)
            this.elementList = {}
        if (typeof this.elementList[id] === 'undefined')
            this.elementList = this.buildElementList({})
        return this.elementList[id]
    }

    buildElementList(list, form = this.form) {
        list[form.id] = form
        form.children.map(child => {
            this.buildElementList(list,child)
        })
        return list
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

    setFormElement(element, form = this.form) {
        if (element.id === form.id)
        {
            form = {...element}
            return form
        }
        form.children.map(child => {
            this.setFormElement(element, child)
        })
    }

    getElementData(id){
        let element = this.getFormElementById(id)
        if (element.id !== id)
            console.error('The element ' + element.id + ' returned does not match the requested element ' + id)

        if ((element.value === '' || element.value === 'undefined' || element.value === null) && element.value !== element.data)
        {
            element.value = element.data
            this.elementList[element.id] = element
        }
        return (typeof element.value === 'undefined' || element.value === null) ? '' : element.value
    }

    buildFormData(data, form = this.form) {
        if (form.children.length > 0){
            form.children.map(child => {
                data[child.name] = this.buildFormData({}, child)
            })
            return data
        } else
            return form.value
    }

    saveForm(){
        this.data = this.buildFormData({})
        console.log(this.data)
    }

    render() {
        return (
            <FormRender
                {...this.state}
                {...this.formControl}
                {...this.otherProps}
            />
        )
    }
}

FormControl.propTypes = {
    form: PropTypes.object.isRequired,
}

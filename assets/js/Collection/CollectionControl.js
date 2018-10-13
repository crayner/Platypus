'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import CollectionType from './CollectionType'
import {fetchJson} from '../Component/fetchJson'
import Messages from '../Component/Messages/Messages'

export default class CollectionControl extends Component {
    constructor(props) {
        super(props)

        this.translations = props.translations
        this.locale = props.locale
        this.style = props.style
        this.form = {...props.form}
        this.template = props.template
        this.collection_element = props.collection_element
        this.prototype = props.prototype
        this.messages = []

        this.deleteCollectionElement = this.deleteCollectionElement.bind(this)
        this.addCollectionElement = this.addCollectionElement.bind(this)
        this.cancelMessage = this.cancelMessage.bind(this)

        if (typeof(this.translations) !== 'object')
            this.translations = {}

        this.state = {
            messages: this.messages,
            form: this.form,
        }
    }

    getDeleteUrl(data){
        if (this.template.actions.delete.url === 'undefined')
            return '';

        let url = this.template.actions.delete.url
        Object.keys(this.template.actions.delete.url_options).map(key => {
            url = url.replace(key,data)
        })

        return url
    }

    deleteCollectionElement(url, type, options){
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

    removeElement(eid){
        eid = parseInt(eid)
        const children = this.form.children.filter((element, key) => {
            if (key !== eid)
                return element;
        })
        this.form.children = children
        this.setState({
            form: this.form,
            messages: this.messages,
        })
    }

    addCollectionElement(){
        const key = this.state.form.children.length
        let prototype = {...this.prototype}
        prototype = this.setCollectionMemberKey(prototype, key)

        let children = this.form.children
        children[key] = prototype
        this.form.children = children
        this.setState({
            form: this.form,
            messages: this.messages
        })
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

        return vars;
    }

    cancelMessage(id) {
        id = parseInt(id)
        this.setState({
            messages: this.messages.filter((message,key) => {
                if (key !== id)
                    return message
            }),
            form: this.form,
        })
    }

    render() {
        console.log(this.state)
        return (
            <section>
                <Messages messages={this.state.messages} translations={this.translations} cancelMessage={this.cancelMessage}/>
                <CollectionType
                    translations={this.translations}
                    form={this.state.form}
                    template={this.template}
                    style={this.style}
                    deleteCollectionElement={this.deleteCollectionElement}
                    addCollectionElement={this.addCollectionElement}
                />
                <hr/>
            </section>
        )
    }
}

CollectionControl.propTypes = {
    translations: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]).isRequired,
    content: PropTypes.string.isRequired,
    locale: PropTypes.string,
    prototype: PropTypes.object,
    style: PropTypes.string.isRequired,
    collection_element: PropTypes.string.isRequired,
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
}

CollectionControl.defaultProps = {
    prototype: {}
}

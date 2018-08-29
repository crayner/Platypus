'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import Parser from 'html-react-parser';
import AddressCollection from './AddressCollection'
import {fetchJson} from '../Component/fetchJson'

export default class AddressControl extends Component {
    constructor(props) {
        super(props)

        this.locale = props.locale
        this.translations = props.translations
        this.id = props.id
        this.parentClass = props.parentClass

        this.state = {
            attached: [],
            suggestions: [],
            messages: [],
            search: '',
        }

        this.attached = []
        this.suggestions = []
        this.messages = []
        this.search = ''

        this.addressSuggestions = this.addressSuggestions.bind(this)
        this.removeAddress = this.removeAddress.bind(this)
        this.addAddress = this.addAddress.bind(this)
        this.cancelMessage = this.cancelMessage.bind(this)

        this.allAddresses = []

    }

    componentWillMount(){
        this.grabAllAddress()
        this.grabAttachedAddresses()
        this.handleAddress()
    }

    grabAllAddress() {
         fetchJson('/address/grab/list/', [], this.locale)
            .then((data) => {
                this.allAddresses = data.data
                return this.allAddresses
            })

        return this.allAddresses
    }

    grabAttachedAddresses(){
        let path = '/address/attached/{parentEntity}/{id}/'

        path = path.replace('{parentEntity}', this.parentClass)
        path = path.replace('{id}', this.id)

        fetchJson(path, [], this.locale)
            .then((data) => {
                this.attached = data.addresses
                this.messages = data.messages
                this.handleAddress()
            })
    }

    cancelMessage(id) {
        this.messages.splice(id, 1)
        this.handleAddress()
    }

    handleAddress(){
        this.setState({
            attached: this.attached,
            suggestions: this.suggestions,
            messages: this.messages,
            search: this.search,
       })
    }

    addressSuggestions(event) {
        const value = event.target.value.toLowerCase()

        this.search = value

        if (! value || value.length < 2) {
            this.suggestions = []
            this.handleAddress()
            return
        }

        if (typeof(this.allAddresses) === 'object')
            this.allAddresses = Object.keys(this.allAddresses).map((id) => {
                return this.allAddresses[id]
            })
        // filter stuff
        const suggestions = this.allAddresses.filter(address => address.label.toLowerCase().includes(value))

        this.suggestions = suggestions
        this.handleAddress()
    }

    removeAddress(url){
        url = url.replace('{entity_id}', this.id)

        fetchJson(url, [], this.locale)
            .then((data) => {
                this.messages = data.messages
                this.search = ''
                this.suggestions = []
                this.grabAttachedAddresses()
            })
    }

    addAddress(event){
        let value = event.target

        const id = value.getAttribute('value')

        let path = '/address/{id}/attach/{parentEntity}/{entity_id}/'

        path = path.replace('{id}', id)
        path = path.replace('{parentEntity}', this.parentClass)
        path = path.replace('{entity_id}', this.id)

        fetchJson(path, [], this.locale)
            .then((data) => {
                this.messages = data.messages
                this.attached = data.addresses
                this.search = ''
                this.suggestions = []
                this.handleAddress()
            })
    }

    render() {
        if (this.id === 'Add')
            return (
                <div className="card card-warning">
                    <div className="card-header">
                        <h3 className="card-title d-flex mb-12 justify-content-between">{translateMessage(this.translations, 'address.help.header')}</h3>
                        <p>{translateMessage(this.translations, 'address.no.parent')}</p>
                    </div>
                </div>
            )
        return (
            <div className="card card-primary">
                <div className="card-header">
                    <h4 className="card-title d-flex mb-12 justify-content-between">{translateMessage(this.translations, 'address.help.header')}</h4>
                    {Parser(translateMessage(this.translations, 'address.help.content'))}
                </div>
                <div className="card-body">
                    <AddressCollection
                        translations={this.translations}
                        suggestions={this.state.suggestions}
                        addAddress={this.addAddress}
                        addressSuggestions={this.addressSuggestions}
                        messages={this.messages}
                        cancelMessage={this.cancelMessage}
                        attached={this.attached}
                        parentClass={this.parentClass}
                        id={this.id}
                        inputSearch={this.search}
                        removeAddress={this.removeAddress}
                />
                </div>
            </div>
        )
    }

}

AddressControl.propTypes = {
    locale: PropTypes.string,
    translations: PropTypes.object.isRequired,
    id: PropTypes.string.isRequired,
    parentClass: PropTypes.string.isRequired,
}

AddressControl.defaultTypes = {
    locale: 'en',
}


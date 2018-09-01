'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import Parser from 'html-react-parser';
import AddressCollection from './AddressCollection'
import {fetchJson} from '../Component/fetchJson'
import AddressEdit from './AddressEdit'

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
            currentAddress: null,
            currentLocality: null,
        }

        this.attached = []
        this.suggestions = []
        this.messages = []
        this.search = ''

        this.addressSuggestions = this.addressSuggestions.bind(this)
        this.removeAddress = this.removeAddress.bind(this)
        this.addAddress = this.addAddress.bind(this)
        this.cancelMessage = this.cancelMessage.bind(this)
        this.newAddress = this.newAddress.bind(this)
        this.editLocality = this.editLocality.bind(this)
        this.editAddress = this.editAddress.bind(this)
        this.changeCountry = this.changeCountry.bind(this)
        this.changeRegion = this.changeRegion.bind(this)
        this.saveLocality = this.saveLocality.bind(this)
        this.exitLocality = this.exitLocality.bind(this)
        this.exitAddress = this.exitAddress.bind(this)
        this.saveAddress = this.saveAddress.bind(this)

        this.allAddresses = []

        this.buildingTypeList = this.props.buildingTypeList
        this.localityList = this.props.localityList
        this.currentLocality = null
        this.currentAddress = null
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
            currentLocality: this.currentLocality,
            currentAddress: this.currentAddress,
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

    newAddress(){
        this.currentAddress = {
            id: 0,
            streetName: '',
            buildingType: '',
            buildingNumber: '',
            streetNumber: '',
            propertyName: '',
            postCode: '',
            locality: '',
        }
        this.handleAddress()
    }

    addAddress(id){
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

    editLocality(){
        const object = document.getElementById('address_locality')
        const value = object[object.selectedIndex].value;

        if (parseInt(value) == value) {
            let path = '/locality/{id}/grab/'
            path = path.replace('{id}', value)

            fetchJson(path, {}, this.locale)
                .then(data => {
                    this.currentLocality = data.locality
                    this.handleAddress()
                })
        } else {
            this.currentLocality = {
                name: '',
                country: '',
                id: 0,
                territory: '',
                postCode: '',
            }
            this.handleAddress()
        }
    }

    editAddress(val){
        let path = '/address/{id}/grab/'
        path = path.replace('{id}', val)
        fetchJson(path, {}, this.locale)
            .then(data => {
                this.currentAddress = data.address
                this.handleAddress()
            })
    }

    changeCountry(val){
        this.currentLocality.country = val
        this.handleAddress()
    }

    changeRegion(val){
        this.currentLocality.territory = val
        this.handleAddress()
    }

    exitLocality(){
        this.currentLocality = null
        this.handleAddress()
    }

    exitAddress(){
        this.currentAddress = null
        this.handleAddress()
    }

    saveLocality(){
        this.currentLocality.postCode = window.document.getElementById('locality_postCode').value
        this.currentLocality.name = window.document.getElementById('locality_name').value

        const xxx = new Buffer(JSON.stringify(this.currentLocality)).toString('base64')

        let path = '/locality/{locality}/save/'
            .replace('{locality}', xxx)

        fetchJson(path, {}, this.locale )
            .then(data => {
                this.currentLocality = data.locality
                this.messages = data.messages
                if (data.status === 'default') {
                    this.currentLocality = null
                    fetchJson('/locality/grab/list/', {}, this.locale)
                        .then(data => {
                            this.localityList = data.data
                            this.handleAddress()
                        })
                } else
                    this.handleAddress()
            })
    }

    saveAddress(){
        this.currentAddress.postCode = window.document.getElementById('address_postCode').value
        this.currentAddress.streetName = window.document.getElementById('address_streetName').value
        this.currentAddress.streetNumber = window.document.getElementById('address_streetNumber').value
        this.currentAddress.propertyName = window.document.getElementById('address_propertyName').value
        this.currentAddress.buildingNumber = window.document.getElementById('address_buildingNumber').value
        let x = window.document.getElementById('address_buildingType')
        this.currentAddress.buildingType = x.options[x.selectedIndex].value;
        this.currentAddress.id = window.document.getElementById('address_id').value
        let y = window.document.getElementById('address_locality')
        this.currentAddress.locality = y.options[y.selectedIndex].value;

        const xxx = new Buffer(JSON.stringify(this.currentAddress)).toString('base64')

        let path = '/address/{address}/save/'
            .replace('{address}', xxx)

        fetchJson(path, {}, this.locale )
            .then(data => {
                this.currentAddress = data.address
                this.messages = data.messages
                if (data.status === 'default') {
                    this.currentAddress = null
                    this.grabAllAddress()
                    this.grabAttachedAddresses()
                } else
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

        if (this.state.currentAddress !== null){
            return ( <AddressEdit
                translations={this.translations}
                buildingTypeList={this.buildingTypeList}
                localityList={this.localityList}
                editLocality={this.editLocality}
                currentLocality={this.state.currentLocality}
                currentAddress={this.state.currentAddress}
                changeCountry={this.changeCountry}
                changeRegion={this.changeRegion}
                saveLocality={this.saveLocality}
                exitLocality={this.exitLocality}
                exitAddress={this.exitAddress}
                messages={this.state.messages}
                cancelMessage={this.cancelMessage}
                saveAddress={this.saveAddress}
                newAddress={this.newAddress}
        /> )
        }

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
                        newAddress={this.newAddress}
                        editAddress={this.editAddress}
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
    buildingTypeList: PropTypes.array.isRequired,
}

AddressControl.defaultTypes = {
    locale: 'en',
}


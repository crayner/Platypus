'use strict'

import React, { Component } from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import { parseNumber, formatNumber } from 'libphonenumber-js'
import firstBy from 'thenby'
import {fetchJson} from '../Component/fetchJson'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faPlusCircle} from '@fortawesome/free-solid-svg-icons'
import Messages from '../Component/Messages/Messages'
import PhoneAttachedList from './PhoneAttachedList'
import PhoneFormRender from './PhoneFormRender'
import Parser from 'html-react-parser'

export default class PhoneControl extends Component {
    constructor(props) {
        super(props)
        this.translations = props.translations
        this.locale = props.locale
        this.country = props.country
        this.id = props.id
        this.parentClass = props.parentClass
        this.phoneTypeList = props.phoneTypeList

        this.state = {
            phone: '',
            suggestions: [],
            currentPhone: {},
            attachedPhoneList: [],
            messages: {},
            phoneValid: false,
        }

        this.search = ''
        this.xxx = ''
        this.suggestions = []
        this.fullPhoneList = {}
        this.currentPhone = {}
        this.attachedPhoneList = []
        this.messages = {}
        this.phoneValid = false

        this.phoneSuggestions = this.phoneSuggestions.bind(this)
        this.handlePhone = this.handlePhone.bind(this)
        this.newPhone = this.newPhone.bind(this)
        this.grabAttachedPhoneList = this.grabAttachedPhoneList.bind(this)
        this.removePhone = this.removePhone.bind(this)
        this.editPhone = this.editPhone.bind(this)
        this.exitPhone = this.exitPhone.bind(this)
        this.savePhone = this.savePhone.bind(this)
        this.getCurrentPhone = this.getCurrentPhone.bind(this)
        this.isPhoneValid = this.isPhoneValid.bind(this)
    }

    componentWillMount(){
        this.grabFullPhoneList()
        this.grabAttachedPhoneList()
        this.handlePhone()
    }

    handlePhone(){
        this.setState({
            phone: this.search,
            suggestions: this.suggestions,
            currentPhone: this.currentPhone,
            attachedPhoneList: this.attachedPhoneList,
            messages: this.messages,
            phoneValid: this.phoneValid,
        })
    }

    phoneSuggestions(event) {
        this.search = event.target.value

        if (! this.search || this.search.length < 2) {
            this.suggestions = []
            this.handlePhone()
            return
        }

        // filter stuff
        const suggestions = this.fullPhoneList.filter(phone => phone.label.includes(this.search)).sort(firstBy('label'))

        this.suggestions = suggestions
        this.handlePhone()
    }

    grabFullPhoneList() {
        fetchJson('/phone/grab/list/', [], this.locale)
            .then((data) => {
                this.fullPhoneList = Object.keys(data.data).map(id => {
                    const num = data.data[id]
                    const phone = parseNumber(num['phoneNumber'], num['countryCode'].toUpperCase(), {extended: true})
                    return {id: id, label: (formatNumber(phone, 'National') + '|' + formatNumber(phone, 'International')).replace(/ /g, '').replace('(', '').replace(')', '')}
                })
                return this.fullPhoneList
            })

        return this.fullPhoneList
    }

    newPhone(){
        this.currentPhone = {
            id: 'Add',
            country: this.country,
            phone: '',
            type: '',
        }
        this.phoneValid = false
        this.handlePhone()
    }

    isPhoneValid(){
        if (Object.keys(this.currentPhone).length === 0)
        {
            this.phoneValid = false
            this.handlePhone()
        }

        this.currentPhone = this.getCurrentPhone()
        let phone
        if (this.currentPhone.phone[0] === '+')
            phone = parseNumber(this.currentPhone.phone, {extended: true})
        else
            phone = parseNumber(this.currentPhone.phone, this.currentPhone.country.toUpperCase(), {extended: true})

        this.phoneValid = true
        this.currentPhone.country = phone.country
        if (this.currentPhone.type === '' || phone.valid === false)
            this.phoneValid = false

        this.handlePhone()
    }

    addPhone(id){
        let path = '/phone/{id}/attach/{parentEntity}/{entity_id}/'

        path = path.replace('{id}', id)
        path = path.replace('{parentEntity}', this.parentClass)
        path = path.replace('{entity_id}', this.id)

        fetchJson(path, [], this.locale)
            .then((data) => {
                this.messages = data.messages
                this.attachedPhoneList = data.phones
                this.search = ''
                this.suggestions = []
                this.handlePhone()
            })
    }

    grabAttachedPhoneList(){
        let path = '/phone/attached/{parentEntity}/{id}/'

        path = path.replace('{parentEntity}', this.parentClass)
        path = path.replace('{id}', this.id)

        fetchJson(path, [], this.locale)
            .then((data) => {
                this.attachedPhoneList = data.phones
                this.messages = data.messages
                this.handlePhone()
            })
    }

    cancelMessage(id) {
        this.messages.splice(id, 1)
        this.handlePhone()
    }


    removePhone(url){
        url = url.replace('{entity_id}', this.id)

        fetchJson(url, [], this.locale)
            .then((data) => {
                this.messages = data.messages
                this.search = ''
                this.suggestions = []
                this.grabAttachedPhoneList()
                this.handlePhone()
            })
    }

    editPhone(val){
        let path = '/phone/{id}/grab/'
        path = path.replace('{id}', val)
        fetchJson(path, {}, this.locale)
            .then(data => {
                this.currentPhone = data.phone
                this.isPhoneValid()
            })
    }

    exitPhone(){
        this.currentPhone = {}
        this.phoneValid = false
        this.handlePhone()
    }

    getCurrentPhone(){
        if (window.document.getElementById('phone_number') === null)
            return this.currentPhone
        let currentPhone = {}
        currentPhone.phone = window.document.getElementById('phone_number').value
        let x = window.document.getElementById('phone_type')
        currentPhone.type = x.options[x.selectedIndex].value;
        currentPhone.id = window.document.getElementById('phone_id').value
        let y = window.document.getElementById('phone_country')
        currentPhone.country = y.options[y.selectedIndex].value;

        return currentPhone
    }

    savePhone(){
        this.currentPhone = this.getCurrentPhone()

        const phDetails = parseNumber(this.currentPhone.phone, this.currentPhone.country.toUpperCase(), {extended: true})

        this.currentPhone.phone = formatNumber(phDetails, 'National').replace(/ /g, '').replace('(', '').replace(')', '')

        const xxx = new Buffer(JSON.stringify(this.currentPhone)).toString('base64')

        let path = '/phone/{phone}/save/'
            .replace('{phone}', xxx)

        fetchJson(path, {}, this.locale )
            .then(data => {
                this.currentPhone = data.phone
                this.messages = data.messages
                if (data.status === 'default') {
                    this.currentPhone = {}
                    this.grabFullPhoneList()
                    this.grabAttachedPhoneList()
                    this.handlePhone()
                } else
                    this.handlePhone()
            })
    }

    render() {
        if (Object.keys(this.state.currentPhone).length > 0) {
            return (
                <div>
                    <PhoneFormRender
                        translations={this.translations}
                        phone={this.state.currentPhone}
                        phoneTypeList={this.phoneTypeList}
                        exitPhone={this.exitPhone}
                        changeCountry={this.changeCountry}
                        savePhone={this.savePhone}
                        phoneValid={this.state.phoneValid}
                        isPhoneValid={this.isPhoneValid}
                        messages={this.messages}
                        cancelMessage={this.cancelMessage}
                    />
                </div>
            )
        }

        const options = this.state.suggestions.map(phone =>
            <div className='row row-striped' key={phone.id} onClick={() => this.addPhone(phone.id)}>
                <div className='col-10 offset-1 possiblePhone'>
                    {phone.label}
                </div>
            </div>)

        if (this.id === 'Add')
            return (
                <div className="card card-warning">
                    <div className="card-header">
                        <h3 className="card-title d-flex mb-12 justify-content-between">{translateMessage(this.translations, 'phone.help.header')}</h3>
                        <p>{translateMessage(this.translations, 'phone.no.family')}</p>
                    </div>
                </div>
            )


        return (
            <div className="card card-primary">
                <div className="card-header">
                    <h4 className="card-title d-flex mb-12 justify-content-between">{translateMessage(this.translations, 'phone.help.header')}</h4>
                    {Parser(translateMessage(this.translations, 'phone.help.content'))}
                </div>
                <div className="card-body">
                    {this.state.messages.length > 0 ?
                    <div className='row'>
                        <div className='col-12'>
                            <Messages
                                messages={this.state.messages}
                                translations={this.translations}
                                cancelMessage={this.cancelMessage}
                            />
                        </div>
                    </div> : ''}
                    <div className='row'>
                        <div className='col-12'>
                            <div className="input-group input-group-sm">
                                <input
                                    placeholder={translateMessage(this.translations, 'phone.search.placeholder')}
                                    onChange={this.phoneSuggestions}
                                    type={'text'}
                                    value={this.state.phone}
                                    className='form-control'
                                    autoComplete={'off-tel'}
                                />
                                <span className='input-group-append'>
                                <button className='btn btn-success' type={'button'} onClick={() => this.newPhone()} title={translateMessage(this.translations, 'phone.button.add')}>
                                    <FontAwesomeIcon icon={faPlusCircle} fixedWidth={true} />
                                </button>
                            </span>
                            </div>
                        </div>
                    </div>
                    {this.state.suggestions.length > 0 ?
                    <div className='row'>
                        <div className='col-12'>
                            <div className='small' style={{maxHeight: '200px', overflowY: 'scroll', overflowX: 'hidden', cursor: 'pointer'}}>
                                {options}
                            </div>
                        </div>
                    </div> : '' }
                    {this.state.attachedPhoneList.length > 0 ?
                    <div className='row'>
                        <div className='col-12'>
                            <PhoneAttachedList
                                translations={this.translations}
                                list={this.state.attachedPhoneList}
                                parentClass={this.parentClass}
                                removePhone={this.removePhone}
                                editPhone={this.editPhone}
                            />
                        </div>
                    </div> : '' }

                </div>
            </div>
        )
    }
}

PhoneControl.propTypes = {
    translations: PropTypes.object.isRequired,
    locale: PropTypes.string.isRequired,
    phoneTypeList: PropTypes.array.isRequired,
    country: PropTypes.string.isRequired,
    id: PropTypes.string.isRequired,
    parentClass: PropTypes.string.isRequired,
}


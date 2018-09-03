'use strict';

import React from "react"
import PropTypes from 'prop-types'
import BootstrapInput from '../Form/BootstrapInput'
import BootstrapSelect from '../Form/BootstrapSelect'
import {translateMessage} from '../Component/MessageTranslator'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faSave, faWindowClose} from '@fortawesome/free-regular-svg-icons'
import {faPlusCircle} from '@fortawesome/free-solid-svg-icons'
import Parser from 'html-react-parser';
import {CountryDropdown} from 'react-country-region-selector'
import Messages from '../Component/Messages/Messages'

export default function PhoneFormRender(props) {
    const {
        translations,
        phone,
        phoneTypeList,
        exitPhone,
        savePhone,
        phoneValid,
        isPhoneValid,
        messages,
        cancelMessage,
    } = props

    const phoneTypes = phoneTypeList.map((type) => {
        return {type: type, label: 'phone.type.' + type}
    })

    return (
        <div className="card card-success" key={phone.id}>
            <div className="card-header">
                <h4 className="card-title d-flex mb-12 justify-content-between">{translateMessage(translations, 'phone.edit.header')}</h4>
                {Parser(translateMessage(translations, 'phone.edit.help'))}
                <span><button type={'button'} className={'btn btn-sm btn-warning'} style={{float: 'right'}} onClick={() => exitPhone()} title={translateMessage(translations, 'phone.button.exit')}><FontAwesomeIcon icon={faWindowClose}/></button>
                    {parseInt(phone.id) > 0 ?
                        <button type={'button'} className={'btn btn-sm btn-primary'} style={{float: 'right'}} title={translateMessage(translations, 'phone.button.add')} onClick={() => newAddress()}><FontAwesomeIcon icon={faPlusCircle}/></button> : '' }
                    <button disabled={phoneValid ? false : true } type={'button'} className={'btn btn-sm btn-success'} style={{float: 'right'}} title={translateMessage(translations, 'phone.button.save')} onClick={() => savePhone()}><FontAwesomeIcon icon={faSave}/></button>
                    </span>
            </div>
            <div className="card-body">
                <div className="phoneNumber">
                    {messages.length > 0 ?
                        <div className='row'>
                            <div className='col-12'>
                                <Messages
                                    messages={messages}
                                    translations={translations}
                                    cancelMessage={cancelMessage}
                                />
                            </div>
                        </div> : ''}
                    <div className="row">
                        <div className="col-12">
                            <BootstrapSelect
                                translations={translations}
                                name={'phone[type]'}
                                value={phone.type}
                                optionList={phoneTypes}
                                label={'phone.type.label'}
                                required={true}
                                onChange={isPhoneValid}
                            />
                        </div>
                    </div>
                    <div className={'row'}>
                        <div className="col-12">
                            <CountryDropdown
                                value={phone.country}
                                classes={'form-control'}
                                id={'phone_country'}
                                onChange={isPhoneValid}
                                autocomplete={'country'}
                                required={true}
                                name={'phone[country]'}
                                valueType={'short'}
                                countryValueType={'short'}
                                defaultOptionLabel={translateMessage(translations, 'locality.country.placeholder')}
                            />
                            <label className={"form-control-label"} htmlFor={'phone_country'}>{translateMessage(translations, 'phone.country.label')}</label>
                            <span className="field-required"> {translateMessage(translations, 'form.required')} </span>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12">
                            <BootstrapInput
                                translations={translations}
                                name={'phone[number]'}
                                value={phone.phone}
                                label={'phone.number.label'}
                                onChange={isPhoneValid}
                                required={true}
                            />
                        </div>
                    </div>
                    <BootstrapInput
                        translations={translations}
                        name={'phone[id]'}
                        value={phone.id}
                        type={'hidden'}
                    />
                </div>
            </div>
        </div>
    )
}

PhoneFormRender.propTypes = {
    translations: PropTypes.object.isRequired,
    phone: PropTypes.object.isRequired,
    phoneTypeList: PropTypes.array.isRequired,
    exitPhone: PropTypes.func.isRequired,
    savePhone: PropTypes.func.isRequired,
    phoneValid: PropTypes.bool.isRequired,
    isPhoneValid: PropTypes.func.isRequired,
    messages: PropTypes.array.isRequired,
    cancelMessage: PropTypes.func.isRequired,
}

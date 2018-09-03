'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import Messages from '../Component/Messages/Messages'
import ButtonDelete from '../Component/Button/ButtonDelete'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faLink, faPlusCircle, faEdit } from '@fortawesome/free-solid-svg-icons'

export default function AddressCollection(props) {
    const {
        translations,
        suggestions,
        addAddress,
        addressSuggestions,
        messages,
        cancelMessage,
        attached,
        parentClass,
        removeAddress,
        inputSearch,
        newAddress,
        editAddress,
    } = props

    let search = ''
    
    const options = suggestions.map(address =>
        <div className='row row-striped' key={address.id} onClick={() => addAddress(address.id)}>
            <div className='col-10 offset-1 possibleAddress'>
                {address.label}
            </div>
        </div>)

    const attachedAddresses = attached.map(address => {
        let button = {
            label: 'address.remove',
            style: {float: 'right'},
            classMerge: 'btn-sm',
            response_type: 'json',
        }
        let url = '/address/{id}/remove/{parentClass}/{entity_id}/'
        url = url.replace('{id}', address.id)
        url = url.replace('{parentClass}', parentClass)

        return (
            <div className='row row-striped small' key={address.id}>
                <div className='col-9'>{address.label}</div>
                <div className='col-3'>
                    <span>{parentClass === address.parent ? (
                        <ButtonDelete
                            button={button}
                            url={url}
                            buttonClickAction={removeAddress}
                            translations={translations}
                            style={{float: 'right'}}
                        />) : <div className='btn btn-light btn-sm' style={{float: 'right'}}><FontAwesomeIcon icon={faLink} fixedWidth={true} /></div> }
                        <button type={'button'} title={translateMessage(translations, 'address.button.edit')} className={'btn btn-primary btn-sm'} style={{float: 'right'}} onClick={() => editAddress(address.id)}><FontAwesomeIcon icon={faEdit} fixedWidth={true} /></button>
                    </span>
                </div>
            </div>
        )
    })

    return (
        <div>
            <div className='row'>
                <div className='col-12'>
                    <Messages
                        messages={messages}
                        translations={translations}
                        cancelMessage={cancelMessage}
                    />
                </div>
            </div>
            <div className='row'>
                <div className='col-12 text-justify'>
                    <div className="input-group input-group-sm">
                        <input
                            placeholder={translateMessage(translations, 'address.search.placeholder')}
                            ref={input => search = input}
                            onChange={addressSuggestions}
                            value={inputSearch}
                            className='form-control'
                        />
                        <span className='input-group-append'>
                            <button className='btn btn-success' type={'button'} onClick={() => newAddress()} title={translateMessage(translations, 'address.button.add')}><FontAwesomeIcon icon={faPlusCircle} fixedWidth={true} /></button>
                        </span>
                    </div>
                </div>
            </div>
            <div className='row'>
                <div className='col-12'>
                    <div className='small' style={{maxHeight: '200px', overflowY: 'scroll', cursor: 'pointer'}}>
                        {options}
                    </div>
                </div>
            </div>
            <div className='row'>
                <div className='col-12'>
                    {attachedAddresses}
                </div>
            </div>
            <div className='row'>
                <div className='col-12 text-justify'>
                    <p className={'alert alert-info'}>{translateMessage(translations, 'address.edit.content')}</p>
                </div>
            </div>
        </div>
    )
}

AddressCollection.propTypes = {
    translations: PropTypes.object.isRequired,
    suggestions: PropTypes.array.isRequired,
    addressSuggestions: PropTypes.func.isRequired,
    addAddress: PropTypes.func.isRequired,
    removeAddress: PropTypes.func.isRequired,
    newAddress: PropTypes.func.isRequired,
    cancelMessage: PropTypes.func.isRequired,
    parentClass: PropTypes.string.isRequired,
    inputSearch: PropTypes.string.isRequired,
    editAddress: PropTypes.func.isRequired,
}

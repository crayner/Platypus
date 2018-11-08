'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import BootstrapInput from '../Form/BootstrapInput'
import BootstrapSelect from '../Form/BootstrapSelect'
import firstBy from 'thenby'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import {faEdit, faSave, faWindowClose} from '@fortawesome/free-regular-svg-icons'
import {faPlusCircle} from '@fortawesome/free-solid-svg-icons'
import LocalityEdit from './LocalityEdit'
import Messages from '../Component/Messages/Messages'

export default function AddressEdit(props) {
    const {
        translations,
        buildingTypeList,
        localityList,
        editLocality,
        currentLocality,
        changeCountry,
        changeRegion,
        saveLocality,
        exitLocality,
        messages,
        cancelMessage,
        exitAddress,
        currentAddress,
        saveAddress,
        newAddress
    } = props


    const btList = buildingTypeList.map((type) => {
        return {type: type, label: 'address.building_type.' + type}
    })

    const localities = Object.keys(localityList).map(key => {
        return {type: localityList[key].id, label: localityList[key].label}
    }).sort(firstBy('label'))

    if (currentLocality !== null)
        return <LocalityEdit
            translations={translations}
            currentLocality={currentLocality}
            changeCountry={changeCountry}
            changeRegion={changeRegion}
            saveLocality={saveLocality}
            exitLocality={exitLocality}
            messages={messages}
            cancelMessage={cancelMessage}
        />

    return (
        <div className="card card-success" key={currentAddress.id}>
            <div className="card-header">
                <h4 className="card-title d-flex mb-12 justify-content-between">{translateMessage(translations, 'address.edit.header')}
                    <span><button type={'button'} className={'btn btn-warning'} style={{float: 'right'}} onClick={() => exitAddress()} title={translateMessage(translations, 'address.button.exit')}><FontAwesomeIcon icon={faWindowClose}/></button>
                        {parseInt(currentAddress.id) > 0 ?
                        <button type={'button'} className={'btn btn-primary'} style={{float: 'right'}} title={translateMessage(translations, 'address.button.add')} onClick={() => newAddress()}><FontAwesomeIcon icon={faPlusCircle}/></button> : '' }
                        <button type={'button'} className={'btn btn-success'} style={{float: 'right'}} title={translateMessage(translations, 'address.button.save')} onClick={() => saveAddress()}><FontAwesomeIcon icon={faSave}/></button>
                    </span>
                </h4>
                <p>{translateMessage(translations, 'address.edit.help')}</p>
            </div>
            <div className="card-body">
                <Messages
                    messages={messages}
                    translations={translations}
                    cancelMessage={cancelMessage}
                />
                <div className='row'>
                    <div className='col-12 card'>
                        <input type={'hidden'} defaultValue={currentAddress.id} id={'address_id'} name={'address[id]'} />
                        <BootstrapInput
                            translations={translations}
                            name={"address[propertyName]"}
                            autoComplete={"property_name"}
                            label={'address.property_name.label'}
                            value={currentAddress.propertyName}
                        />
                    </div>
                </div>
                <div className='row'>
                    <div className='col-4 card'>
                        <BootstrapSelect
                            translations={translations}
                            optionList={btList}
                            name={'address[buildingType]'}
                            autoComplete={"building_type"}
                            label={'address.building_type.label'}
                            value={currentAddress.buildingType}
                        />
                    </div>
                    <div className='col-4 card'>
                        <BootstrapInput
                            translations={translations}
                            name={'address[buildingNumber]'}
                            autoComplete={"building_number"}
                            label={'address.building_number.label'}
                            value={currentAddress.buildingNumber}

                        />
                    </div>
                    <div className='col-4 card'>
                        <BootstrapInput
                            translations={translations}
                            name={'address[streetNumber]'}
                            label={'address.street_number.label'}
                            autoComplete={"street_number"}
                            value={currentAddress.streetNumber}
                        />
                    </div>
                </div>
                <div className='row'>
                    <div className='col-8 card'>
                        <BootstrapInput
                            translations={translations}
                            name={'address[streetName]'}
                            label={'address.street_name.label'}
                            autoComplete={"street_name"}
                            required={true}
                            value={currentAddress.streetName}
                        />
                    </div>
                    <div className='col-4 card'>
                        <BootstrapInput
                            translations={translations}
                            name={'address[postCode]'}
                            label={'address.post_code.label'}
                            required={true}
                            value={currentAddress.postCode}
                            help={'address.post_code.help'}
                        />
                    </div>
                </div>
                <div className='row'>
                    <div className='col-10 card'>
                        <BootstrapSelect
                            translations={translations}
                            optionList={localities}
                            name={'address[locality]'}
                            label={'address.locality.label'}
                            translate={false}
                            required={true}
                            help={'address.locality.help'}
                            placeholder={'address.locality.placeholder'}
                            value={currentAddress.locality}
                        />
                    </div>
                    <div className='col-2 card'>
                        <button type={'button'} className={'btn btn-primary'} title={translateMessage(translations, 'locality.button.edit')} onClick={() => editLocality()}><FontAwesomeIcon icon={faEdit} fixedWidth={true} /></button>
                    </div>
                </div>
            </div>
        </div>
    )
}

AddressEdit.propTypes = {
    translations: PropTypes.object.isRequired,
    currentAddress: PropTypes.object.isRequired,
    buildingTypeList: PropTypes.array.isRequired,
    messages: PropTypes.oneOfType([
        PropTypes.array,
        PropTypes.object,
    ]).isRequired,
    localityList: PropTypes.object.isRequired,
    editLocality: PropTypes.func.isRequired,
    currentLocality: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.object,
    ]),
    changeCountry: PropTypes.func.isRequired,
    changeRegion: PropTypes.func.isRequired,
    saveLocality: PropTypes.func.isRequired,
    exitLocality: PropTypes.func.isRequired,
    exitAddress: PropTypes.func.isRequired,
    cancelMessage: PropTypes.func.isRequired,
    saveAddress: PropTypes.func.isRequired,
    newAddress: PropTypes.func.isRequired,
}

AddressEdit.defaultTypes = {
    currentLocality: null,
}
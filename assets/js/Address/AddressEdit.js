'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import BootstrapInput from '../Form/BootstrapInput'
import BootstrapSelect from '../Form/BootstrapSelect'
import firstBy from 'thenby'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faEdit } from '@fortawesome/free-regular-svg-icons'
import LocalityEdit from './LocalityEdit'
import Messages from '../Component/Messages/Messages'


export default function AddressEdit(props) {
    const {
        translations,
        addressData,
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
        <div className="card card-success">
            <div className="card-header">
                <h4 className="card-title d-flex mb-12 justify-content-between">{translateMessage(translations, 'address.edit.header')}</h4>
            </div>
            <div className="card-body">
                <Messages
                    messages={messages}
                    translations={translations}
                    cancelMessage={cancelMessage}
                />
                <div className='row'>
                    <div className='col-12 card'>
                        <BootstrapInput
                            translations={translations}
                            name={"address[propertyName]"}
                            autoComplete={"property_name"}
                            label={'address.property_name.label'}
                        />
                    </div>
                </div>
                <div className='row'>
                    <div className='col-4 card'>
                        <BootstrapSelect
                            translations={translations}
                            optionList={btList}
                            name={'address[buildingType]'}
                            label={'address.building_type.label'}
                        />
                    </div>
                    <div className='col-4 card'>
                        <BootstrapInput
                            translations={translations}
                            name={'address[buildingNumber]'}
                            label={'address.building_number.label'}
                        />
                    </div>
                    <div className='col-4 card'>
                        <BootstrapInput
                            translations={translations}
                            name={'address[streetNumber]'}
                            label={'address.street_number.label'}
                        />
                    </div>
                </div>
                <div className='row'>
                    <div className='col-12 card'>
                        <BootstrapInput
                            translations={translations}
                            name={'address[streetName]'}
                            label={'address.street_name.label'}
                            required={true}
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
                        />
                    </div>
                    <div className='col-2 card'>
                        <button type={'button'} className={'btn btn-primary'} title={translateMessage(translations, 'locality.button.edit')} onClick={() => editLocality()}><FontAwesomeIcon icon={faEdit}/></button>
                    </div>
                </div>
                <div className='row'>
                    <div className='col-12 text-justify'>
                        <p className={'alert alert-success'}>{translateMessage(translations, 'address.edit.content')}</p>
                    </div>
                </div>
            </div>
        </div>
    )
}

AddressEdit.propTypes = {
    translations: PropTypes.object.isRequired,
    addressData: PropTypes.object.isRequired,
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
    cancelMessage: PropTypes.func.isRequired,
}

AddressEdit.defaultTypes = {
    currentLocality: null,
}
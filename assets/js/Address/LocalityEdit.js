'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import BootstrapInput from '../Form/BootstrapInput'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faSave, faWindowClose } from '@fortawesome/free-regular-svg-icons'
import { CountryDropdown, RegionDropdown } from 'react-country-region-selector';
import Messages from '../Component/Messages/Messages'

export default function LocalityEdit(props) {
    const {
        translations,
        currentLocality,
        changeCountry,
        changeRegion,
        saveLocality,
        exitLocality,
        messages,
        cancelMessage
    } = props

    return (
        <div className="card card-warning">
            <div className="card-header">
                <h4 className="card-title d-flex mb-12 justify-content-between">{translateMessage(translations, 'locality.edit.header')}
                    <span><button type={'button'} className={'btn btn-warning'} style={{float: 'right'}} onClick={() => exitLocality()} title={translateMessage(translations, 'locality.button.exit')}><FontAwesomeIcon icon={faWindowClose}/></button>
                    <button type={'button'} className={'btn btn-success'} style={{float: 'right'}} onClick={() => saveLocality()} title={translateMessage(translations, 'locality.button.save')}><FontAwesomeIcon icon={faSave}/></button></span>
                </h4>
            </div>
            <div className="card-body">
                <Messages
                    messages={messages}
                    translations={translations}
                    cancelMessage={cancelMessage}
                />
                <div className={'row'}>
                    <div className={'col-8 card'}>
                        <input type={'hidden'} id={'locality_id'} name={'locality[id'} className={'locality'} />
                        <BootstrapInput
                            translations={translations}
                            name={'locality[name]'}
                            input_class={'locality'}
                            value={currentLocality.name}
                            label={'locality.name.label'}
                            required={true}
                        />
                    </div>
                    <div className={'col-4 card'}>
                        <BootstrapInput
                            translations={translations}
                            name={'locality[postCode]'}
                            input_class={'locality'}
                            value={currentLocality.postCode}
                            label={'locality.post_code.label'}
                        />
                    </div>
                </div>
                <div className={'row'}>
                    <div className={'col-6 card'}>
                        <CountryDropdown
                            value={currentLocality.country}
                            classes={'form-control locality'}
                            id={'locality_country'}
                            onChange={(val) => changeCountry(val)}
                            autocomplete={'country'}
                            name={'locality[country]'}
                            valueType={'short'}
                            countryValueType={'short'}
                            defaultOptionLabel={translateMessage(translations, 'locality.country.placeholder')}
                        />
                        <label className={"form-control-label"} htmlFor={'locality_country'}>{translateMessage(translations, 'locality.country.label')}</label>
                        <span className="field-required"> {translateMessage(translations, 'form.required')} </span>
                    </div>
                    <div className={'col-6 card'}>
                        <RegionDropdown
                            country={currentLocality.country}
                            value={currentLocality.territory}
                            classes={'form-control locality'}
                            disableWhenEmpty={true}
                            name={'locality[territory]'}
                            id={'locality_territory'}
                            countryValueType={'short'}
                            valueType={'short'}
                            onChange={(val) => changeRegion(val)}
                            defaultOptionLabel={translateMessage(translations, 'locality.territory.placeholder')}
                        />
                        <label className={"form-control-label"} htmlFor={'locality_territory'}>{translateMessage(translations, 'locality.territory.label')}</label>
                    </div>
                </div>
            </div>
        </div>
    )
}

LocalityEdit.propTypes = {
    translations: PropTypes.object.isRequired,
    currentLocality: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.object,
    ]).isRequired,
    changeCountry: PropTypes.func.isRequired,
    changeRegion: PropTypes.func.isRequired,
    saveLocality: PropTypes.func.isRequired,
    exitLocality: PropTypes.func.isRequired,
    cancelMessage: PropTypes.func.isRequired,
    messages: PropTypes.oneOfType([
        PropTypes.array,
        PropTypes.object,
    ]).isRequired,
}
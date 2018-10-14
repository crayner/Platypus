'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonDelete from '../Component/Button/ButtonDelete'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faEdit, faLink} from '@fortawesome/free-solid-svg-icons'
import {translateMessage} from '../Component/MessageTranslator'
import {formatNumber, parseNumber} from 'libphonenumber-js'

export default function PhoneAttachedList(props) {
    const {
        translations,
        list,
        parentClass,
        removePhone,
        editPhone,
    } = props


    const attachedPhones = list.map(phone => {
        let button = {
            label: 'phone.remove',
            style: {float: 'right'},
            mergeClass: 'btn-sm',
            url_type: 'json',
        }
        const url = '/phone/{id}/remove/{parentClass}/{entity_id}/'.replace('{id}', phone.id).replace('{parentClass}', parentClass)

        const phDetails = parseNumber(phone['phone'], phone['country'].toUpperCase(), {extended: true})

        return (
            <div className='row row-striped small' key={phone.id}>
                <div className='col-6'>{translateMessage(translations, 'phone.type.' + phone.type)}<br />{formatNumber(phDetails, 'International')}</div>
                <div className='col-6'>
                    <span>{parentClass === phone.parent ? (
                        <ButtonDelete
                            button={button}
                            url={url}
                            buttonClickAction={() => removePhone(url)}
                            translations={translations}
                            style={{float: 'right'}}
                        />) : <div className='btn btn-light btn-sm' style={{float: 'right'}} title={translateMessage(translations, 'family.button.link')}><FontAwesomeIcon icon={faLink} fixedWidth={true} /></div> }
                        <button type={'button'} title={translateMessage(translations, 'phone.button.edit')} className={'btn btn-primary btn-sm'} style={{float: 'right'}} onClick={() => editPhone(phone.id)}><FontAwesomeIcon icon={faEdit} fixedWidth={true} /></button>
                    </span>
                </div>
            </div>
        )
    })

    return (
        <div>{attachedPhones}</div>
    )
}

PhoneAttachedList.propTypes = {
    translations: PropTypes.object.isRequired,
    list: PropTypes.array.isRequired,
    parentClass: PropTypes.string.isRequired,
    removePhone: PropTypes.func.isRequired,
    editPhone: PropTypes.func.isRequired,
}

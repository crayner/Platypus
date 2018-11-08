'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faEraser} from '@fortawesome/free-solid-svg-icons'

export default function AddressRow(props) {
    const {
        translations,
        removeAddress,
        address
    } = props
    return (
        <div className={'row'}>
            <div className={'col-12 card text-right'}>
                <div><button className='btn btn-sm btn-warning' type='button' onClick={() => removeAddress(address.id)} style={{float: 'right'}} title={translateMessage(translations, 'address.remove')}><FontAwesomeIcon icon={faEraser} />
                </button></div>
            </div>
        </div>
    )
}
AddressRow.propTypes = {
    translations: PropTypes.object.isRequired,
    address: PropTypes.object.isRequired,
    removeAddress: PropTypes.func.isRequired,
}

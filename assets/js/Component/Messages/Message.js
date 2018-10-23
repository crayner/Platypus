'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../MessageTranslator'
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faTimesCircle} from '@fortawesome/free-regular-svg-icons'
import Parser from 'html-react-parser';

export default function Message(props) {
    const {
        message,
        translations,
        cancelMessage,
    } = props

    return (
        <div className={'row'}>
            <div className={"col-12 alert alert-" + message.level} role="alert">
                {Parser(message.message)}
                <button className='btn close' onClick={() => {cancelMessage(message.id)}} title={translateMessage(translations, 'message.close')} type='button'>
                    <FontAwesomeIcon icon={faTimesCircle} />
                </button>
            </div>
        </div>
    )
}

Message.propTypes = {
    message: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
    cancelMessage: PropTypes.func.isRequired,
}

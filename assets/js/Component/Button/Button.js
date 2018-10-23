'use strict';

import React from 'react';
import PropTypes from 'prop-types';
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons'
import {translateMessage} from '../MessageTranslator'

library.add(fas, far, fab)

export default function Button(props) {
    const {
        button,
        buttonHandler,
        translations,
    } = props;

    let className = 'btn btn-' + button.colour

    const buttonTypes = ['button','submit']

    if (button.mergeClass !== '')
        className = className + ' ' + button.mergeClass

    if (typeof(button.style) !== 'object')
        button.style = {}

    if (button.type === '' || typeof(button.type) === 'undefined' || ! buttonTypes.includes(button.type))
        button.type = 'button'

    if (typeof(button.options) !== 'object')
        button.options = {}

    if (typeof buttonHandler !== 'undefined')
        return (
            <button
                type={button.type}
                className={className}
                style={button.style}
                onClick={(e) => buttonHandler(button, e)}
                title={translateMessage(translations, button.label)}>
                {button.prompt ? translateMessage(translations, button.prompt) : null}{button.icon ?
                <FontAwesomeIcon icon={button.icon} fixedWidth={true}/> : null}
            </button>
        )

    return (
        <button
            type={button.type}
            className={className}
            style={button.style}
            title={translateMessage(translations, button.label)}>
                {button.prompt ? translateMessage(translations, button.prompt) : null}{button.icon ?
                <FontAwesomeIcon icon={button.icon} fixedWidth={true}/> : null}
        </button>
    )
}

Button.propTypes = {
    button: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
    buttonHandler: PropTypes.func,
};

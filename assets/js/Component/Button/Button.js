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
        url,
        buttonClickAction,
        translations,
    } = props;

    let className = 'btn btn-' + button.colour

    if (button.classMerge !== '')
        className = className + ' ' + button.classMerge

    if (typeof(button.style) !== 'object')
        button.style = new Object()

    return (
        <button className={className} onClick={() => { buttonClickAction(url, button.response_type)}} style={button.style}>
            {button.prompt ? translateMessage(translations, button.prompt) : null}{button.icon ? <FontAwesomeIcon icon={button.icon} /> : null}
        </button>
    )
}

Button.propTypes = {
    url: PropTypes.string.isRequired,
    button: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
    buttonClickAction: PropTypes.func.isRequired,
};

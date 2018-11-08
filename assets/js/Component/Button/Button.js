'use strict';

import React from 'react';
import PropTypes from 'prop-types';
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons'

library.add(fas, far, fab)

export default function Button(props) {
    const {
        button,
        buttonHandler
    } = props;

    let attr = {}

    if (button.class === false || button.class === undefined)
        attr.className = 'btn btn-' + button.colour
    else
        attr.className = button.class

    const buttonTypes = ['button','submit']

    if (button.mergeClass !== '' && (button.class === false || button.class === undefined))
        attr.className = attr.className + ' ' + button.mergeClass

    if (typeof(button.style) === 'object')
        attr.style = button.style

    if (button.type === '' || typeof button.type === 'undefined' || ! buttonTypes.includes(button.type))
        attr.type = 'button'
    else
        attr.type = button.type

    if (typeof button.options === 'object')
        attr.options = button.options

    if (typeof button.title === 'string')
        attr.title = button.title

    if (typeof buttonHandler === 'function')
        attr.onClick = (e) => buttonHandler(button,e)

    if (typeof button.attr === 'object')
        attr = {...attr, ...button.attr}

    if (button.disabled !== undefined && button.disabled === true)
        attr.disabled = true

    if (button.colour === 'transparent') {
        delete attr.className
        delete attr.type
        return (
            <i {...attr}>
                {button.prompt ? button.prompt : null}
                {button.icon ? <FontAwesomeIcon icon={button.icon} fixedWidth={true}/> : null}
            </i>
        )
    }

    return (
        <button {...attr}>
            {button.prompt ? button.prompt : null}
            {button.icon ? <FontAwesomeIcon icon={button.icon} fixedWidth={true}/> : null}
        </button>
    )
}

Button.propTypes = {
    button: PropTypes.object.isRequired,
    buttonHandler: PropTypes.func,
};

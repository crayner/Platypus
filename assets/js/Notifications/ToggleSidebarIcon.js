'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import { translateMessage } from "../Component/MessageTranslator";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faBars } from '@fortawesome/free-solid-svg-icons'
import { faTimesCircle } from '@fortawesome/free-regular-svg-icons';

export default function ToggleSidebarIcon(props) {
    const {
        alwaysFullPage,
        fullPage,
        manageSidebarClick,
        translations,
    } = props;

    return (
        <span onClick={manageSidebarClick} style={{float: 'right'}} className={alwaysFullPage ? 'text-muted' : 'text-normal'} title={translateMessage(translations, 'toggle.sidebar', [])}>
            <FontAwesomeIcon icon={fullPage ? faBars : faTimesCircle}/>
        </span>
    )
}

ToggleSidebarIcon.propTypes = {
    alwaysFullPage: PropTypes.bool.isRequired,
    fullPage: PropTypes.bool.isRequired,
    manageSidebarClick: PropTypes.func.isRequired,
    translations: PropTypes.object.isRequired,
}

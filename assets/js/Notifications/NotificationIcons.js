'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import NoMessage from './NoMessage'
import MessageIcon from './MessageIcon'
import { translateMessage } from "../Component/MessageTranslator";

export default function NotificationIcons(props) {
    const {
        alwaysFullPage,
        fullPage,
        manageSidebarClick,
        messageCount,
        translations,
    } = props;

    function notificationIcon()
    {
        if (messageCount > 0) {
            return ( <MessageIcon
                messageCount={messageCount}
                translations={translations}
            /> )
        } else {
            return ( <NoMessage/> )
        }
    }

    return (
        <div className="col-4 fa-2x" onClick={manageSidebarClick}>
            <span style={{float: 'right'}} className={alwaysFullPage ? 'text-muted' : 'text-normal'} title={translateMessage(translations, 'toggle.sidebar', [])}>
                <span className={fullPage ? "fas fa-bars" : "far fa-times-circle"}></span>
            </span>
            <span style={{float: 'right'}}>&nbsp;&nbsp;</span>
            { notificationIcon() }
        </div>
    )
}

NotificationIcons.propTypes = {
    alwaysFullPage: PropTypes.bool.isRequired,
    fullPage: PropTypes.bool.isRequired,
    manageSidebarClick: PropTypes.func.isRequired,
    messageCount: PropTypes.number.isRequired,
    translations: PropTypes.object.isRequired,
}

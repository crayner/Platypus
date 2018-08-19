'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import NoMessage from './NoMessage'
import MessageIcon from './MessageIcon'
import ToggleSidebarIcon from './ToggleSidebarIcon'

export default function NotificationIcons(props) {
    const {
        alwaysFullPage,
        fullPage,
        manageSidebarClick,
        messageCount,
        translations,
    } = props

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
        <div className="col-4 fa-2x">
            <ToggleSidebarIcon
                alwaysFullPage={alwaysFullPage}
                fullPage={fullPage}
                manageSidebarClick={manageSidebarClick}
                translations={translations}
            />
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

'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import NotificationIcons from "./NotificationIcons";

export default function Notifications(props) {
    const {
        message,
        alwaysFullPage,
        fullPage,
        manageSidebarClick,
        messageCount,
        translations,
    } = props;

    return (
        <div className="row" style={{marginTop: '-32px', maxHeight: '50px', minHeight: '50px'}}>
            <div className="col-8">
                <div className='text-center'>{ message }</div>
            </div>
            <NotificationIcons
                alwaysFullPage={alwaysFullPage}
                fullPage={fullPage}
                manageSidebarClick={manageSidebarClick}
                messageCount={messageCount}
                translations={translations}
            />
        </div>
    )
}

Notifications.propTypes = {
    message: PropTypes.object.isRequired,
    alwaysFullPage: PropTypes.bool.isRequired,
    fullPage: PropTypes.bool.isRequired,
    manageSidebarClick: PropTypes.func.isRequired,
    messageCount: PropTypes.number.isRequired,
    translations: PropTypes.object.isRequired,
};
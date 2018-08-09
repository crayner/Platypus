'use strict';

import React from 'react'
import { render } from 'react-dom'
import NotificationApp from './Notifications/NotificationApp'

render(
    <NotificationApp
        interval={window.NOTIFICATION_PROPS.interval}
        fullPage={window.NOTIFICATION_PROPS.fullPage}
        alwaysFullPage={window.NOTIFICATION_PROPS.alwaysFullPage}
        translations={window.NOTIFICATION_PROPS.translations}
    />,
    document.getElementById('notificationBar')
)

import React from 'react'
import { render } from 'react-dom'
import NotificationApp from './Notifications/NotificationApp'

render(
    <NotificationApp
        interval={window.NOTIFICATION_PROPS.interval}
    />,
    document.getElementById('notifications')
)

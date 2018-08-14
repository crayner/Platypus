'use strict';

import React from 'react'
import { render } from 'react-dom'
import AlarmApp from './Alarm/AlarmApp'

render(
    <AlarmApp
        translations={window.NOTIFICATION_PROPS.translations}
        locale={window.NOTIFICATION_PROPS.locale}
    />,
    document.getElementById('showAlarm')
)

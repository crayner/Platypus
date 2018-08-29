'use strict';

import React from 'react'
import { render } from 'react-dom'
import PhoneControl from './Phone/PhoneControl'

render(
    <PhoneControl
        locale={window.ADDRESS_PROPS.locale}
        translations={window.ADDRESS_PROPS.translations}
    />,
    document.getElementById('phoneContent')
)

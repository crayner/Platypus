'use strict';

import React from 'react'
import { render } from 'react-dom'
import PhoneControl from './Phone/PhoneControl'
import AddressControl from './Address/AddressControl'

render(
    <PhoneControl
        locale={window.ADDRESS_PROPS.locale}
        translations={window.ADDRESS_PROPS.translations}
        country={window.ADDRESS_PROPS.country}
        parentClass={window.ADDRESS_PROPS.parentClass}
        id={window.ADDRESS_PROPS.id}
        phoneTypeList={window.ADDRESS_PROPS.phoneTypeList}
    />,
    document.getElementById('phoneContent')
)

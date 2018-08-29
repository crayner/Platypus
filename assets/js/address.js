'use strict';

import React from 'react'
import { render } from 'react-dom'
import AddressControl from './Address/AddressControl'

render(
    <AddressControl
        locale={window.ADDRESS_PROPS.locale}
        translations={window.ADDRESS_PROPS.translations}
        parentClass={window.ADDRESS_PROPS.parentClass}
        id={window.ADDRESS_PROPS.id}
    />,
    document.getElementById('addressContent')
)

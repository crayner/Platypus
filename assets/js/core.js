'use strict';

import $ from 'jquery'
import 'popper.js'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css'
import 'promise-polyfill/src/polyfill'
import React from 'react'
import { render } from 'react-dom'
import TimetableApp from './Timetable/TimetableApp'
import DoNothing from './DoNothing'

const timetable = document.getElementById("timetableDisplay")
const content = timetable.innerHTML

if (timetable !== null) {
    render(
        <TimetableApp content={content} />,
        timetable
    )
}

render(
    <DoNothing />, document.getElementById("showAlarm")
)

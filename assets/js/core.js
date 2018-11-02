'use strict';

import $ from 'jquery'
import 'popper.js'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css'
import 'promise-polyfill/src/polyfill'
import React from 'react'
import { render } from 'react-dom'
import TimetableControl from './Timetable/TimetableControl'
import DoNothing from './DoNothing'

var timetable = document.getElementById("timetableDisplay");
console.log(timetable)
if (timetable !== null) {
    render(
        <TimetableControl {...window.CORE_PROPS} />,
        timetable
    )
}

render(
    <DoNothing />, document.getElementById("showAlarm")
)
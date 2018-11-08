'use strict';

import React from 'react'
import { render } from 'react-dom'
import TimetableApp from './Timetable/TimetableApp'
import DoNothing from './Component/DoNothing'

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

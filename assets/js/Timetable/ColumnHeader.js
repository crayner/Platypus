'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function ColumnHeader(props) {
    const {
        day,
        data,
    } = props

    return (
            <div className={'col-2 text-center align-self-center card column-header'} style={{color: day.timetableDay.fontColour, backgroundColor: day.timetableDay.colour, border: '1px solid ' + day.timetableDay.colour }}>
                <span className={'font-weight-bold'}>{day.timetableDay.nameShort}</span>
                <span className={'font-italic small'}>{day.dayShort}</span>
            </div>
    )
}

ColumnHeader.propTypes = {
    data: PropTypes.object.isRequired,
    day: PropTypes.object.isRequired,
}

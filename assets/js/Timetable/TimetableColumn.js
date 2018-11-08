'use strict';

import React from "react"
import PropTypes from 'prop-types'
import TimetablePeriod from './TimetablePeriod'

export default function TimetableColumn(props) {
    const {
        column,
        data,
    } = props

    let today = new Date()

    today = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2)
    const columnDate = column.day.date.slice(0,10)

    if (columnDate === today)
        today = true
    else
        today = false

    const periods = column.periods.map((period, key) => {
        return (<TimetablePeriod today={today} period={period} key={key} data={data}  />)
    })

    return (
        <div className={'column col-2 small text-center'}>
            {periods}
        </div>
    )
}

TimetableColumn.propTypes = {
    column: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
}

'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ColumnHeader from './ColumnHeader'

export default function ColumnHeaders(props) {
    const {
        control,
        data,
    } = props

    const timeHeader = (
        <div className={'col-2 text-center align-self-center card'} >
            {data.translate.week_number}<br/>
            <span className={'font-italic small'}>{data.translate.time}</span>
        </div>
    )

    const columnHeaders = control.columns.map(column => {
        return (<ColumnHeader data={data} day={column.day} key={column.day.date} />)
    })

    return (
            <div className={'row timetable-row'}>
                {timeHeader}
                {columnHeaders}
            </div>
    )
}

ColumnHeaders.propTypes = {
    data: PropTypes.object.isRequired,
    control: PropTypes.object.isRequired,
}

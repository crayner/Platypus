'use strict';

import React from "react"
import PropTypes from 'prop-types'
import TimetableTimeColumn from './TimetableTimeColumn'
import ColumnHeaders from './ColumnHeaders'
import TimetableColumn from './TimetableColumn'

export default function TimetableManagement(props) {
    const {
        control,
        data,
        ...otherProps
    } = props

    let times = ''
    if (control.showTimes)
        times = (<TimetableTimeColumn {...otherProps} control={control} />)

    const header = (<ColumnHeaders data={data} control={control}/>)

    const columns = Object.keys(control.columns).map(key => {
        const column = control.columns[key]
        return (<TimetableColumn {...otherProps} data={data} column={column} key={key} />)
    })

    return (
        <section>
                {header}
            <div className={'row timetable-row'}>
                {times}
                {columns}
            </div>
        </section>
    )
}

TimetableManagement.propTypes = {
    data: PropTypes.object.isRequired,
    control: PropTypes.object.isRequired,
}

'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function TimetableTimeColumn(props) {
    const {
        control,
    } = props

    const start = new Date('1970-01-01 ' + control.startTime)
    const end = new Date('1970-01-01 ' + control.endTime)
    const interval = Math.ceil(((end - start) / 1000) / 3600)

    let content = []
    for (let i = 0; i < interval; i++) {
        if (i === 0)
            content.push (
                <div style={{height: '45px'}} key={i}></div>
            )
        else
            content.push (
                <div className={'timeHour small text-center align-self-top'} key={i}>
                    {('0' + (i + start.getHours())).slice(-2)}:{('0' + start.getMinutes()).slice(-2)}
                </div>
            )
    }

    return (
            <div className={'col-2'}>
                {content}
            </div>
    )
}

TimetableTimeColumn.propTypes = {
    control: PropTypes.object.isRequired,
}

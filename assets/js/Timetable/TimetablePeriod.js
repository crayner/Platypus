'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { OverlayTrigger, Tooltip } from 'react-bootstrap'

export default function TimetablePeriod(props) {
    const {
        period,
        today,
        data,
        callUrl,
    } = props

    function timeDiff(start,end)
    {
        start = new Date('1970-01-01 ' + start)
        end = new Date('1970-01-01 ' + end)
        const diff = (end - start) / 60000
        return diff
    }

    const minutes  = timeDiff(period.timeStart, period.timeEnd)

    let size = 0
    let content = []
    let title = []

    if (size + 15 >= minutes)
        title.push((<p className={'text-center'} key={'name_' + period.id}>{period.name}</p>))
    else
        content.push((<p className={'text-center text-truncate'} key={'name_' + period.id}>{period.name}</p>))
    size += 15

    const time = period.timeStart.slice(0,-3) + '-' + period.timeEnd.slice(0,-3)
    if (size + 15 >= minutes)
        title.push((<p className={'font-italic text-center'} key={'time_' + period.id}>{time}</p>))
    else
        content.push((<p className={'font-italic text-center text-truncate'} key={'time_' + period.id}>{time}</p>))
    size += 15

    let periodClass = ''
    if (period.class !== false)
    {
        let url = '/department/course/class/{courseClass}/'
        const type = 'redirect'
        const options = {
            '{courseClass}': 'classId',
        }
        if (size + 15 >= minutes)
            title.push(<p className={'text-center period-class-name'} key={'className_' + period.id}>{period.class.className}</p>)
        else
            content.push((<p className={'text-center period-class-name text-truncate'} key={'className_' + period.id} onClick={() => callUrl(url,options,type,period.class)}>{period.class.className}</p>))
        size += 15

        if (size + 15 >= minutes)
            title.push((<p className={'text-center'} key={'facility_' + period.id}>{period.class.facilityName}</p>))
        else
            content.push((<p className={'text-center text-truncate'} key={'facility_' + period.id}>{period.class.facilityName}</p>))
        size += 15

        if (size + 15 >= minutes && period.class.phoneInt !== null)
            title.push((<p className={'text-center'} key={'phone_' + period.id}>{data.translate.phone + period.class.phoneInt}</p>))
        else
            content.push((<p className={'text-center text-truncate'} key={'phone_' + period.id}>{data.translate.phone + period.class.phoneInt}</p>))
        size += 15

        periodClass += ' period-class'
    }

    if (today) {
        let now = new Date()
        now = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':00'
        if (period.timeStart <= now && period.timeEnd > now)
            periodClass += ' period-now'
    }

    if (title.length > 0) {
        let tooltip = (<Tooltip id={'tooltip_' + period.id} bsClass={'timetable-tooltip'}>{title}</Tooltip>)
        return (
            <OverlayTrigger
                overlay={tooltip}
                placement="top"
                key={'tooltip_' + period.id}
                delayShow={300}
                delayHide={150}>
                <div className={'period' + periodClass} style={{height: (minutes + 1) + 'px'}}>{content}</div>
            </OverlayTrigger>
        )
    } else
        return (
            <div className={'period' + periodClass} style={{height: (minutes + 1) + 'px'}}>
                {content}
            </div>
        )

}

TimetablePeriod.propTypes = {
    today: PropTypes.bool.isRequired,
    period: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    callUrl: PropTypes.func.isRequired,
}


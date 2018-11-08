'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ReactTooltip from 'react-tooltip'

export default function TimetablePeriod(props) {
    const {
        period,
        today,
        data,
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
    let title = ''

    if (size + 15 >= minutes)
        title += "<p>" + period.name + "</p>"
    else
        content.push((<p className={'text-center text-truncate'} key={period.id + period.name}>{period.name}</p>))
    size += 15

    const time = period.timeStart.slice(0,-3) + '-' + period.timeEnd.slice(0,-3)
    if (size + 15 >= minutes)
        title += "<p>" + time + "</p>"
    else
        content.push((<p className={'font-italic text-center text-truncate'} key={period.id + time}>{time}</p>))
    size += 15

    let periodClass = ''
    if (period.class !== false)
    {
        if (size + 15 >= minutes)
            title += "<p>" + period.class.className + "</p>"
        else
            content.push((<p className={'text-center period-class-name text-truncate'} key={period.id + period.class.className}>{period.class.className}</p>))
        size += 15

        if (size + 15 >= minutes)
            title += "<p>" + period.class.facilityName + "</p>"
        else
            content.push((<p className={'text-center text-truncate'} key={period.id + period.class.facilityName}>{period.class.facilityName}</p>))
        size += 15

        if (size + 15 >= minutes && period.class.phoneInt !== null)
            title += "<p>" + data.translate.phone + period.class.phoneInt + "</p>"
        else
            content.push((<p className={'text-center text-truncate'} key={period.id + period.class.phoneInt}>{data.translate.phone + period.class.phoneInt}</p>))
        size += 15

        periodClass += ' period-class'
    }

    if (today) {
        let now = new Date()
        now = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':00'
        if (period.timeStart <= now && period.timeEnd > now)
            periodClass += ' period-now'
    }
    return (
        <div className={'period' + periodClass} style={{height: (minutes + 1) + 'px'}} data-tip={title}>
            {content}
            <ReactTooltip html={true} delayUpdate={500} />
        </div>
    )

}

TimetablePeriod.propTypes = {
    today: PropTypes.bool.isRequired,
    period: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
}


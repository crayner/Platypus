'use strict';

import React from "react"
import PropTypes from 'prop-types'
import TimetableHeader from './TimetableHeader'
import TimetableManagement from './TimetableManagement'
import Parser from 'html-react-parser'
import TimetableController from './TimetableController'
import '../../css/timetable.scss'

export default function TimetableRender(props) {
    const {
        data,
        control,
        ...otherProps
    } = props

    if (control.render === undefined)
    {
        const content = otherProps.content
        return (Parser(content))
    }

    if (control.render === false && data.default.length > 0)
    {
        const content = data.default.map((words, key) =>{
            switch (words.type){
                case 'p':
                    return <p key={key}>{words.content}</p>
                default:
                    return <p key={key}>{words.content}</p>
            }
        })
        return (
            <section>
                {content}
            </section>
        )
    }

    return (
        <div className={'container-fluid timetable-display'}>
            <TimetableHeader {...otherProps} header={{...data.header}} />
            <TimetableController {...otherProps} data={data} control={control} />
            <TimetableManagement {...otherProps} data={data} control={control} />
        </div>
    )
}

TimetableRender.propTypes = {
    data: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]),
    control: PropTypes.object.isRequired,
}

TimetableRender.defaultProps = {
    data: {},
}

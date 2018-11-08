'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonManager from '../Component/Button/ButtonManager'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCalendar, faCalendarCheck, faCalendarPlus, faCalendarMinus } from '@fortawesome/free-regular-svg-icons'
import DatePicker from 'react-date-picker'

export default function TimetableController(props) {
    const {
        control,
        data,
    } = props

    const prev = {
        icon: faCalendarMinus,
        type: 'misc',
        colour: 'info',
        attr: {'data-date': control.date.value, 'data-type': 'prevWeek'},
        title: data.translate.prevWeek,
        mergeClass: 'btn-sm',
    }

    const home = {
        icon: faCalendarCheck,
        type: 'misc',
        colour: 'info',
        attr: {'data-date': control.date.value, 'data-type': 'now'},
        mergeClass: 'btn-sm',
        title: data.translate.today,
        disabled: control.home.disabled,
    }

    const next = {
        icon: faCalendarPlus,
        type: 'misc',
        colour: 'info',
        mergeClass: 'btn-sm',
        attr: {'data-date': control.date.value, 'data-type': 'nextWeek'},
        title: data.translate.nextWeek,
    }

    control.date.value = new Date(control.date.value)
    control.date.minDate = new Date(control.date.minDate)
    control.date.maxDate = new Date(control.date.maxDate)
    control.date.clearIcon = null
    control.date.calendarIcon = <FontAwesomeIcon icon={faCalendar} />
    control.date.onChange = (e) => control.changeDate(control.date,e)
    control.date.className = 'small'

    return (
        <div className={'row alert-info'}>
            <div className={'col-4 offset-8'}>
                <div className="input-group">
                    <div className="input-group-prepend">
                        <ButtonManager button={{...prev}} miscButtonHandler={control.changeDate} />
                        <ButtonManager button={{...home}} miscButtonHandler={control.changeDate} />
                    </div>
                    <DatePicker {...control.date} />
                    <div className="input-group-append">
                        <ButtonManager button={{...next}} miscButtonHandler={control.changeDate} />
                    </div>
                </div>


            </div>
        </div>
    )
}

TimetableController.propTypes = {
    data: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]),
    control: PropTypes.object.isRequired,
}

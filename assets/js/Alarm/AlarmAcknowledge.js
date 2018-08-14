'use strict';

import React from 'react'
import PropTypes from 'prop-types'

export default function AlarmAcknowledge(props) {
    const {
        currentPerson,
        acknowledgeAlarm,
    } = props;

    if (Object.keys(currentPerson).length === 0)
        return (
            ''
        )

    return (
        <div className={'currentPerson row'}>
            <div className={'col-12 text-center lead'} onClick={acknowledgeAlarm(currentPerson.id)}>{currentPerson.fullName} Click to Acknowledge Alarm <span className={'text-info alert-info far fa-clipboard-check'}></span></div>
        </div>
    )

}

AlarmAcknowledge.propTypes = {
    currentPerson: PropTypes.object.isRequired,
    acknowledgeAlarm: PropTypes.func.isRequired,
}
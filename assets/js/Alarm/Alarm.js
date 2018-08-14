'use strict';

import React from 'react'
import Sound from 'react-sound';
import PropTypes from 'prop-types'
import { translateMessage } from "../Component/MessageTranslator"
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faTimesCircle } from '@fortawesome/free-regular-svg-icons';
import { faBellSlash } from '@fortawesome/free-regular-svg-icons';

export default function Alarm(props) {
    const {
        status,
        type,
        currentUser,
        modal,
        translations,
        closeAlarmWindow,
        turnOffTheAlarm,
        escFunction,
        soundUrl,
    } = props;

    var className = ''

    if (type !== 'none')
        className = 'alarm' + type.charAt(0).toUpperCase() + type.substr(1)

    const turnAlarmOff = translateMessage(translations, 'alarm.turn.off.title')
    const alarmTitle = translateMessage(translations, 'alarm.' + type + '.title')

    if (modal)
        document.body.classList.add('displayAlarm')
    else
        document.body.classList.remove('displayAlarm')

    if (type !== 'none' && status === 'current')
        return (
            <div className={className} onKeyDown={escFunction}>
                <div className={'outerAlarm text-center'}>
                    <div className={'closeAlarm'} onClick={closeAlarmWindow}>
                        <FontAwesomeIcon icon={faTimesCircle}/> or Esc Key
                    </div>

                    <div className={'innerAlarm text-center'}>
                        {currentUser ?  <span title={turnAlarmOff}><FontAwesomeIcon icon={faBellSlash} onClick={turnOffTheAlarm} color={'white'}/></span> : ''}
                        <h1>{alarmTitle}</h1>
                    </div>
                </div>
                <Sound
                    url={soundUrl}
                    volume={55}
                    loop={true}
                    playStatus={'PLAYING'}
                />

            </div>
        )

    return (
        <section>
        </section>
    )
}

Alarm.propTypes = {
    status: PropTypes.string.isRequired,
    type: PropTypes.string.isRequired,
    currentUser: PropTypes.bool.isRequired,
    modal: PropTypes.bool.isRequired,
    closeAlarmWindow: PropTypes.func.isRequired,
    turnOffTheAlarm: PropTypes.func.isRequired,
    escFunction: PropTypes.func.isRequired,
    soundUrl: PropTypes.string.isRequired,
    translations: PropTypes.object.isRequired,
}
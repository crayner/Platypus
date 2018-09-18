'use strict';

import React, { Component } from "react"
import { getAlarm, closeAlarm, acknowledgeAlarm } from "./alarm_api";
import PropTypes from 'prop-types'
import Alarm from './Alarm'


export default class AlarmApp extends Component {
    constructor(props) {
        super(props)

        this.state  = {
            status: 'past',
            type: 'none',
            currentUser: false,
            modal: false,
            currentPerson: new Object(),
            volume: 100,
            staffList: {},
        }

        this.translations = props.translations
        this.locale = props.locale
        this.refreshTimeout = 0
        this.soundUrl = ''
        this.permission = false
        this.config = props.config
        this.closeAlarmWindow = this.closeAlarmWindow.bind(this)
        this.turnOffTheAlarm = this.turnOffTheAlarm.bind(this)
        this.escFunction = this.escFunction.bind(this)
        this.acknowledgeAlarm = this.acknowledgeAlarm.bind(this)
    }

    componentDidMount() {
        document.body.addEventListener("keydown", this.escFunction, false);
        getAlarm(this.locale)
            .then((data) => {
                this.handleNewAlarm(data)
            });
        this.refreshAlarm()
    }

    componentWillUnmount(){
        document.body.removeEventListener("keydown", this.escFunction, false);
        clearTimeout(this.refreshTimeout)
    }

    handleNewAlarm(data) {
        this.permission = data.permission
        this.currentPerson = data.currentPerson

        data = data.alarm
        var change = false
        if (data.type !== this.state.type)
            change = true
        if (data.status !== this.state.status)
            change = true
        if (data.currentUser !== this.state.currentUser)
            change = true
        if (data.status === 'current' && data.type !== 'none')
            data['modal'] = true
        else
            data['modal'] = false
        if (data.modal !== this.state.modal)
            change = true
        if (data.volume !== this.state.volume)
            change = true
        if (this.state.staffList !== data.staffList)
            change = true
        if (typeof(data.staffList) !== 'object')
            data.staffList = {}

        this.soundUrl = data.customFile

        if (change)
            this.setState({
                ...data
            })
    }

    refreshAlarm(timeOut = 5000){
        clearTimeout(this.refreshTimeout)
        this.refreshTimeout =  setTimeout(() => {
            getAlarm(this.locale)
                .then((data) => {
                    this.handleNewAlarm(data)
                });
            this.refreshAlarm()
        }, timeOut)
    }

    escFunction(event){
        if(event.keyCode === 27) {
            this.closeAlarmWindow()
        }
    }

    closeAlarmWindow() {
        clearTimeout(this.refreshTimeout)
        this.setState({
            modal: false,
            type: 'none',
        })
        this.refreshAlarm(30000)
    }

    turnOffTheAlarm()
    {
        closeAlarm(this.locale)
            .then((data) => {
                this.setState({
                    status: 'past',
                    modal: false,
                })
                this.handleNewAlarm(data)
            })

    }

    acknowledgeAlarm(person)
    {
        if (person === 0)
            return
        acknowledgeAlarm(person, this.locale)
            .then((data) => {
                this.setState({
                    currentPerson: new Object(),
                })
            })
    }

    render() {
        return (
            <Alarm
                status={this.state.status}
                type={this.state.type}
                currentUser={this.state.currentUser}
                modal={this.state.modal}
                translations={this.translations}
                closeAlarmWindow={this.closeAlarmWindow}
                turnOffTheAlarm={this.turnOffTheAlarm}
                escFunction={this.escFunction}
                soundUrl={this.soundUrl}
                staffList={this.state.staffList}
                permission={this.permission}
                currentPerson={this.state.currentPerson}
                acknowledgeAlarm={this.acknowledgeAlarm}
                volume={this.state.volume}
            />
        )
    }
}

AlarmApp.propTypes = {
    translations: PropTypes.object.isRequired,
    locale: PropTypes.string.isRequired,
}
AlarmApp.defaultTypes = {
    locale: 'en'
}

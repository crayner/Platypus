'use strict';

import React, { Component } from "react"
import { getAlarm, closeAlarm } from "./alarm_api";
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
        }

        this.translations = props.translations
        this.locale = props.locale
        this.refreshTimeout = 0
        this.soundUrl = ''
        this.closeAlarmWindow = this.closeAlarmWindow.bind(this)
        this.turnOffTheAlarm = this.turnOffTheAlarm.bind(this)
        this.escFunction = this.escFunction.bind(this)
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
        data = data.alarm
        var change = false
        if (data.type !== this.state.type)
            change = true
        if (data.status !== this.state.status) {
            change = true
        }
        if (data.currentUser !== this.state.currentUser)
            change = true
        if (data.status === 'current' && data.type !== 'none')
            data['modal'] = true
        else
            data['modal'] = false
        if (data.modal !== this.state.modal)
            change = true
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
            });

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
            />
        )
    }
}

AlarmApp.propTypes = {
    translations: PropTypes.object.isRequired,
    locale: PropTypes.string.isRequired,
}

'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import {fetchJson} from '../Component/fetchJson'
import TimetableRender from './TimetableRender'
import {openPage} from '../Component/openPage'

export default class TimetableApp extends Component {
    constructor(props) {
        super(props)
        this.content = props.content
        this.locale = props.locale ? props.locale : 'en'

        this.data = {}
        this.messages = {}

        this.changeDate = this.changeDate.bind(this)
        this.checkTime = this.checkTime.bind(this)
        this.callUrl = this.handleURLCall.bind(this)

        this.control = this.getControl()


        this.time = new Date()
        this.time = this.time.getHours() + this.time.getMinutes()

        this.state = {
            data: this.data,
            messages: this.messages,
            control: this.control,
            content: this.content,
            time: this.time,
        }
        this.calls = {
            callUrl: this.callUrl,
        }
    }

    checkTime(){
        const time = new Date()
        if (this.time !== time.getHours() + time.getMinutes())
        {
            this.time = time.getHours() + time.getMinutes()
            this.setState({
                data: this.data,
                messages: this.messages,
                control: this.control,
                time: this.time,
            })
        }
    }

    componentDidMount() {
        if (Object.keys(this.data).length === 0)
            this.loadTimetable(this.data)
        else
            this.loadTimetable(...this.data)
        this.interval = setInterval(this.checkTime, 1000)
    }

    componentWillUnmount() {
        clearInterval(this.interval)
    }

    changeDate(button, e){
        if (e instanceof Date){
            const year = e.getFullYear()
            const month = ('0' + (e.getMonth() + 1)).slice(-2)
            const day = ('0' + e.getDate()).slice(-2)
            button = {
                attr: {
                    'data-type': 'goto',
                    'data-date': year + '-' + month + '-' + day,
                }
            }
        }
        this.loadTimetable({...this.data, ...button.attr})
    }

    loadTimetable(data){
        let path = '/timetable/display/'
        if (data !== undefined && data.control !== undefined)
            delete data.control
        fetchJson(path, {method: 'POST', body: JSON.stringify(data)}, this.locale)
            .then(result => {
                this.messages = result.messages
                this.addReactControl(result.data)

                this.setState({
                    data: this.data,
                    messages: this.messages,
                    control: this.control,
                    time: this.time,
                })
            })
    }

    getControl(){
        this.control = {
            locale: this.locale,
            changeDate: this.changeDate,
        }
        return this.control
    }

    addReactControl(data){
        if (this.locale !== data.control.locale)
            this.locale = data.control.locale
        this.control = {...this.getControl(), ...data.control}
        delete data.control
        this.data = data
        return this.control
    }

    handleURLCall(url,options,type,element) {
        if (typeof options !== 'object')
            options = {}
        let found = true
        Object.keys(options).map(search => {
            let replace = element[options[search]]
            if (search === '{id}' && (!replace || /^\s*$/.test(replace)))
                replace = 'Add'
            url = url.replace(search, replace)
            if (replace === undefined || replace === null)
                found = false
        })
        if (!found) return false
        if (type === 'redirect') {
            openPage(url, {method: 'GET'}, this.locale)
        } else {
            fetchJson(url, {method: 'GET'}, this.locale)
                .then(data => {
                    this.elementList = {}
                    this.messages = this.messages.concat(data.messages)
                    this.form = data.form
                    if (!(!data.template || /^\s*$/.test(data.template)))
                        this.template = data.template
                    this.setState({
                        form: this.form,
                        messages: this.messages,
                        template: this.template,
                    })
                }).catch(error => {
                console.error('Error: ', error)
                this.messages.push({level: 'danger', message: error})
                this.setState({
                    form: this.form,
                    messages: this.messages,
                    template: this.template,
                })
            })
        }
        return true
    }

    render() {
        return (
            <TimetableRender  {...this.calls} {...this.state} />
        )
    }
}


TimetableApp.propTypes = {
    locale: PropTypes.string,
    content: PropTypes.string,
}

TimetableApp.defaultTypes = {
    locale: 'en',
}



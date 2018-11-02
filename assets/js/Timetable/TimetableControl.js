'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import {fetchJson} from '../Component/fetchJson'

export default class TimetableControl extends Component {
    constructor(props) {
        super(props)
        this.locale = props.locale
        this.otherProps = {...props}

        this.timetable = {}
        this.data = {}

        this.state = {
            timetable: this.timetable,
        }
    }

    componentWillMount() {
        this.loadTimetable()
    }

    loadTimetable(){
        let path = '/timetable/display/'

        fetchJson(path, {method: 'POST', body: JSON.stringify(this.data)}, this.locale)
            .then(data => {
                this.timetable = data.timetable
                this.messages = data.messages
                this.data = data.data
                this.setState({
                    timetable: this.timetable,
                    data: this.data,
                })
            })
    }

    render() {
        return (
            <div>timetable</div>
        )
    }
}


TimetableControl.propTypes = {
    locale: PropTypes.string,
}

TimetableControl.defaultTypes = {
    locale: 'en',
}



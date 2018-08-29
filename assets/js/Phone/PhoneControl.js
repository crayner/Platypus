'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'

export default class PhoneControl extends Component {
    constructor(props) {
        super(props)

        this.locale = props.locale
        this.translations = props.translations
    }

    componentWillMount(){
    }

    render() {
        return (
            <div> Phone Stuff </div>
        )
    }
}

PhoneControl.propTypes = {
    locale: PropTypes.string,
    translations: PropTypes.object.isRequired,
}

PhoneControl.defaultTypes = {
    locale: 'en',
}


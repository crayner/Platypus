'use strict'

import React, { Component } from "react"
import Parser from 'html-react-parser'

export default class DoNothing extends Component {
    render() {
        return (<span>{Parser('<!--  the do nothing was here! -->')}</span>)
    }
}

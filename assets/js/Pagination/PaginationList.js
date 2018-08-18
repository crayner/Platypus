'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'

export default class PaginationList extends Component {
    constructor(props) {
        super(props)
        this.rows = props.rows
        this.translations = props.translations
        this.locale = props.locale
        this.offset = props.offset
        this.limit = props.limit

        console.log(this)
    }


    render() {
        return (
            ''
        )
    }
}


PaginationList.propTypes = {
    locale: PropTypes.string.isRequired,
    rows: PropTypes.array.isRequired,
    translations: PropTypes.object.isRequired,
    offset: PropTypes.number.isRequired,
    limit: PropTypes.number.isRequired,
}

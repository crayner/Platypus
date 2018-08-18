'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import PaginationList from './PaginationList'

export default class PaginationTitle extends Component {
    constructor(props) {
        super(props)
        this.rows = props.rows
        this.translations = props.translations
        this.locale = props.locale
        this.offset = props.offset
        this.limit = props.limit
    }

    render() {
        return (
            <section>
                <PaginationList
                    locale={this.locale}
                    rows={this.rows}
                    translations={this.translations}
                    limit={this.limit}
                    offset={this.offset}
                />
            </section>
        )
    }
}

PaginationTitle.propTypes = {
    locale: PropTypes.string.isRequired,
    rows: PropTypes.array.isRequired,
    translations: PropTypes.object.isRequired,
    offset: PropTypes.number.isRequired,
    limit: PropTypes.number.isRequired,
}


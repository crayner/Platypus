'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import { translateMessage } from "../Component/MessageTranslator"

export default class PaginationSort extends Component {
    constructor(props) {
        super(props)

        this.name = props.name
        this.translations = props.translations

        var x = 0
        this.options = Object.keys(props.sortOptions).map(key =>
            <option value={key} key={x++}>{translateMessage(this.translations, props.sortOptions[key])}</option>
        )
    }


    render() {
        return (
            <section>
                <label className="control-label">{translateMessage(this.translations, 'pagination.sort.label')}</label>
                <div className="input-group input-group-sm">
                    <select id={this.name + '_sortbyName'} name={this.name + '[sortbyName]'}
                            className="form-inline form-control"
                            autoComplete="off">
                        {this.options}
                    </select>
                </div>
            </section>
        )
    }
}

PaginationSort.propTypes = {
    name: PropTypes.string.isRequired,
    translations: PropTypes.object.isRequired,
    sortOptions: PropTypes.object.isRequired,
}


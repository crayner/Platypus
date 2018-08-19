'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { translateMessage } from "../Component/MessageTranslator"

export default function PaginationSort(props) {
    const {
        name,
        translations,
        sortOptions,
    } = props

    function buildOptions() {
        var x = 0
        var options = Object.keys(sortOptions).map(key =>
            <option value={key} key={x++}>{translateMessage(translations, sortOptions[key])}</option>
        )
        return options
    }

    return (
        <section>
            <label className="control-label">{translateMessage(translations, 'pagination.sort.label')}</label>
            <div className="input-group input-group-sm">
                <select id={name + '_sortbyName'} name={name + '[sortbyName]'}
                        className="form-inline form-control"
                        autoComplete="off">
                    {buildOptions()}
                </select>
            </div>
        </section>
    )
}

PaginationSort.propTypes = {
    name: PropTypes.string.isRequired,
    translations: PropTypes.object.isRequired,
    sortOptions: PropTypes.object.isRequired,
}


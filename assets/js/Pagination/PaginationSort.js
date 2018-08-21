'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { translateMessage } from "../Component/MessageTranslator"
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faSortAlphaDown, faSortAlphaUp} from '@fortawesome/free-solid-svg-icons'

export default function PaginationSort(props) {
    const {
        name,
        translations,
        sortOptions,
        changeTheSort,
        orderBy,
        toggleOrderBy,
        sort,
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
                        autoComplete="off"
                        defaultValue={sort}
                        onChange={changeTheSort}>
                    {buildOptions()}
                </select>
                <span className="input-group-append">
                    <button name={name + '[next]'} title={translateMessage(translations, 'order_by')} className="btn btn-info"
                            style={{height: '31px'}} id={name + '_next'} onClick={toggleOrderBy}>
                        <FontAwesomeIcon icon={(orderBy === 1 ? faSortAlphaDown : faSortAlphaUp)}/>
                    </button>
                </span>
            </div>
        </section>
    )
}

PaginationSort.propTypes = {
    name: PropTypes.string.isRequired,
    translations: PropTypes.object.isRequired,
    sortOptions: PropTypes.object.isRequired,
    changeTheSort: PropTypes.func.isRequired,
    toggleOrderBy: PropTypes.func.isRequired,
    orderBy: PropTypes.number.isRequired,
    sort: PropTypes.string.isRequired,
}


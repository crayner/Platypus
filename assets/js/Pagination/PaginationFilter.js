'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { translateMessage } from "../Component/MessageTranslator"
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faEraser } from '@fortawesome/free-solid-svg-icons'

export default function PaginationFilter(props) {
    const {
        name,
        translations,
        filter,
        clearFilter,
        changeFilterValue,
    } = props

    const filterOptions = filter.map((group, key) =>
        <optgroup key={key} label={translateMessage(translations, name + '.filter.group.' + group['name'])}>
            {getOptions(group)}
        </optgroup>
    )

    function getOptions(group)
    {
        const options = group['fields'].map((field, key) =>
            <option key={key} value={field['name']}>{translateMessage(translations, field['label'])}</option>
        )
        return options
    }

    return (
        <section>
            <label className="control-label">{translateMessage(translations, 'pagination.filter.label')}</label>
            <div className="input-group input-group-sm">
                <select id={name + '_filter'} name={name + '[filter]'} className="form-control" autoComplete="off" defaultValue='0' onChange={changeFilterValue}>
                    <option>{translateMessage(translations, 'pagination.filter.placeholder')}</option>
                    {filterOptions}
                </select>
                <span className="input-group-append">
                    <button name={name + '[next]'} title={translateMessage(translations, 'pagination.filter.clear')} className="btn btn-info"
                            style={{height: '31px'}} id={name + '_next'} onClick={() => {clearFilter()}} >
                        <FontAwesomeIcon icon={faEraser} />
                    </button>
                </span>
            </div>
        </section>
    )
}

PaginationFilter.propTypes = {
    name: PropTypes.string.isRequired,
    translations: PropTypes.object.isRequired,
    filter: PropTypes.array.isRequired,
    clearFilter: PropTypes.func.isRequired,
    changeFilterValue: PropTypes.func.isRequired,
}

PaginationFilter.defaultProps = {
    filterValue: []
}


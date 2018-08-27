'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { translateMessage } from "../Component/MessageTranslator"

export default function PaginationSearch(props) {
    const {
        name,
        translations,
        search,
        changeTheSearch,
        caseSensitive,
        toggleCaseSensitive
    } = props

    return (
        <section>
            <label className="control-label">{translateMessage(translations, 'pagination.search.label')}</label>
            <div className="input-group input-group-sm">
                <input type="text" id={name + '_searchData'} name={name + '[searchData]'}
                       placeholder={translateMessage(translations, 'pagination.search.placeholder')} className="form-inline form-control" onChange={changeTheSearch} autoComplete="off" defaultValue={search} />
                <span className="input-group-append">
                    <button name={name + '[next]'} title={translateMessage(translations, 'case_sensitive')} className="btn btn-info"
                            style={{height: '31px'}} id={name + '_next'} onClick={toggleCaseSensitive}>
                        {caseSensitive ? 'Az' : 'az'}
                    </button>
                </span>
            </div>
        </section>
    )
}

PaginationSearch.propTypes = {
    name: PropTypes.string.isRequired,
    translations: PropTypes.object.isRequired,
    search: PropTypes.string.isRequired,
    changeTheSearch: PropTypes.func.isRequired,
    caseSensitive: PropTypes.bool.isRequired,
    toggleCaseSensitive: PropTypes.func.isRequired,
}


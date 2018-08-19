'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {faSearch} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { translateMessage } from "../Component/MessageTranslator"

export default function PaginationSearch(props) {
    const {
        name,
        translations,
        search,
    } = props

    return (
        <section>
            <label className="control-label">{translateMessage(translations, 'pagination.search.label')}</label>
            <div className="input-group input-group-sm">
                <input type="text" id={name + '_searchData'} name={name + '[searchData]'}
                       placeholder={translateMessage(translations, 'pagination.search.placeholder')} className="form-inline form-control" autoComplete="off" defaultValue={search} />
                <span className="input-group-append">
                    <button name={translateMessage(translations, 'search')} title={translateMessage(translations, 'search')} className={'btn btn-success'}>
                        <FontAwesomeIcon
                            icon={faSearch}
                        />
                    </button>
                </span>
            </div>
        </section>
    )
}

PaginationSearch.propTypes = {
    name: PropTypes.string.isRequired,
    translations:  PropTypes.object.isRequired,
    search:  PropTypes.string.isRequired,
}


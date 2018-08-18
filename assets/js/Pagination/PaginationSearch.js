'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import {faSearch} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { translateMessage } from "../Component/MessageTranslator"

export default class PaginationSearch extends Component {
    constructor(props) {
        super(props)
        this.name = props.name
        this.translations = props.translations
        this.search = props.search
    }

    render() {
        return (
            <section>
                <label className="control-label">{translateMessage(this.translations, 'pagination.search.label')}</label>
                <div className="input-group input-group-sm">
                    <input type="text" id={this.name + '_searchData'} name={this.name + '[searchData]'}
                           placeholder={translateMessage(this.translations, 'pagination.search.placeholder')} className="form-inline form-control" autoComplete="off" defaultValue={this.search} />
                    <span className="input-group-append">
                        <button name={translateMessage(this.translations, 'search')} title={translateMessage(this.translations, 'search')} className={'btn btn-success'}>
                            <FontAwesomeIcon
                                icon={faSearch}
                            />
                        </button>
                    </span>
                </div>
            </section>
        )
    }
}

PaginationSearch.propTypes = {
    name: PropTypes.string.isRequired,
    translations:  PropTypes.object.isRequired,
    search:  PropTypes.string.isRequired,
}


'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import {faBackward, faForward} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { translateMessage } from "../Component/MessageTranslator"

export default class PaginationLimit extends Component {
    constructor(props) {
        super(props)
        this.name = props.name
        this.translations = props.translations
        this.limit = props.limit
        this.changeLimit = props.changeLimit
        this.nextPage = props.nextPage
        this.previousPage = props.previousPage
    }

    render() {
        return (
            <section>
                <div className="text-right">
                    <label className="control-label">{translateMessage(this.translations, 'pagination.limit.label')}</label>
                    <div className="input-group input-group-sm">
                        <span className="input-group-prepend">
                            <button name={this.name + '[prev]'} title={translateMessage(this.translations, 'previous')} className="btn btn-info"
                                    style={{height: '31px'}} id={this.name + '_prev'} onClick={this.previousPage}>
                                <FontAwesomeIcon icon={faBackward}/>
                            </button>
                        </span>
                        <select id={this.name + '_limit'} name={this.name + '[limit]'} autoComplete="off" className="form-control" defaultValue={this.limit} onChange={this.changeLimit}>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span className="input-group-append">
                            <button name={this.name + '[next]'} title={translateMessage(this.translations, 'next')} className="btn btn-info"
                                    style={{height: '31px'}} id={this.name + '_next'} onClick={this.nextPage}>
                                <FontAwesomeIcon icon={faForward}/>
                            </button>
                        </span>
                    </div>
                </div>
            </section>

        )
    }
}

PaginationLimit.propTypes = {
    name: PropTypes.string.isRequired,
    translations: PropTypes.object.isRequired,
    limit: PropTypes.number.isRequired,
    changeLimit: PropTypes.func.isRequired,
    nextPage: PropTypes.func.isRequired,
    previousPage: PropTypes.func.isRequired,
}


'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {faBackward, faForward} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { translateMessage } from "../Component/MessageTranslator"

export default function PaginationLimit(props) {
    const {
        name,
        translations,
        limit,
        changeLimit,
        nextPage,
        previousPage,
    } = props

    return (
        <section>
            <div className="text-right">
                <label className="control-label">{translateMessage(translations, 'pagination.limit.label')}</label>
                <div className="input-group input-group-sm">
                    <span className="input-group-prepend">
                        <button name={name + '[prev]'} title={translateMessage(translations, 'previous')} className="btn btn-info"
                                style={{height: '31px'}} id={name + '_prev'} onClick={previousPage}>
                            <FontAwesomeIcon icon={faBackward}/>
                        </button>
                    </span>
                    <select id={name + '_limit'} name={name + '[limit]'} autoComplete="off" className="form-control" defaultValue={limit} onChange={changeLimit}>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span className="input-group-append">
                        <button name={name + '[next]'} title={translateMessage(translations, 'next')} className="btn btn-info"
                                style={{height: '31px'}} id={name + '_next'} onClick={nextPage}>
                            <FontAwesomeIcon icon={faForward}/>
                        </button>
                    </span>
                </div>
            </div>
        </section>
    )
}

PaginationLimit.propTypes = {
    name: PropTypes.string.isRequired,
    translations: PropTypes.object.isRequired,
    limit: PropTypes.number.isRequired,
    changeLimit: PropTypes.func.isRequired,
    nextPage: PropTypes.func.isRequired,
    previousPage: PropTypes.func.isRequired,
}


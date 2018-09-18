'use strict';

import React from "react"
import PropTypes from 'prop-types'
import PaginationList from './PaginationList'
import {translateMessage} from '../Component/MessageTranslator'
import PaginationColumnHeader from './PaginationColumnHeader'
import PaginationActionHeader from './PaginationActionHeader'
import ButtonManager from "../Component/Button/ButtonManager";

export default function PaginationTitle(props) {
    const {
        rows,
        translations,
        columnDefinitions,
        headerDefinition,
        sort,
        orderBy,
        actions,
        ...otherProps
    } = props;

    var columns = Object.keys(columnDefinitions).map(key =>
        <PaginationColumnHeader
            item={columnDefinitions[key]}
            key={key}
            translations={translations}
            sort={sort}
            orderBy={orderBy}
        />
    )

    var buttons = headerDefinition.buttons.map(function(button,key){

        let url = button.url

        if (typeof(button.url_options) !== 'object')
            button.url_options = new Object()

        Object.keys(button.url_options).map(function(key) {
            const value = button.url_options[key]
            url = url.replace(key, item[value])
        })

        return <ButtonManager
            button={button}
            url={url}
            key={key}
            translations={translations}
            {...otherProps}
        />
    })

    return (
        <div className="container_fluid">
            <div className="card card-warning">
                <div className="card-header">
                    <h3 className="card-title d-flex mb-12 justify-content-between">
                        <span className="p-6">{translateMessage(translations, headerDefinition.title)}</span>
                        <span className="p-6">{buttons}</span>
                    </h3>
                    <p>{headerDefinition.paragraph ? translateMessage(translations, headerDefinition.paragraph) : '' }</p>
                </div>
                <div className="card-body">
                    <div className="row row-header">
                        {columns}
                        <PaginationActionHeader
                            translations={translations}
                            actions={actions}
                        />
                    </div>
                    <PaginationList
                        rows={rows}
                        translations={translations}
                        columnDefinitions={columnDefinitions}
                        actions={actions}
                        {...otherProps}
                    />
                </div>
            </div>
        </div>
    )
}

PaginationTitle.propTypes = {
    rows: PropTypes.array.isRequired,
    translations: PropTypes.object.isRequired,
    columnDefinitions: PropTypes.object.isRequired,
    headerDefinition: PropTypes.object.isRequired,
    sort: PropTypes.string.isRequired,
    orderBy: PropTypes.number.isRequired,
    actions:  PropTypes.object.isRequired,
}

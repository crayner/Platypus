'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonManager from '../Component/Button/ButtonManager'


export default function PaginationActionCell(props) {
    const {
        actions,
        item,
        ...otherProps,
    } = props

    const xxx = Object.assign(actions.buttons, {})
    const buttons = Object.keys(xxx).map((key) => {
            let button = xxx[key]

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
                {...otherProps}
            />
        }
    )

    return (
        <div className={actions.class + ' card-text col-' + actions.size}>
            {buttons}
        </div>
    )
}
PaginationActionCell.propTypes = {
    translations: PropTypes.object.isRequired,
    actions: PropTypes.object.isRequired,
    item: PropTypes.object.isRequired,
}

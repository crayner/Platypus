'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonManager from '../Component/Button/ButtonManager'

export default function PaginationActionCell(props) {
    const {
        actions,
        item,
        ...otherProps
    } = props

    const xxx = Object.assign(actions.buttons, {})
    const buttons = Object.keys(xxx).map((key) => {
            let button = {...xxx[key]}

            if (typeof(button.options) !== 'object')
                button.options = {}

            let options = {}
            Object.keys(button.options).map(key => {
                const value = button.options[key]
                options[key] = typeof item[value] !== 'undefined' ? item[value] : value
            })
            delete button.options
            button.options = Object.assign(item, options)

            return <ButtonManager
                button={button}
                key={key}
                {...otherProps}
            />
        }
    )

    return (
        <div className={actions.class === '' ? ('card-text col-' + actions.size) : (actions.class + ' ' + 'card-text col-' + actions.size)}>
            {buttons}
        </div>
    )
}
PaginationActionCell.propTypes = {
    translations: PropTypes.object.isRequired,
    actions: PropTypes.object.isRequired,
    item: PropTypes.object.isRequired,
}

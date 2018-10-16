'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRows from './FormRows'
import ButtonManager from '../Component/Button/ButtonManager'

export default function FormPanel(props) {
    const {
        page,
        ...otherProps
    } = props

    const panel = page.container.panel

    function getButtons(){
        const buttons = panel.buttons.map((button, key) => {
            return (
                <ButtonManager
                    button={button}
                    key={key}
                    {...otherProps}
                />
            )
        })
        return buttons
    }

    return (
        <div className={'card card-' + panel.colour}>
            <div className={'card-header'}>
                {panel.buttons === false ?
                <h3 className={'card-title d-flex mb-12 justify-content-between'}>{panel.label}</h3>
                    :
                    <h3 className={'card-title d-flex mb-12 justify-content-between'}>
                        <span className={'p-6'}>{panel.label}</span>
                        <span className={'p-6'}>{getButtons()}</span>
                    </h3>
                }
                <p>{panel.description}</p>
            </div>
            <div className="card-body">
                <FormRows
                    rows={page.rows}
                    {...otherProps}
                />
            </div>
        </div>
    )
}

FormPanel.propTypes = {
    page: PropTypes.object.isRequired,
}

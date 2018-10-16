'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRenderTabs from './FormRenderTabs'
import FormMessages from './FormMessages'

export default function FormRender(props) {
    const {
        template,
        ...otherProps
    } = props

    if (template.useTabs) {
        return (
            <div className={'container'}>
                <FormMessages
                    {...otherProps}
                />
                <FormRenderTabs
                    tabs={template.tabs}
                    {...otherProps}
                />
            </div>
        )
    }
    return (
        <div className={'container'}>
            <FormMessages
                {...otherProps}
            />
            Render
        </div>
    )
}

FormRender.propTypes = {
    template: PropTypes.object.isRequired,
}

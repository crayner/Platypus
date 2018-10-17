'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormRenderTabs from './FormRenderTabs'
import FormMessages from './FormMessages'
import FormContainer from './FormContainer'

export default function FormRender(props) {
    const {
        template,
        ...otherProps
    } = props

    if (template.form.tabs === false) {
        return (
            <div className={'container'}>
                <FormMessages
                    {...otherProps}
                />
                <FormContainer template={template.container} {...otherProps}/>
            </div>
        )
    }

    return (
        <div className={'container'}>
            <FormMessages
                {...otherProps}
            />
            <FormRenderTabs
                tabs={template.tabs}
                template={template.tabs}
                {...otherProps}
            />
        </div>
    )
}

FormRender.propTypes = {
    template: PropTypes.object.isRequired,
}

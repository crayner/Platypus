'use strict';

import React from "react"
import PropTypes from 'prop-types'

export default function ContentSpan(props) {
    const {
        content,
    } = props

   if (content.options.length === 0)
       return content.content


    return (
        <span className={content.options.class ? content.options.class : ''}>
            {content.options.join === '<br />' ? <br /> : content.options.join}{content.content}
        </span>
    )
}

ContentSpan.propTypes = {
    content: PropTypes.object.isRequired,
}

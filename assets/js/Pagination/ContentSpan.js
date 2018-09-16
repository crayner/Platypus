'use strict';

import React from "react"
import PropTypes from 'prop-types'
import Parser from 'html-react-parser';

export default function ContentSpan(props) {
    const {
        content,
    } = props

    if (content.options.length === 0)
        return content.content

    return (
        <span className={content.options.class ? content.options.class : ''}>
            {content.options.join === '<br />' ? <br /> : content.options.join}{content.options.style === 'html' ? Parser(content.content) : content.content}
        </span>
    )
}

ContentSpan.propTypes = {
    content: PropTypes.object.isRequired,
}

'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { HelpBlock } from 'react-bootstrap'


export default function FormHelp(props) {
    const {
        help,
    } = props

    if (help === false || help === null)
        return ('')

    return (
        <HelpBlock>{help}</HelpBlock>
    )
}

FormHelp.propTypes = {
    help: PropTypes.oneOfType([
        PropTypes.bool,
        PropTypes.string,
    ]),
}

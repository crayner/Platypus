'use strict';

import React from 'react'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faArrowDown } from '@fortawesome/free-solid-svg-icons'
import PropTypes from 'prop-types'

export default function DownButton() {
    const {
        mergeClass
    } = props;

    const className = 'btn btn-light ' + mergeClass
    return (
        <span style={{float: 'right'}} className={className}>
            <FontAwesomeIcon icon={faArrowDown}/>
        </span>
    )
}

DownButton.propTypes = {
    mergeClass: PropTypes.string,
}

DownButton.defaultProps = {
    mergeClass: '',
}


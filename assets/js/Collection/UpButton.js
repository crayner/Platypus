'use strict';

import React from 'react'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faArrowUp} from '@fortawesome/free-solid-svg-icons'
import PropTypes from 'prop-types'

export default function UpButton() {
    const {
        mergeClass
    } = props;

    const className = 'btn btn-light ' + mergeClass
    return (
        <span style={{float: 'right'}} className={className}>
            <FontAwesomeIcon icon={faArrowUp}/>
        </span>
    )
}

UpButton.propTypes = {
    mergeClass: PropTypes.string,
}

UpButton.defaultProps = {
    mergeClass: '',
}


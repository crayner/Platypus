'use strict';

import React from 'react'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faEraser} from '@fortawesome/free-solid-svg-icons'
import PropTypes from 'prop-types'

export default function RemoveButton() {
    const {
        mergeClass
    } = props;

    const className = 'btn btn-warning' + mergeClass
    return (
        <span style={{float: 'right'}} className={className}>
            <FontAwesomeIcon icon={faEraser}/>
        </span>
    )
}

RemoveButton.propTypes = {
    mergeClass: PropTypes.string,
}

RemoveButton.defaultProps = {
    mergeClass: '',
}


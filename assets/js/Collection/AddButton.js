'use strict';

import React from 'react'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faPlusCircle } from '@fortawesome/free-solid-svg-icons'
import PropTypes from 'prop-types'

export default function AddButton() {
    const {
        mergeClass
    } = props;

    const className = 'btn btn-info ' + mergeClass
    return (
        <span style={{float: 'right'}} className={className}>
            <FontAwesomeIcon icon={faPlusCircle}/>
        </span>
    )
}

AddButton.propTypes = {
    mergeClass: PropTypes.string,
}

AddButton.defaultProps = {
    mergeClass: '',
}


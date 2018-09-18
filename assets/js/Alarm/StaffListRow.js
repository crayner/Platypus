'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faTimesCircle, faCheckCircle } from '@fortawesome/free-regular-svg-icons'


export default function StaffListRow(props) {
    const {
        item,
    } = props;

    const index = item.id
    const name = item.fullName

    return (
        <div className={'row'} key={index}>
            <div className={'col-4 offset-4 text-left font-weight-bold text-truncate'}>{name}</div>
            <div className={'col-1 text-center'}>{(item.confirmed)?<span className='text-success font-weight-bold'><FontAwesomeIcon icon={faCheckCircle} /></span>: <span className='text-danger font-weight-bold'><FontAwesomeIcon icon={faTimesCircle} /></span>}</div>
        </div>
    )
}

StaffListRow.propTypes = {
    item: PropTypes.object.isRequired,
}
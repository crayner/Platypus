'use strict';

import React from 'react'
import PropTypes from 'prop-types'

export default function StaffListRow(props) {
    const {
        item,
    } = props;

    var index = item.id.toString()
    return (
        <div className={'row'} key={index}>
            <div className={'col-4 offset-4 text-left'}>{item.fullName}</div>
            <div className={'col-1 text-center'}><span className={item.confirmed ? 'text-success far fa-check-circle' : 'text-danger far fa-times-circle'}></span></div>
        </div>
    )
}

StaffListRow.propTypes = {
    item: PropTypes.object.isRequired,
}
'use strict';

import React from 'react'
import PropTypes from 'prop-types'

export default function StaffListRow(props) {
    const {
        obj,
    } = props;

    var index = obj.id.toString()
    console.log(index)
    return (
        <div className={'row'} key={index}>
            <div className={'col-4 offset-4 text-left'}>{obj.fullName}</div>
            <div className={'col-1 text-center'}><span className={'text-success far fa-check-circle'}></span></div>
        </div>
    )
}

StaffListRow.propTypes = {
    obj: PropTypes.object.isRequired,
}
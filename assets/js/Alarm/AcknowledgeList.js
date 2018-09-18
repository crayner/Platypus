'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import StaffListRow from './StaffListRow'
import firstBy from "thenby";

export default function AcknowledgeList(props) {
    const {
        permission,
        staffList,
    } = props;

    if (permission === false)
        return (
            ''
        )

    const staff = Object.keys(staffList).map(key => {
        const item = staffList[key]
        return <StaffListRow
            item={item}
            key={key}
        />
    })

    return (
        <div className={'staffList container'} key={'wbgfiljhgfpoiwehjgfwerhjgferq[9hg'}>
            {staff}
        </div>
    )

}

AcknowledgeList.propTypes = {
    permission: PropTypes.bool.isRequired,
    staffList: PropTypes.object.isRequired,
}
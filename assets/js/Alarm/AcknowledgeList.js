'use strict';

import React from 'react'
import PropTypes from 'prop-types'
import StaffListRow from './StaffListRow'

export default function AcknowledgeList(props) {
    const {
        permission,
        staffList,
    } = props;

    if (permission === false)
        return (
            ''
        )

    return (
        <div className={'staffList container'} key={'wbgfiljhgfpoiwehjgfwerhjgferq[9hg'}>
            {Object.keys(staffList).map((item, index) => (
                <StaffListRow
                    obj={staffList[item]}
                />
            ))}
        </div>
    )

}

AcknowledgeList.propTypes = {
    permission: PropTypes.bool.isRequired,
    staffList: PropTypes.object.isRequired,
}
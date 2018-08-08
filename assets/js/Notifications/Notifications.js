import React from 'react'
import PropTypes from 'prop-types'

export default function Notifications(props) {
    const {
        message,
    } = props;

    return (
        <div className='text-center'>{ message }</div>
    )
}

Notifications.propTypes = {
    message: PropTypes.object.isRequired,
};
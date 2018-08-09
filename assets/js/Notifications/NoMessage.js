'use strict';

import React from 'react'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCommentDots } from '@fortawesome/free-regular-svg-icons'

export default function NoMessage() {

    return (
        <span style={{float: 'right'}} className={'text-muted'}>
            <FontAwesomeIcon icon={faCommentDots}/>
        </span>
    )
}

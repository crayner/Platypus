'use strict';

import React from 'react'
import { render } from 'react-dom'
import PaginationControl from './Pagination/PaginationControl'

render(
    <PaginationControl
        {...window.PAGINATION_PROPS}
    />,
    document.getElementById('paginationControl')
)

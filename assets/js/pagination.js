'use strict';

import React from 'react'
import { render } from 'react-dom'
import PaginationControl from './Pagination/PaginationControl'

render(
    <PaginationControl
        locale={window.PAGINATION_PROPS.locale}
        name={window.PAGINATION_PROPS.name}
        displaySearch={window.PAGINATION_PROPS.displaySearch}
        displaySort={window.PAGINATION_PROPS.displaySort}
        sortOptions={window.PAGINATION_PROPS.sortOptions}
        translations={window.PAGINATION_PROPS.translations}
        results={window.PAGINATION_PROPS.results}
        offset={window.PAGINATION_PROPS.offset}
        search={window.PAGINATION_PROPS.search}
        limit={window.PAGINATION_PROPS.limit}
        pages={window.PAGINATION_PROPS.pages}
    />,
    document.getElementById('paginationControl')
)

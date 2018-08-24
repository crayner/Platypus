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
        sortByList={window.PAGINATION_PROPS.sortByList}
        translations={window.PAGINATION_PROPS.translations}
        results={window.PAGINATION_PROPS.results}
        offset={window.PAGINATION_PROPS.offset}
        search={window.PAGINATION_PROPS.search}
        sort={window.PAGINATION_PROPS.sort}
        orderBy={window.PAGINATION_PROPS.orderBy}
        caseSensitive={window.PAGINATION_PROPS.caseSensitive}
        limit={window.PAGINATION_PROPS.limit}
        pages={window.PAGINATION_PROPS.pages}
        searchDefinition={window.PAGINATION_PROPS.searchDefinition}
        columnDefinitions={window.PAGINATION_PROPS.columnDefinitions}
        headerDefinition={window.PAGINATION_PROPS.headerDefinition}
        actions={window.PAGINATION_PROPS.actions}
    />,
    document.getElementById('paginationControl')
)

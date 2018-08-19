'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import PaginationList from './PaginationList'

export default function PaginationTitle(props) {
    const {
        rows,
        translations,
        locale,
    } = props;

    return (
        <section>
            <PaginationList
                locale={locale}
                rows={rows}
                translations={translations}
            />
        </section>
    )
}

PaginationTitle.propTypes = {
    locale: PropTypes.string.isRequired,
    rows: PropTypes.array.isRequired,
    translations: PropTypes.object.isRequired,
}


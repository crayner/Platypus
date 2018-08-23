'use strict';

import React from "react"
import PropTypes from 'prop-types'
import {translateMessage} from '../Component/MessageTranslator'

export default function PaginationActionHeader(props) {
    const {
        translations,
        actions,
    } = props;

    return (
        <div className={actions.class + ' text-center col-' + actions.size}>{translateMessage(translations, actions.label)}</div>
    )

}

PaginationActionHeader.propTypes = {
    translations: PropTypes.object.isRequired,
    actions:  PropTypes.object.isRequired,
}

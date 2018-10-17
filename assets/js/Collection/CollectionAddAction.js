'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonAdd from '../Component/Button/ButtonAdd'

export default function CollectionAddAction(props) {
    const {
        template,
        addCollectionElement,
        ...otherProps,
    } = props

    if (typeof template.actions === 'undefined' && typeof template.actions.add === 'undefined')
        return ('')

    let button = template.actions.add

    return (
        <ButtonAdd
            button={button}
            addElement={addCollectionElement}
            {...otherProps}
        />
    )
}

CollectionAddAction.propTypes = {
    template: PropTypes.object.isRequired,
    addCollectionElement: PropTypes.func.isRequired,
}

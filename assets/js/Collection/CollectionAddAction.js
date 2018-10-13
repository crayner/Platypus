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

    let add = template.actions.add

    let button = {
        classMerge: (typeof add.mergeClass === 'string' ? add.mergeClass : ''),
        response_type:(typeof(add.url_type) === 'string' ? add.url_type : 'json'),
        style: {float: 'right'},
    }

    return (
        <ButtonAdd
            button={button}
            buttonClickAction={addCollectionElement}
            url={''}
            {...otherProps}
        />
    )
}

CollectionAddAction.propTypes = {
    template: PropTypes.object.isRequired,
    addCollectionElement: PropTypes.func.isRequired,
}

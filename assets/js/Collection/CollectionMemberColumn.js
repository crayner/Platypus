'use strict';

import React from "react"
import PropTypes from 'prop-types'
import FormTypes from '../Form/FormTypes'

export default function CollectionMemberColumn(props) {
    const {
        translations,
        form,
        column,
        ...otherProps,
    } = props

    let x_key = 0

    function getColumnContent(){
        if (typeof(column.form) === 'object'){
            let x = 0
            const content = column.form.map(name => {
                let element = findFormElement(name)
                return renderColumn(column, element)
            })
            return content
        }
    }

    function findFormElement(name){
        const element = form.children.filter(child => {
            if (child.name === name)
                return child
        })
        if (element.length > 0)
            return element[0]
        console.log('The form element: ' + name + ' was not found.')``
        return []
    }

    function renderColumn(column, element)
    {
        return (
            <FormTypes
                translations={translations}
                form={element}
                key={x_key++}
                {...otherProps}
            />
        )
    }

    return (
        <div className={typeof(column.class) !== 'undefined' ? column.class : ''}>{ getColumnContent() }</div>
    )
}

CollectionMemberColumn.propTypes = {
    translations: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]).isRequired,
    form: PropTypes.object.isRequired,
    column: PropTypes.object.isRequired,
}

'use strict';

import React from "react"
import PropTypes from 'prop-types'
import CollectionMember from './CollectionMember'
import CollectionAddAction from './CollectionAddAction'

export default function CollectionType(props) {
    const {
        form,
        ...otherProps,
    } = props

    const children = form.children

    const collectionRows = Object.keys(children).map(key => {
         let child = children[key]
         return (
                <CollectionMember
                    key={key}
                    form={child}
                    {...otherProps}
                />
            )
        }
    )


    return (
        <div id={form.id} autoComplete={'off'}>
            { collectionRows }
            <CollectionAddAction
                form={form}
                {...otherProps}
            />
        </div>
    )
}

CollectionType.propTypes = {
    form: PropTypes.object.isRequired,
}

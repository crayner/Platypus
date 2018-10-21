'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonManager from '../Component/Button/ButtonManager'
import FormRows from './FormRows'

export default function CollectionType(props) {
    const {
        form,
        template,
        ...otherProps,
    } = props

    const collection = form.children
    let last = null

    Object.keys(collection).map(key => {
        let child = collection[key]
        last = child.name
    })

    const collectionProps = {
        addElement: otherProps.addCollectionElement,
        deleteElement: otherProps.deleteCollectionElement,
        allow_add: form.allow_add,
        allow_delete: form.allow_delete,
        allow_up: form.allow_up,
        allow_down: form.allow_down,
        allow_duplicate: form.allow_duplicate,
        collection_buttons: template.buttons,
        first:  collection[0].name,
        last: last,
        button_merge_class: form.button_merge_class,
        collection_name: form.name,
        default_buttons: {
            add: {type: 'add', style: {float: 'right'}, options: {eid: 'id'}},
            delete: {type: 'delete', style: {}, options: {eid: 'id'}},
            up: {type: 'up', style: {}, options: {eid: 'id'}},
            down: {type: 'down', style: {}, options: {eid: 'id'}},
            duplicate: {type: 'duplicate', style: {}, options: {eid: 'id'}},
        },
    }

    const collectionRows = Object.keys(collection).map(key => {
            let child = collection[key]
            return (
                <FormRows
                    key={key}
                    form={child}
                    template={template.rows}
                    {...collectionProps}
                    {...otherProps}
                />
            )
        }
    )

    function addButton() {
        if (form.allow_add) {
            let button = {...collectionProps.default_buttons.add}
            if (typeof template.buttons.add !== 'undefined')
                button = {...template.buttons.add}
            button = Object.assign({id: form.id + '_add'}, {...button})
            return (
                <ButtonManager
                    button={button}
                    addElement={otherProps.addCollectionElement}
                    {...otherProps}
                />
            )
        }
        return ''
    }

    return (
        <div id={form.id} autoComplete={'off'}>
            { collectionRows }
            { addButton() }
            <hr />
        </div>
    )
}

CollectionType.propTypes = {
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
}

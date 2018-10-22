'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonManager from '../Component/Button/ButtonManager'
import FormRows from './FormRows'

export default function CollectionType(props) {
    const {
        form,
        template,
        getFormElementById,
        ...otherProps,
    } = props

    const collection = getFormElementById(form.id)

    const children = collection.children
    let last = null

    Object.keys(children).map(key => {
        let child = children[key]
        last = child.name
    })

    const collectionProps = {
        addElement: otherProps.addCollectionElement,
        deleteElement: otherProps.deleteCollectionElement,
        allow_add: collection.allow_add,
        allow_delete: collection.allow_delete,
        allow_up: collection.allow_up,
        allow_down: collection.allow_down,
        allow_duplicate: collection.allow_duplicate,
        collection_buttons: template.buttons,
        first:  children[0].name,
        last: last,
        button_merge_class: collection.button_merge_class,
        collection_name: collection.name,
        default_buttons: {
            add: {type: 'add', style: {float: 'right'}, options: {eid: 'id'}},
            delete: {type: 'delete', style: {}, options: {eid: 'id'}},
            up: {type: 'up', style: {}, options: {eid: 'id'}},
            down: {type: 'down', style: {}, options: {eid: 'id'}},
            duplicate: {type: 'duplicate', style: {}, options: {eid: 'id'}},
        },
    }

    const collectionRows = Object.keys(children).map(key => {
            let child = children[key]
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
        if (collection.allow_add) {
            let button = {...collectionProps.default_buttons.add}
            if (typeof template.buttons.add !== 'undefined')
                button = {...template.buttons.add}
            button = Object.assign({id: collection.id + '_add'}, {...button})
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
        <div id={collection.id} autoComplete={'off'}>
            { collectionRows }
            { addButton() }
            <hr />
        </div>
    )
}

CollectionType.propTypes = {
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
    getFormElementById: PropTypes.func.isRequired,
}

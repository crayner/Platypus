'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonSave from '../Component/Button/ButtonSave'
import ButtonDelete from '../Component/Button/ButtonDelete'
import ButtonDuplicate from '../Component/Button/ButtonDuplicate'
import ButtonUp from '../Component/Button/ButtonUp'
import ButtonDown from '../Component/Button/ButtonDown'

export default function CollectionMemberActions(props) {
    const {
        translations,
        form,
        template,
        first,
        last,
        deleteCollectionElement,
        duplicateCollectionElement,
        moveCollectionElementUp,
        moveCollectionElementDown,
        saveCollection,
        ...otherProps,
    } = props

    function getButtonOptions(buttonType){
        if (typeof template.actions[buttonType] === 'undefined')
            return {}
        let button = {...template.actions[buttonType]}

        let options = {
            id: form.data_id,
            name: form.data_toString
        }

        if (typeof button.options !== 'object')
            button.options = []

        Object.keys(button.options).map(key => {
            const value = button.options[key]
            options[key] = typeof form[value] !== 'undefined' ? form[value] : value
        })
        return options
    }

    return (
        <div className={typeof(template.actions.class) !== 'undefined' ? template.actions.class : ''}>

            {template.actions.duplicate.display ? <ButtonDuplicate
                button={{
                    'mergeClass': (typeof(template.actions.duplicate.mergeClass) === 'string' ? template.actions.duplicate.mergeClass : ''),
                    url_type:(typeof(template.actions.duplicate.url_type) === 'string' ? template.actions.duplicate.url_type : 'json'),
                    options: getButtonOptions('duplicate')
                }}
                duplicateElement={duplicateCollectionElement}
                translations={translations}
                {...otherProps}
            /> : ''}

            {template.actions.up.display && form.name !== first ? <ButtonUp
                button={{
                    'mergeClass': (typeof(template.actions.up.mergeClass) === 'string' ? template.actions.up.mergeClass : ''),
                    url_type:(typeof(template.actions.up.url_type) === 'string' ? template.actions.up.url_type : 'json'),
                    options: getButtonOptions('up')
                }}
                moveElementUp={moveCollectionElementUp}
                translations={translations}
                {...otherProps}
            /> : ''}

            {template.actions.down.display && form.name !== last ? <ButtonDown
                button={{
                    'mergeClass': (typeof(template.actions.down.mergeClass) === 'string' ? template.actions.down.mergeClass : ''),
                    url_type:(typeof(template.actions.down.url_type) === 'string' ? template.actions.down.url_type : 'json'),
                    options: getButtonOptions('down')
                }}
                url={''}
                moveElementDown={moveCollectionElementDown}
                translations={translations}
                {...otherProps}
            /> : ''}

            {template.actions.delete.display ? <ButtonDelete
                button={{
                    'mergeClass': (typeof(template.actions.delete.mergeClass) === 'string' ? template.actions.delete.mergeClass : ''),
                    options: getButtonOptions('delete')
                }}
                deleteElement={deleteCollectionElement}
                translations={translations}
                {...otherProps}
            /> : ''}

            {template.actions.save ? <ButtonSave
                button={{
                    'mergeClass': (typeof(template.actions.save.mergeClass) === 'string' ? template.actions.save.mergeClass : ''),
                    options: getButtonOptions('save')
                }}
                saveElement={saveCollection}
                translations={translations}
                {...otherProps}
            /> : ''}

        </div>
    )
}

CollectionMemberActions.propTypes = {
    translations: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]).isRequired,
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
    first: PropTypes.string.isRequired,
    last: PropTypes.string.isRequired,
    saveCollection: PropTypes.func.isRequired,
    deleteCollectionElement: PropTypes.func.isRequired,
    moveCollectionElementUp: PropTypes.func.isRequired,
    moveCollectionElementDown: PropTypes.func.isRequired,
}

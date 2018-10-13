'use strict';

import React from "react"
import PropTypes from 'prop-types'
import ButtonSave from '../Component/Button/ButtonSave'
import ButtonDelete from '../Component/Button/ButtonDelete'

export default function CollectionMemberActions(props) {
    const {
        translations,
        form,
        template,
        deleteCollectionElement,
        ...otherProps,
    } = props

    function buildButtons() {
        const actions = template.actions
        if (typeof(actions.buttons) === 'undefined')
            actions.buttons = []
        let content = actions.buttons.map((button, key) => {
            switch (button.type) {
                case 'saveButton':
                    return <ButtonSave
                        key={key}
                        button={{'classMerge': (typeof(button.mergeClass) === 'string' ? button.mergeClass : '')}}
                        translations={translations}
                        url={''}
                        {...otherProps}
                    />
                case 'deleteButton':
                    return <ButtonDelete
                        key={key}
                        button={{
                            'classMerge': (typeof(button.mergeClass) === 'string' ? button.mergeClass : ''),
                            response_type:(typeof(button.url_type) === 'string' ? button.url_type : 'json'),
                            options: {
                                eid: form.name
                            }
                        }}
                        url={''}
                        buttonClickAction={deleteCollectionElement}
                        translations={translations}
                        {...otherProps}
                    />
                default:
                    return ''
            }
        })

        return content
    }

    return (
        <div className={typeof(template.actions.class) !== 'undefined' ? template.actions.class : ''}>{ buildButtons() }</div>
    )
}

CollectionMemberActions.propTypes = {
    translations: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]).isRequired,
    form: PropTypes.object.isRequired,
    template: PropTypes.object.isRequired,
    deleteCollectionElement: PropTypes.func.isRequired,
}

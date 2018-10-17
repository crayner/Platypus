'use strict';

import React from "react"
import PropTypes from 'prop-types'
import CollectionMemberColumn from './CollectionMemberColumn'
import CollectionMemberActions from './CollectionMemberActions'

export default function CollectionMember(props) {
    const {
        template,
        form,
        ...otherProps,
    } = props

    function getColumns(columns, row){
        return columns.map(function(column, key) {
            return <CollectionMemberColumn
                column={column}
                key={key}
                row={row}
                form={form}
                {...otherProps}
            />
        })
    }

    const rows = template.rows.map((row, key) => {
        return (
            <div className={typeof(row.class) !== 'undefined' ? row.class : ''} key={key}>
                { getColumns(row.columns, key) }
                <CollectionMemberActions
                    key={key}
                    template={template}
                    form={form}
                    {...otherProps}
                />
            </div>
        )
    })

    return (
        <div className={'form-group'} id={form.id}>{ rows }</div>
    )
}

CollectionMember.propTypes = {
    template: PropTypes.object.isRequired,
    form: PropTypes.object.isRequired,
}

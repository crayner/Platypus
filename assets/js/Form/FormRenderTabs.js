'use strict';

import React from "react"
import PropTypes from 'prop-types'
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import '../../css/react-tabs.scss';
import FormPage from './FormPage'

export default function FormRenderTabs(props) {
    const {
        tabs,
        ...otherProps
    } = props

    const tabTags = Object.keys(tabs).map(name => {
        const tab = tabs[name]
        const disabled = tab.display ? false : true
        return (
            <Tab key={name} disabled={disabled}>{tabs[name].label}</Tab>
        )
    })

    const content = Object.keys(tabs).map(name => {
        const page = tabs[name]
            return (
                <TabPanel key={name}>
                    {page.display ?
                    <FormPage
                        page={page}
                        {...otherProps}
                    /> : 'Empty Tab' }
                </TabPanel>
            )
    })

    return (
        <Tabs>
            <TabList>
                {tabTags}
            </TabList>
            {content}
        </Tabs>
    )
}

FormRenderTabs.propTypes = {
    tabs: PropTypes.object.isRequired,
}

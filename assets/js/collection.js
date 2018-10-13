'use strict';

import React from 'react'
import { render } from 'react-dom'
import CollectionControl from './Collection/CollectionControl'

render(
    <CollectionControl
        {...window.COLLECTION_PROPS}
    />,
    document.getElementById(window.COLLECTION_PROPS.collection_element)
)

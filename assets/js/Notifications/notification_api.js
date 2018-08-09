'use strict';

import { fetchJson} from "../Component/fetchJson";

export function getNotifications(content, locale = 'en') {
    return fetchJson('/system/notification/manage/', {method: 'POST', body: JSON.stringify(content)}, locale)
        .then((data) => {
                data = data.content
                return data
            })
}

'use strict';

import { fetchJson} from "../Component/fetchJson";

export function getAlarm(locale = 'en') {
    return fetchJson('/system/alarm/check/', {method: 'GET'}, locale)
        .then((data) => {
            return data
        })
}

export function closeAlarm(locale = 'en') {
    return fetchJson('/system/alarm/close/', {method: 'GET'}, locale)
        .then((data) => {
            return data
        })
}

export function acknowledgeAlarm(person, locale = 'en') {
    var xxx = '/system/alarm/person/acknowledge/'
    xxx = xxx.replace('person', person)
    return fetchJson(xxx, {method: 'GET'}, locale)
        .then((data) => {
            return data
        })
}

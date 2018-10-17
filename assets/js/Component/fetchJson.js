'use strict';

export function fetchJson(url, options, locale) {

    var headers = {}
    if (options && options.headers) {
        headers = options.headers
        delete options.headers
    }
    headers = ({...headers, 'Content-Type': 'application/json; charset=utf-8'})

    if (locale === null)
        locale = 'en'

    return fetch('/' + locale + url, ({
        ...options,
        credentials: 'same-origin',
        headers: headers,
    }))
        .then(checkStatus)
        .then(response => {
            // decode JSON, but avoid problems with empty responses
            return response.text()
                .then(text => text ? JSON.parse(text) : '')
        })
}

function checkStatus(response) {
    if (response.status >= 200 && response.status < 400) {
        return response;
    }

    const error = new Error(response.statusText);
    error.response = response;

    throw error
}


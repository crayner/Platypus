function fetchJson(url, options) {

    var headers = {}
    if (options && options.headers) {
        headers = options.headers
        delete options.headers
    }
    headers = ({...headers, 'Content-Type': 'application/json'})

    return fetch(url, ({
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

export function getNotifications(content) {
    return fetchJson('/en/system/notification/manage/', {method: 'POST', body: JSON.stringify(content)})
        .then((data) => {
                data = data.content
                return data
            })
}

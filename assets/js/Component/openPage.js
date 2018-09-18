'use strict';

export function openPage(url, options, locale) {

    var target = '_self'
    if (options && typeof(options.target) === 'string') {
        target = options.target
    }

    var specs = ''
    if (options && typeof(options.specs) === 'string') {
        specs = options.specs
    }

    if (locale === null || typeof(locale) === 'undefined')
        locale = 'en'

    window.open(window.location.protocol + '//' + window.location.hostname + '/' + locale + url, target, specs)
}



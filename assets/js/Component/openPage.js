'use strict';

export function openPage(url, options, locale) {

    var target = '_self'
    if (options && options.target) {
        target = options.target
    }

    var specs = ''
    if (options && options.specs) {
        specs = options.specs
    }

    if (locale === null || typeof(locale) === 'undefined')
        locale = 'en'

    window.open(window.location.protocol + '//' + window.location.hostname + '/' + locale + url, target, specs)
}



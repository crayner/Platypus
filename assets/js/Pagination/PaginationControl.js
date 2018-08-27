'use strict';

import React, { Component } from "react"
import PropTypes from 'prop-types'
import firstBy from 'thenby'
import PaginationSearch from "./PaginationSearch";
import PaginationSort from './PaginationSort'
import PaginationLimit from './PaginationLimit'
import PaginationTitle from './PaginationTitle'
import {translateMessage} from '../Component/MessageTranslator'
import { fetchJson } from "../Component/fetchJson";
import {openPage} from '../Component/openPage'
import PaginationMessages from './PaginationMessages'
import PaginationFilter from './PaginationFilter'
import PaginationFilterNotices from './PaginationFilterNotices'

export default class PaginationControl extends Component {
    constructor(props) {
        super(props)

        this.state = {
            offset: props.offset,
            search: props.search,
            limit: props.limit,
            results: props.results,
            rows: [],
            sort: props.sort,
            messages: new Object(),
            filterValue: [],
        }

        this.locale = props.locale
        this.name = props.name
        this.displaySearch = props.displaySearch
        this.displaySort = props.displaySort
        this.displayFilter = props.displayFilter
        this.filter = props.filter
        this.sortOptions = props.sortOptions
        this.translations = props.translations
        this.allResults = props.results
        this.total = props.results.length
        this.offset = props.offset
        this.limit = props.limit
        this.search = props.search
        this.searchChange = true
        this.actions = props.actions
        this.columnDefinitions = props.columnDefinitions
        this.headerDefinition = props.headerDefinition
        this.searchDefinition = props.searchDefinition
        this.orderBy = props.orderBy === 'ASC' ? 1 : -1
        this.caseSensitive = props.caseSensitive === '1' ? true : false
        this.messages = new Object()
        this.filterValue = props.filterValue

        this.filterLabels = this.createFilterLabels(this.filter)

        this.sort = props.sort

        this.pages = 0
        this.sortByList = props.sortByList
        this.rows = []

        this.changeLimit = this.changeLimit.bind(this)
        this.nextPage = this.nextPage.bind(this)
        this.previousPage = this.previousPage.bind(this)
        this.changeTheSort = this.changeTheSort.bind(this)
        this.changeTheSearch = this.changeTheSearch.bind(this)
        this.toggleOrderBy = this.toggleOrderBy.bind(this)
        this.toggleCaseSensitive = this.toggleCaseSensitive.bind(this)
        this.buttonClickAction = this.buttonClickAction.bind(this)
        this.cancelMessage = this.cancelMessage.bind(this)
        this.clearFilter = this.clearFilter.bind(this)
        this.changeFilterValue = this.changeFilterValue.bind(this)
    }

    componentWillMount(){
        this.buildSearchString()
        this.handlePagination()
    }

    handlePagination(){
        let results = this.getSearchResults()
        results = this.getSortResults(results)

        this.pages = this.calculatePages(results)

        this.offset = this.calculateOffset(results)

        this.rows = results.slice(this.offset, (this.limit + this.offset))

        if (typeof(this.messages) !== 'object')
            this.messages = new Object()

        this.setState({
            offset: this.offset,
            search: this.search,
            limit: this.limit,
            results: results,
            rows: this.rows,
            sort: this.sort,
            messages: this.messages,
            filterValue: this.filterValue,
        })
        this.setPaginationCache()
    }

    getSearchResults(){
        if (this.searchChange) {
            this.searchChange = false

            let results
            if (!(this.search === '' || this.search === null)) {
                if (this.caseSensitive)
                    results = this.allResults.filter(row =>
                        row.SearchString.includes(this.search)
                    )
                else
                    results = this.allResults.filter(row =>
                        row.SearchString.toLowerCase().includes(this.search.toLowerCase())
                    )
            } else
                results = this.allResults

            return this.filterResults(results)
        }
        return this.state.results
    }

    getSortResults(results){
        const sortCriteria = this.sortByList[this.sort]
        const sortDepth = sortCriteria.length

        if (sortDepth < 1)
            return results
        else if (sortDepth === 1)
            return results.sort(
                firstBy(sortCriteria[0], this.orderBy)
            )
        else if (sortDepth === 2)
            return results.sort(
                firstBy(sortCriteria[0], this.orderBy)
                    .thenBy(sortCriteria[1], this.orderBy)
            )
        else if (sortDepth === 3)
            return results.sort(
                firstBy(sortCriteria[0], this.orderBy)
                    .thenBy(sortCriteria[1], this.orderBy)
                    .thenBy(sortCriteria[2], this.orderBy)
            )
        else if (sortDepth === 4)
            return results.sort(
                firstBy(sortCriteria[0], this.orderBy)
                    .thenBy(sortCriteria[1], this.orderBy)
                    .thenBy(sortCriteria[2], this.orderBy)
                    .thenBy(sortCriteria[3], this.orderBy)
            )
        return results.sort(
            firstBy(sortCriteria[0], this.orderBy)
                .thenBy(sortCriteria[1], this.orderBy)
                .thenBy(sortCriteria[2], this.orderBy)
                .thenBy(sortCriteria[3], this.orderBy)
                .thenBy(sortCriteria[4], this.orderBy)
        )
    }

    calculatePages(results){
        this.total = results.length
        var pages = 0
        if (this.total > 0)
            pages = parseInt(this.total / this.limit + 1)

        if (pages !== this.pages)
            this.pagesChange = true
        return pages
    }

    calculateOffset(results) {
        if (this.offset < 0) {
            this.offsetChange = true
            return 0
        }
        if (this.offset > this.total)
        {
            this.offsetChange = true
            return (this.pages - 1) * this.limit
        }
        return this.offset
    }

    calculateFirst(){
        if (this.offset === 0)
            return 1
        return this.offset;
    }

    calculateLast() {
        if (this.offset === 0)
            return this.limit
        if (this.offset + this.limit > this.total)
            return this.total
        return this.offset + this.limit
    }

    changeLimit(event){
        this.limitChange = true
        this.limit = parseInt(event.target.value)
        this.handlePagination()
    }

    setPaginationCache()
    {
        if (this.filterValue[0] === '[object Object]')
            this.filterValue = []
        const filter = new Buffer(JSON.stringify(this.filterValue)).toString('base64')

        var path = '/pagination/cache/' + this.name + '/' + this.limit + '/' + this.offset + '/' + (this.search === '' ? '*' : this.search) + '/' + (this.sort === '' ? '*' : this.sort) + '/' + (this.orderBy === 1 ? 'ASC' : 'DESC') + '/' + (this.caseSensitive ? '1' : '0') + '/' + filter + '/'
        fetchJson(path, {}, this.locale)
    }

    nextPage(event){
        this.offset = this.offset + this.limit
        this.offsetChange = true
        this.handlePagination()
    }

    createFilterLabels(filter){
        let labels = new Object()
        let i, k
        for(i=0; i < filter.length; i++) {
            for (k = 0; k < filter[i]['fields'].length; k++) {
                const field = filter[i]['fields'][k]
                labels[field['name']] = field['label']
            }
        }
        return labels
    }

    previousPage(event){
        this.offset = this.offset - this.limit
        this.offsetChange = true
        this.handlePagination()
    }

    changeTheSort(event){
        this.sort = event.target.value
        this.handlePagination()
    }

    changeTheSearch(event){
        this.search = event.target.value
        this.searchChange = true
        this.handlePagination()
    }

    toggleOrderBy(){
        this.orderBy = this.orderBy * -1
        this.handlePagination()
    }

    toggleCaseSensitive(){
        this.caseSensitive = this.caseSensitive ? false : true
        this.searchChange = true
        this.handlePagination()
    }

    buttonClickAction(url,type) {
        if (type === 'json') {
            fetchJson(url, {}, this.locale)
                .then((data) => {
                    this.allResults = data['rows']
                    this.searchChange = true
                    this.messages = data['messages']
                    this.buildSearchString()
                    this.handlePagination()
                });
        } else if (type === 'redirect')
            openPage(url)
    }

    buildSearchString(){
        for (let key in this.allResults) {
            let row =  this.allResults[key]
            row.SearchString = ''
            for(let q in this.searchDefinition) {
                if (typeof(row[this.searchDefinition[q]]) === 'undefined')
                {
                    console.log(row)
                    console.log(this.searchDefinition[q])
                    console.error(this.searchDefinition[q] + ' was not found in the data.  Your pagination searchDefinition is not configured correctly.')

                }
                if (typeof(this.searchDefinition[q]) === 'array')
                    for(let w in this.searchDefinition[q]) {
                        const y = this.searchDefinition[q][w]
                        row.SearchString = row.SearchString + y + '|'
                    }
                else
                    row.SearchString = row.SearchString + row[this.searchDefinition[q]] + '|'
            }
            this.allResults[key] = row
        }
    }

    cancelMessage(id) {
        this.messages.splice(id, 1)
        this.handlePagination()
    }

    clearFilter(){
        this.filterValue = []
        this.searchChange = true
        this.handlePagination()
    }

    changeFilterValue(event) {
        const val = event.target.value
        const value = val.split('::')
        const group = value[0]
        const definition = this.getFilterDefinition(group)

        let result
        if (definition['group_style'] === 'one_only')
            result = this.filterValue.filter(filter => filter.includes(group + '::') === false )
        else
            result = this.filterValue.filter(filter => filter.includes(val) === false )

        result.push(val)
        this.filterValue = result
        this.searchChange = true
        this.handlePagination()
    }

    getFilterDefinition(group) {
        let i
        for(i=0; i < this.filter.length; i++)
            if (this.filter[i].name === group)
                return this.filter[i]
        console.error('The filter definition was not found.')
    }

    filterResults(results){
        let i
        let where = {}
        for(i=0; i < this.filterValue.length; i++){
            const val = this.filterValue[i]
            const value = val.split('::')
            const group = value[0]
            let definition = this.getFilterDefinition(group)
            let o
            for(o=0; o < definition['fields'].length; o++){
                if (definition['fields'][o]['name'] === val)
                {
                    if (typeof(where[definition['fields'][o]['field']]) === 'undefined')
                        where[definition['fields'][o]['field']] = []
                    where[definition['fields'][o]['field']].push(definition['fields'][o]['value'])
                }
            }
        }
        for(let name in where) {
            const values = where[name][0]
            const rows = results.filter(function(row) {
                let i
                for(i=0; i < values.length; i++) {
                    if (row[name] === values[i])
                        return row
                }
            })

            results = rows
        }

        return results
    }

    render() {
        return (
            <section>
                <div className="container-fluid card card-dark" style={{padding: '0 15px 5px'}}>
                    <div className="row">
                        {this.displayFilter ?
                            <div className="col-3 card text-right">
                                <PaginationFilter
                                    name={this.name}
                                    translations={this.translations}
                                    filter={this.filter}
                                    clearFilter={this.clearFilter}
                                    changeFilterValue={this.changeFilterValue}
                                />
                            </div>
                            : ''}
                        <div className="col-3 card text-right">
                            {this.displaySearch ?
                                <PaginationSearch
                                    translations={this.translations}
                                    name={this.name}
                                    search={this.state.search}
                                    changeTheSearch={this.changeTheSearch}
                                    caseSensitive={this.caseSensitive}
                                    toggleCaseSensitive={this.toggleCaseSensitive}
                                /> : ''}
                        </div>
                        <div className="col-3 card text-right">
                            {this.displaySort ?
                                <PaginationSort
                                    name={this.name}
                                    translations={this.translations}
                                    changeTheSort={this.changeTheSort}
                                    sortOptions={this.sortOptions}
                                    orderBy={this.orderBy}
                                    toggleOrderBy={this.toggleOrderBy}
                                    sort={this.state.sort}
                                /> : ''}
                        </div>
                        <div className={this.displayFilter ? 'col-3 card text-right' : 'col-3 offset-3 card text-right'}>
                            {this.displaySort ?
                                <PaginationLimit
                                    name={this.name}
                                    translations={this.translations}
                                    limit={this.state.limit}
                                    changeLimit={this.changeLimit}
                                    nextPage={this.nextPage}
                                    previousPage={this.previousPage}
                                /> : ''}
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-8 small">
                            <PaginationFilterNotices
                                translations={this.translations}
                                filterValue={this.state.filterValue}
                                filterLabels={this.filterLabels}
                            />
                        </div>
                        <div className="col-4 text-right small">
                            {this.pages === 0 ? translateMessage(this.translations, 'pagination.figures.empty') : '' }
                            {this.pages === 1 ? (this.total === 1 ? translateMessage(this.translations, 'pagination.figures.one_page.one_record') : translateMessage(this.translations, 'pagination.figures.one_page.two_plus', {'%total%': this.total})) : '' }
                            {this.pages > 1 ? translateMessage(this.translations, 'pagination.figures.two_plus', {'%first%': this.calculateFirst(),'%last%': this.calculateLast(), '%total%': this.total, '%pages%': this.pages}) : '' }
                        </div>
                    </div>
                    <PaginationMessages
                        messages={this.state.messages}
                        translations={this.translations}
                        cancelMessage={this.cancelMessage}
                    />
                </div>
                <PaginationTitle
                    rows={this.state.rows}
                    translations={this.translations}
                    columnDefinitions={this.columnDefinitions}
                    headerDefinition={this.headerDefinition}
                    sort={this.sort}
                    orderBy={this.orderBy}
                    actions={this.actions}
                    buttonClickAction={this.buttonClickAction}
                />
            </section>
        )
    }
}

PaginationControl.propTypes = {
    locale: PropTypes.string.isRequired,
    name: PropTypes.string.isRequired,
    displaySearch: PropTypes.bool.isRequired,
    displaySort: PropTypes.bool.isRequired,
    displayFilter: PropTypes.bool.isRequired,
    filterValue: PropTypes.array.isRequired,
    filter: PropTypes.array.isRequired,
    translations: PropTypes.object.isRequired,
    sortOptions: PropTypes.object.isRequired,
    sortByList: PropTypes.object.isRequired,
    results: PropTypes.array.isRequired,
    search: PropTypes.string.isRequired,
    sort: PropTypes.string.isRequired,
    offset: PropTypes.number.isRequired,
    limit: PropTypes.number.isRequired,
    columnDefinitions: PropTypes.object.isRequired,
    headerDefinition: PropTypes.object.isRequired,
    orderBy: PropTypes.string.isRequired,
    caseSensitive: PropTypes.string.isRequired,
    actions: PropTypes.object.isRequired,
}

PaginationControl.defaultTypes = {
    locale: 'en',
    sortOptions: new Object(),
    sortByList: new Object(),
    filter: new Object(),
}


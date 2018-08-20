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

export default class PaginationControl extends Component {
    constructor(props) {
        super(props)

        this.state = {
            offset: props.offset,
            search: props.search,
            limit: props.limit,
            results: props.results,
            rows: [],
        }

        this.locale = props.locale
        this.name = props.name
        this.displaySearch = props.displaySearch
        this.displaySort = props.displaySort
        this.sortOptions = props.sortOptions
        this.translations = props.translations
        this.allResults = props.results
        this.total = props.results.length
        this.offset = props.offset
        this.limit = props.limit
        this.search = ''
        this.columnDefinitions = props.columnDefinitions
        this.headerDefinition = props.headerDefinition

        for(key in this.sortOptions){
            if(this.sortOptions.hasOwnProperty(key)){
                this.sort = key;
                break;
            }
        }
        this.pages = 0
        this.limitChange = true
        this.offsetChange = true
        this.sortByList = props.sortByList
        this.rows = []

        this.changeLimit = this.changeLimit.bind(this)
        this.nextPage = this.nextPage.bind(this)
        this.previousPage = this.previousPage.bind(this)
    }

    componentWillMount(){
        this.handlePagination()
    }

    handlePagination(){
        var results = this.getSearchResults()
        results = this.getSortResults(results)

        this.pages = this.calculatePages(results)

        this.offset = this.calculateOffset(results)

        this.rows = results.slice(this.offset, (this.limit + this.offset))

        this.setState({
            offset: this.offset,
            search: this.search,
            limit: this.limit,
            results: results,
            rows: this.rows,
        })

        this.setPaginationCache()
    }

    getSearchResults(){
        if (this.searchChange)
            return this.allResults

        return this.state.results
    }

    getSortResults(results){
        return results.sort(
            firstBy('surname')
                .thenBy('firstName')
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
        var path = '/pagination/cache/' + this.name + '/' + this.limit + '/' + this.offset + '/'
        fetchJson(path, {}, this.locale)
    }

    nextPage(event){
        this.offset = this.offset + this.limit
        this.offsetChange = true
        this.handlePagination()
    }

    previousPage(event){
        this.offset = this.offset - this.limit
        this.offsetChange = true
        this.handlePagination()
    }

    render() {
        return (
            <section>
                <div className="container-fluid card card-dark" style={{padding: '0 15px 5px'}}>
                    <div className="row">
                        <div className="col-3 card text-right">
                            {this.displaySearch ?
                                <PaginationSearch
                                    translations={this.translations}
                                    name={this.name}
                                    search={this.state.search}
                                /> : ''}
                        </div>
                        <div className="col-3 card text-right">
                            {this.displaySort ?
                                <PaginationSort
                                    name={this.name}
                                    translations={this.translations}
                                    sortOptions={this.sortOptions}
                                /> : ''}
                        </div>
                        <div className="col-3 offset-3 card text-right">
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
                        <div className="col-4 offset-8 text-right small">
                            {this.pages === 0 ? translateMessage(this.translations, 'pagination.figures.empty') : '' }
                            {this.pages === 1 ? translateMessage(this.translations, 'pagination.figures.one') : '' }
                            {this.pages > 1 ? translateMessage(this.translations, 'pagination.figures.two_plus', {'%first%': this.calculateFirst(),'%last%': this.calculateLast(), '%total%': this.total, '%pages%': this.pages}) : '' }
                        </div>
                    </div>
                </div>
                <PaginationTitle
                    rows={this.state.rows}
                    translations={this.translations}
                    columnDefinitions={this.columnDefinitions}
                    headerDefinition={this.headerDefinition}
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
    translations: PropTypes.object.isRequired,
    sortOptions: PropTypes.object.isRequired,
    sortByList: PropTypes.object.isRequired,
    results: PropTypes.array.isRequired,
    search: PropTypes.string.isRequired,
    offset: PropTypes.number.isRequired,
    limit: PropTypes.number.isRequired,
    columnDefinitions: PropTypes.object.isRequired,
    headerDefinition: PropTypes.object.isRequired,
}

PaginationControl.defaultTypes = {
    locale: 'en',
    sortOptions: new Object(),
    sortByList: new Object(),
}


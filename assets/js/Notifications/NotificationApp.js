'use strict';

import React, { Component } from "react"
import { getNotifications } from "./notification_api";
import PropTypes from 'prop-types'
import Notifications from "./Notifications";
import { fetchJson } from "../Component/fetchJson";

export default class NotificationApp extends Component {
    constructor(props) {
        super(props)

        this.state  = {
            interval: props.interval,
            content: [],
            contentKey: 0,
            fullPage: props.fullPage,
            alwaysFullPage: props.alwaysFullPage,
            message: this.manageMessage({text: '', id: 1, link: ''}),
        }

        this.translations = props.translations
        this.handleRefreshMessageTimeout = 0
        this.handleStepMessageTimeout = 0
        this.messageCount = 0
        this.locale = props.locale
        this.manageSideBarClick = this.manageSideBarClick.bind(this)
        if (props.fullPage)
            this.closeSidebar()
    }

    componentDidMount() {
        getNotifications(this.state.content)
            .then((data) => {
                this.handleNewContent(data)
            });
    }

    componentWillUnmount(){
        clearTimeout(this.handleStepMessageTimeout)
    }

    handleNewContent(data){
        this.messageCount = data.length
        if (this.messageCount === 0){
            data = {'0': {id: 1, text: "", link: "", alert: 'light'}}
        }

        this.handleRefreshMessageTimeout = 0;
        clearTimeout(this.handleStepMessageTimeout);
        var interval = this.state.interval
        if (interval < (data.length * 5000)) {
            interval = data.length * 5000
        }
        this.setState({
            content: data,
            contentKey: 1,
            message: this.manageMessage(data[0]),
            interval: interval,
        })
        this.stepToNextMessage()
    }

    stepToNextMessage(){
        this.refreshMessages()
        clearTimeout(this.handleStepMessageTimeout)
        this.handleStepMessageTimeout =  setTimeout(() => {
            var kk = this.state.contentKey
            if (kk in this.state.content) {
                var message = this.manageMessage(this.state.content[kk])
                kk++
            } else {
                var message = this.manageMessage(this.state.content[0])
                kk = 1
            }
            this.setState({
                message: message,
                contentKey: kk
            })
            this.stepToNextMessage()
        }, 5000)
    }

    refreshMessages(){
        if(this.handleRefreshMessageTimeout >= this.state.interval) {
            this.handleRefreshMessageTimeout = 0
            getNotifications(this.state.content, this.locale)
                .then((data) => {
                    this.handleNewContent(data)
                });
        } else {
            this.handleRefreshMessageTimeout = parseInt(this.handleRefreshMessageTimeout) + 5000
        }
    }

    manageMessage(message){
        if ('alert' in message) {
            var alert = 'alert-' + message.alert
        } else {
            var alert = 'alert-light'
        }
        if (message.link.length !== 0) {
            return (<span className={alert} key={message.id}><a href={message.link}>{message.text || ""}</a></span>)
        } else {
            return (<span className={alert} key={message.id}>{message.text || ""}</span>)
        }
    }

    openSidebar(){
        var container = $('#contentContainer')
        var sideBar = $('#sectionMenuContainer')

        container.removeAttr('style');
        sideBar.removeAttr('style');
        return false
    }

    closeSidebar(){
        var container = $('#contentContainer')
        var sideBar = $('#sectionMenuContainer')

        container.css('flex', 'auto');
        container.css('max-width', 'none');
        sideBar.css('display', 'none');
        return true
    }

    manageSideBarClick(){
        if (this.state.alwaysFullPage)
            return
        if (this.state.fullPage) {
            fetchJson('/menu/section/show/display/', {}, this.locale)
            this.setState({fullPage: this.openSidebar()})
        } else {
            fetchJson('/menu/section/hide/display/', {}, this.locale)
            this.setState({fullPage: this.closeSidebar()})
        }
    }

    render() {
        return (
            <Notifications
                message={this.state.message}
                fullPage={this.state.fullPage}
                alwaysFullPage={this.state.alwaysFullPage}
                manageSidebarClick={this.manageSideBarClick}
                messageCount={this.messageCount}
                translations={this.translations}
            />
        )
    }
}

NotificationApp.propTypes = {
    interval: PropTypes.number.isRequired,
    fullPage: PropTypes.bool.isRequired,
    alwaysFullPage: PropTypes.bool.isRequired,
    translations: PropTypes.object.isRequired,
}

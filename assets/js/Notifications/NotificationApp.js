import React, { Component } from "react"
import { getNotifications } from "./notification_api";
import PropTypes from 'prop-types'
import Notifications from "./Notifications";


export default class NotificationApp extends Component {
    constructor(props) {
        super(props);

        this.state  = {
            interval: props.interval,
            content: [],
            isLoaded: false,
            contentKey: 0,
            message: this.manageMessage({text: 'Loading...', id: 1, link: ''}),
        }

        this.handleRefreshMessageTimeout = 0;
        this.handleStepMessageTimeout = 0;
    }

    componentDidMount() {
        getNotifications(this.state.content)
            .then((data) => {
                this.handleNewContent(data)
            });
    }

    handleNewContent(data){
        if (data.length === 0){
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
            isLoaded: true,
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
            getNotifications(this.state.content)
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

    render() {
        return (
            <Notifications
                message={this.state.message}
            />
        )
    }
}

NotificationApp.propTypes = {
    interval: PropTypes.number.isRequired,
}

require('dotenv').config();
const { WebClient } = require('@slack/web-api');

let SlackWrapper = class {
    config = {
        'token': process.env.SLACK_TOKEN,
        'debug': process.env.SLACK_DEBUG
    }

    constructor(options) {
        var ahth_token = options.token ?? this.config.token;
        this.web = new WebClient(ahth_token);
    }

    sendMessage(msg, channel) {
        return this.web.chat.postMessage({
            text: msg,
            channel: channel,
        });
    }

    getUsers() {
        return this.web.users.list();
    }

    debug(msg) {
        if(this.config.debug){
            console.info('DEBUG MSG');
            console.info(msg);
        }
    }
}

module.exports = SlackWrapper;
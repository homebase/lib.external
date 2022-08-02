const SlackWrapper = require("./core/wrapper/slack");
const yargs = require('yargs');

var usersMap = [
    {name: 'LinasElksnis', alias: 'linas', slack_id: 'D03ETMZS76K'},
    {name: 'EvgeniiBeliakov', alias: 'jonny', slack_id: 'D03FY2Q30AC'},
    {name: 'KonstantinDemidov', alias: 'kostya', slack_id: 'D03F89P8ULT'},
    {name: 'MaxAvgoustinskiy', alias: 'max', slack_id: 'D03F1LS3C94'},
    {name: 'OlehLypovyi', alias: 'oleg', slack_id: 'D03FLUPFA8Z'},
    {name: 'SergParf', alias: 'parf', slack_id: 'D03RDQLJL6N'},
    {name: 'Stas', alias: 'stas', slack_id: 'D03F89PD8AX'},
    {name: 'YuriyPertsev', alias: 'yuriy', slack_id: 'D03F5CNKT9B'},
    {name: 'radaris-dev', alias: 'dev', slack_id: 'C03F88W90EP'},
];

var slackInstance = new SlackWrapper({token: process.env.SLACK_TOKEN})

const argv = yargs
  .option('user', {
    alias: 'U',
    description: 'User name',
    type: 'string',
    required: true
  })
  .option('msg', {
    alias: 'M',
    description: 'Msg',
    type: 'string',
    required: true
  })
  .help().alias('help', 'h').argv;
  
  if(argv.user && argv.msg) {
    var usersSend = usersMap.filter(item => {
        return item.name.toLocaleLowerCase() === argv.user.toLocaleLowerCase() || item.alias.toLocaleLowerCase() === argv.user.toLocaleLowerCase();
    });

    usersSend.forEach(item => {
        slackInstance.sendMessage(argv.msg, item.slack_id);
    });
  } 
  
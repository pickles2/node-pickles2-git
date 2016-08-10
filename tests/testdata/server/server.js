/**
 * server.js
 */
var conf = require('config');
var urlParse = require('url-parse');
conf.originParsed = new urlParse(conf.origin);
conf.originParsed.protocol = conf.originParsed.protocol.replace(':','');
if(!conf.originParsed.port){
	conf.originParsed.port = (conf.originParsed.protocol=='https' ? 443 : 80);
}
console.log(conf);

var fs = require('fs');
var path = require('path');
var express = require('express'),
	app = express();
var server = require('http').Server(app);
console.log('port number is '+conf.originParsed.port);


app.use( require('body-parser')() );
app.use( '/common/', express.static( path.resolve(__dirname, '../../../dist/') ) );
app.use( '/apis/px2git', require('./apis/px2git.js')() );

app.use( express.static( __dirname+'/../client/' ) );

// {conf.port}番ポートでLISTEN状態にする
server.listen( conf.originParsed.port, function(){
	console.log('server-standby');
} );

var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var rpc = require('node-json-rpc');

var rpc_options = {
	port: 5080,
	host: '127.0.0.1',
	path: '/',
	strict: true
};

var rpc_server = new rpc.Server(rpc_options);

rpc_server.addMethod('emitMessage', function(para, callback) {
	var error, result, msg;
	result = {"status": "success"};
	msg = para.username + ': ' + para.message;
	console.log(para);
	io.emit('chat message', msg);
	console.log(msg);
	callback(error, result);
});

app.get('/', function(req, res){
  res.sendFile(__dirname + '/index.html');
});

io.on('connection', function(socket){
  console.log('a user connected');
  
  socket.on('chat message', function(msg) {
	console.log('message: ' + msg);
	io.emit('chat message', msg);
  });
  
});

rpc_server.start(function(error) {

	if(error) throw error;
	else console.log('RPC server starting...');

});

http.listen(3000, function() {
	console.log('listening on *:3000');
});

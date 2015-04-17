//***** Mikael Feuk *****//

// Configurations
var port = 1337;
var serverProtocols = ['echo-protocol', 'broadcast-protocol'];

//***** HTTP server *****
 
// Require the modules we need
var http = require('http');
 
// Create a http server with a callback handling all requests
var httpServer = http.createServer(function(request, response) {
  console.log((new Date()) + ' Received request for ' + request.url);
  response.writeHead(200, {'Content-type': 'text/plain'});
  response.end('Hello world\n');
});
 
// Setup the http-server to listen to a port
httpServer.listen(port, function() {
  console.log((new Date()) + ' HTTP server is listening on port ' + port);
});


//***** WebSocket server *****
// Based on example server in Readme-file of https://github.com/theturtle32/WebSocket-Node
// and then adapted.

// Get a WebSocket server constructor function
var WebSocketServer = require('websocket').server; 

// Create a Web Socket server
var wsBroadcastServer = new WebSocketServer({
    httpServer: httpServer,
    autoAcceptConnections: false
});

// Set up handling of WebSocket connection requests.
//   The request event is triggered when the configured HTTP-server receives
//   an 'upgrade' request.
wsBroadcastServer.on('request', function(request) {

    var reqProtocols = request.requestedProtocols,
    matchedProtocol = null;

    // Check if the server is allowed to accept a connection to the client
    if (!originIsAllowed(request.origin)) {

        // Make sure we only accept requests from an allowed origin
        request.reject();
        console.log((new Date()) + ' Connection from origin ' + request.origin + ' rejected.');
        return;
    }

    // Check if any of the protocol(s) in the request are supported
    for (var i = 0 ; i < serverProtocols.length ; i++) {
        if (serverProtocols.indexOf(reqProtocols[i]) > -1) {

            // Choose the first protocol that match
            matchedProtocol = reqProtocols[i];
            break;
        }
    }

    if (matchedProtocol === null) {
        // No protocol was found that matched
        request.reject('403', 'Requested protocol(s) not supported');
        return;
    }
        
    switch(matchedProtocol) {
        case 'echo-protocol':
            echoSetUp(request);
            break;
        case 'broadcast-protocol':
            broadcastSetUp(request);
            break;
    }
});

// Connection setup: Echo-protocol
var echoSetUp = function(request) {

    // Create a connection
    var connection = request.accept('echo-protocol', request.origin);
    console.log((new Date()) + ' Connection accepted.');

    //Set up a 'message' event handler
    connection.on('message', function echoMsgHandler(message) {

        //Check type of message and respond accordingly
        if (message.type === 'utf8') {
            console.log('Received Message: ' + message.utf8Data);
            connection.sendUTF(message.utf8Data);
        }
        else if (message.type === 'binary') {
            console.log('Received Binary Message of ' + message.binaryData.length + ' bytes');
            connection.sendBytes(message.binaryData);
        }
    });

    //Set up a 'close' event handler
    connection.on('close', function closeHandler(reasonCode, description) {
        console.log((new Date()) + ' Peer ' + connection.remoteAddress + ' disconnected.');
    });
};

// Connection setup: Broadcast-protocol
var connections = [];

var broadcastSetUp = function(request) {

    // Create a connection
    var connection = request.accept('broadcast-protocol', request.origin);
    console.log((new Date()) + ' Connection accepted.');

    // Add it to the array of connection in the broadcast group
    connections.push(connection);
    console.log('Conn-length: ' + connections.length); // For testing

    //Set up a 'message' event handler
    connection.on('message', function broadcastMsgHandler(message) {

        //Check type of message and respond accordingly
        if (message.type === 'utf8') {
            console.log('Received Message: ' + message.utf8Data);

            // Go through all connections and broadcast the message
            connections.forEach(function broadcastUTF(conn) {
                conn.sendUTF(message.utf8Data);
            });
        }
        else if (message.type === 'binary') {
            console.log('Received Binary Message of ' + message.binaryData.length + ' bytes');

            // Go through all connections and broadcast the message
            connections.forEach(function broadcastBin(conn) {
                conn.sendBytes(message.binaryData);
            });
        }
    });

    //Set up a 'close' event handler
    connection.on('close', function closeHandler(reasonCode, description) {

        console.log((new Date()) + ' Peer ' + connection.remoteAddress + ' disconnected.');

        // Remove the connection from the connections array
        connections = connections.filter(function(conn) { return conn != connection ;});
        console.log('Conn-length: ' + connections.length); // For testing
    });
};


function originIsAllowed(origin) {
  // put logic here to detect whether the specified origin is allowed.
  return true;
}
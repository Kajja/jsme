//***** Chat server using the WebSocket protocol *****//
//***** by Mikael Feuk

// Configurations
var port = 8041;
var serverProtocols = ['chat-protocol'];
var okOrigins = ['http://localhost', 'http://dbwebb.se', 'http://www.student.bth.se/'];

// Users that are connected
var users = [];

// Connections
var connections = [];

//***** HTTP-server *****//
var http = require('http');
var httpServer = http.createServer(function requestListener(request, response){
    console.log('Received request for ' + request.url);
    // Response
    response.writeHead(200, {'Content-type': 'text/plain'});
    response.end('Chat server\n');
});

httpServer.listen(port, function() {
  console.log('HTTP server is listening on port ' + port);
});

//***** WebSocket-server *****//
var WebSocketServer = require('websocket').server; // Using module https://github.com/theturtle32/WebSocket-Node

// Create a new WebSocket-server
var wsServer = new WebSocketServer({
    httpServer: httpServer
});

// Handling WebSocket-server 'request' events
wsServer.on('request', function wsRequestHandler(request) {

    var reqProtocols = request.requestedProtocols,
    matchedProtocol = null;

    // Check if the origin of the request is approved
    if (!isOriginOK(request.origin)) {
        console.log('Origin not approved: ' + request.origin);
        request.reject('401', 'Origin not approved');
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

    // Set up a connection for the matched protocol
    switch(matchedProtocol) {
        case 'chat-protocol':
            chatConnectionSetUp(request.accept('chat-protocol', request.origin));
            break;
    }
});

/*
// Handling WebSocket-server 'close' events
wsServer.on('close',);
*/

// Set up a chat WebSocket connection
var chatConnectionSetUp = function(connection) {

    var chatMsg;

    // Handler for connection 'message' event
    connection.on('message', function messageHandler(message) {

        var reply;

        if (message.type === 'utf8') {
            chatMsg = JSON.parse(message.utf8Data);

            // Handling different type of messages
            switch(chatMsg.type) {
                case 'register':
                    // Check if a username is supplied
                    if (chatMsg.id === '') {
                        
                        // Username is missing
                        reply = {
                            type: 'error',
                            data: '-->Du måste ange ett användarnamn'
                        };
                        connection.send(JSON.stringify(reply));
                        return;
                    }

                    // Check if the username already exists
                    if (users.indexOf(chatMsg.id) > -1) {
                        
                        // The username is not unique
                        reply = {
                            type: 'error',
                            data: '-->Användarnamnet är redan taget'
                        };
                        connection.send(JSON.stringify(reply));

                    } else {
                        // Add user to connected users
                        users.push(chatMsg.id);

                        // Save the username in the connection as well so
                        // if the connection is closed, you know which user to
                        // remove
                        connection.user = chatMsg.id;
                    
                        // Let other users know that this user has connected
                        reply = {
                            type: 'message',
                            data: chatMsg.id + ' anslöt till chatten'
                        };
                        broadcast(reply);

                        // Add the connection to the array of active connections
                        connections.push(connection);
                        console.log('Conn-length register: ' + connections.length); // For testing
                        console.log('Users: ' + users.join(', '));// For testing

                        // Update the users information on which users are online
                        reply = {
                            type: 'connected_users',
                            data: users.join(', ')
                        };
                        broadcast(reply);
                    }
                    break;
                case 'message':

                    // Create a reply message
                    reply = {
                            type: 'message',
                            data:  chatMsg.id + ': ' + chatMsg.data
                    };

                    // Broadcast reply
                    broadcast(reply);
                    break;
            }
        }
    });

    // Handler for connection 'close' event
    connection.on('close', function(reasonCode, description) {

        console.log('Client ' + connection.remoteAddress + ' disconnected');

        if (connection.user !== undefined) {
            // Inform other users that the user is disconnected
            reply = {
                type: 'message',
                data: connection.user + ' lämnade chatten.'
            };
            broadcast(reply);

            // Remove the user from the users array
            users = users.filter(function(user) { return user !== connection.user;});
        }

        // Remove the connection from the connections array
        connections = connections.filter(function(conn) { return conn !== connection;});
        console.log('Conn-length: ' + connections.length); // For testing
        console.log('Users: ' + users.join(', ')); // For testing

        // Update the users information on which users are online
        reply = {
            type: 'connected_users',
            data: users.join(', ')
        };
        broadcast(reply);
    })
};

var isOriginOK = function(url) {

    return okOrigins.indexOf(url) > -1;
}

var broadcast = function(reply) {

    // Make it into a JSON-string
    reply = JSON.stringify(reply);

    // Broadcast the message to all users
    connections.forEach(function broadcastUTF(conn) {
        conn.sendUTF(reply);
    });
}
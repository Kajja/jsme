//***** Web Socket chat client, Mikael Feuk *****//

var WSClient = {
    websocket: null,
};

var user;

// Set up handlers for a websocket
WSClient.connSetUp = function(websocket, printer) {
 
    var _this = this;

    // Saving a reference, to be used in other methods
    this.websocket = websocket;

    // Event 'open'
    websocket.onopen = function() {
        var msg;

        printer.print('Nu är du uppkopplad.');

        // Hide 'connect'-button and show 'disconnect'-button
        $('#connect, #close').toggle();

        // Register user info
        msg = {
            type: 'register',
            id: user,
            data: document.getElementById('msgText').value
        };
        websocket.send(JSON.stringify(msg));
    };

    // Event 'error'
    websocket.onerror = function(event) {
        printer.print('Något gick fel');
        return false;
    };

    // Event 'message'
    websocket.onmessage = function(message) {

        var serverMsg = JSON.parse(message.data);

        // Hanling different server message types
        switch(serverMsg.type) {
            case 'error':
                printer.print(serverMsg.data);
                websocket.close();
                break;
            case 'message':
                printer.print(serverMsg.data);
                break;
            case 'connected_users':
                document.getElementById('online').textContent = serverMsg.data;
                break;          
        }
    };

    // Event 'close'
    websocket.onclose = function(event) {
        printer.print('Du har kopplats ner från servern');
        $('#connect, #close').toggle();
        document.getElementById('online').textContent = ''; // TODO: Inte så snyggt
        //document.getElementById('outtext').value = '';
    };
};

// Check if there is an active WebSocket connection
WSClient.isConnected = function() {
    return this.websocket && 
        this.websocket.readyState === this.websocket.OPEN;
};

// Helper 'class' to print text
function Printer(element) {
    this.out = element;
};

Printer.prototype.print = function(text) {
    this.out.value = text + '\n' + this.out.value;
};


$(document).ready(function(){
  'use strict';

    var url = document.getElementById('url'),
    connect = document.getElementById('connect'),
    disconnect = document.getElementById('close'),
    message_el = document.getElementById('msgText'),
    send = document.getElementById('send'),
    out = document.getElementById('outtext'),
    user_el = document.getElementById('user'),
    websocket = null,
    printer = new Printer(out);

    // 'click' handler for the connect button
    connect.addEventListener('click', function(event) {

        var message;

        if (user_el.value === '') {
            printer.print('Du måste ange ett användarnamn');
            return false;
        }

        if (!WSClient.isConnected()) {

            printer.print('Connecting to: ' + url.value);
            websocket = new WebSocket(url.value, 'chat-protocol'); // Using a sub protocol

            user = user_el.value;

            // Set up connection handlers
            WSClient.connSetUp(websocket, printer);
        }
    }, false);

    // 'click' handler for the disconnect button
    disconnect.addEventListener('click', function(event) {

        if (WSClient.isConnected()) {
            websocket.close();
        }
    });

    // 'click' handler for the send button
    send.addEventListener('click', function(event) {

        var message;

        if (WSClient.isConnected()) {

            // Create message format and send to server
            message = {
                type: 'message',
                id: user,
                data: message_el.value
            }
            websocket.send(JSON.stringify(message));
            message_el.value = '';
        }
    });
});

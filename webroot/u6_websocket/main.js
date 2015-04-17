//***** Web Socket client, Mikael Feuk *****//

var WSClient = {
    websocket: null
};

// Set up handlers for a websocket
WSClient.connSetUp = function(websocket, printer) {
 
    var _this = this;

    // Saving a reference, to be used in other methods
    this.websocket = websocket;

    // Event 'open'
    websocket.onopen = function() {
        printer.print('The websocket is now open.');
        websocket.send('Thanks for letting me connect to you.');
    }

    // Event 'message'
    websocket.onmessage = function(event) {
        printer.print('Från server: ' + event.data);
    }

    // Event 'close'
    websocket.onclose = function() {
        printer.print('The websocket is now closed.');
    }
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
    message = document.getElementById('msgText'),
    send = document.getElementById('send'),
    out = document.getElementById('outtext'),
    websocket = null,
    printer = new Printer(out);

    // 'click' handler for the connect button
    connect.addEventListener('click', function(event) {

        var protocol;

        if (!WSClient.isConnected()) {

            protocol = document.getElementById('protocol').value;
            console.log(protocol + '-protocol');

            printer.print('Connecting to: ' + url.value);
            websocket = new WebSocket(url.value, protocol + '-protocol'); // Using a sub protocol

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

        if (WSClient.isConnected()) {
            websocket.send(message.value);
            message.value = '';
        }

    }, false);
});

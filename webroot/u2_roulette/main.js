$(document).ready(function(){
    'use strict';
    
    // Set event handler for spin-button
    document.getElementById('spin_btn').addEventListener('click', function(e){
        var amount, color, playerBet;

        // Clear possible error messages
        roulette.table.displayMsg('');

        amount = parseInt(document.getElementById('amount').value);
        color = document.getElementById('color').value;
        playerBet = roulette.table.bet(roulette.player, color, null, amount);

        if (playerBet) {
            roulette.table.bets.push(playerBet);
            roulette.table.spin();        
        }
    });

    // Initiating game
    roulette.table.create();
    roulette.player.addToFund(100);
    roulette.player.displayFunds();
});

var roulette = {
    table: {
        numbers: 37,
        colors: ['green', 'black', 'red'],
        numColors: [0, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 1, 2, 1, 2, 1, 2, 1, 2, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 1, 2, 1, 2, 1, 2, 1, 2],
        ballNum: null, // The number that was the result of a spin
        bets: [],
        // Returns a function that represent a bet and that can "clear" itself, ie.
        // the returned function checks if the bet is "in the money" and updates the player.
        // But if bet-amount > funds or amount < 1, it returns false and not a funtion.
        bet: function(player, color, num, amount) {
            var that = this;
            if (amount < 1 || isNaN(amount)) {
                this.displayMsg('Din satsning måste vara större än noll');
                return false;
            }
            if (player.funds - amount < 0) {
                this.displayMsg('Du har inte tillräckligt mycket på kontot för den satsningen');
                return false;
            } else {
                player.funds -= amount; // Reduce the funds with amount the player bets
                player.displayFunds();

                return function(drawnNum) {
                    var win = 0;
                    if (num === drawnNum) {
                        // Här kan man göra ngt om man kan spela på nr
                    }else {
                        if (color === that.colors[that.numColors[drawnNum]]) {
                            switch(color){
                                case 'green':
                                    win = amount * 35;
                                    break;
                                default:
                                    win = amount * 2;
                            }
                            player.addToFund(win);
                            that.displayMsg('Grattis du vann ' + win + '!', true);
                        } else {
                            that.displayMsg('Tyvärr du förlorade.', true);
                        }
                    }
                    player.displayFunds();
                };
            }
        },
        // Writes text in the 'message area'
        displayMsg: function(text, add) {
            var outEl = document.getElementById('text');
            if (add === true) {
                outEl.innerHTML += '<p>' + text + '</p>';
            } else {
                outEl.innerHTML = '<p>' + text + '</p>';
            }
        },
        // Generates a random number and animates the spin
        spin: function() {
            var min = 0, max = 36, iter = 0, that = this;

            // Clear any highlight from last spin
            this.ballNum === null || document.getElementById('num' + this.ballNum).classList.remove('highlight');

            this.displayMsg('Det snurrar!');

            // Create random number
            this.ballNum = Math.floor((Math.random() * (max - min + 1))) + min;

            // Declares function that animate the spin
            function spinAnimate(oldEl) {
                var numEl = document.getElementById('num' + iter%(max + 1));
                numEl.classList.add('highlight');
                oldEl === null || oldEl.classList.remove('highlight'); // Använder OR som if-sats
                if (!(iter > max && (iter%(max+1)) === that.ballNum)) {
                    setTimeout(spinAnimate, 50 + iter * 5, numEl);
                } else {
                    that.displayMsg('Numret blev: ' + that.ballNum);
                    // The spin is finished, clear the bets
                    roulette.table.clearBets();           
                }
                iter++;
            }
            spinAnimate(null);    
        },
        // Goes through all bets and updates the players that have won
        clearBets: function() {
            var i;
            for(i = 0 ; i < this.bets.length ; i++) {
                this.bets[i](this.ballNum); // The bets are functions
            }
            this.bets.length = 0; // Clears the bets array
        }
    },
    player: {
        funds: 0,
        addToFund: function(amount) {
            this.funds += amount;
        },
        displayFunds: function() {
            document.getElementById('funds').value = this.funds;
        }
    },
};

roulette.table.create = function() {

    var i, html = '', color,
        tableDiv = document.getElementById('rtable');

    for (i = 0 ; i < this.numbers ; i++) {
        color = this.colors[this.numColors[i]];
        html += '<div class="number ' + color + '" id="num' + i + '">' + i + '</div>';
    }
    tableDiv.innerHTML = html;
};
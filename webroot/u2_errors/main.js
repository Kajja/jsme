$(document).ready(function(){
    'use strict';

    var errorText = '';
    var result = document.getElementById('res');

    try {
        numberTest(11);
    } catch (error) {
        if (error instanceof TypeError) {
            errorText = error.name + ': ' + error.message;
        } else if (error instanceof RangeError) {
            errorText = error.name + ': ' + error.message;
        }
        result.innerHTML += '<li>' + errorText + '</li>';
    }

    try {
        numberTest('hej');
    } catch (error) {
        if (error instanceof TypeError) {
            errorText = error.name + ': ' + error.message;
        } else if (error instanceof RangeError) {
            errorText = error.name + ': ' + error.message;
        }
        result.innerHTML += '<li>' + errorText + '</li>';
    }
});

function numberTest(number) {

    if (typeof number !== 'number') {
        throw new TypeError('Måste vara ett nummer');
    }

    if (number > 10) {
        throw new RangeError('Inga nummer över 10');
    }
};
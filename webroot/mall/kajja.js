/**
 * My very own JavaScript lib
 *
 * by Mikael Feuk
 */

window.Kajja = (function(window, document) {
    
    var Kajja = {};

    /** 
     * Generate random number between min and max inclusive
     * 
     * @param min minimum value generated
     * @param max maximum value generated
     * @return int random value
     */
    Kajja.random = function(min, max) {

        return Math.floor((Math.random() * (max - min + 1))) + min;
    };

    /** 
     * Check if value is a number
     * 
     * @param value to be checked
     * @return bool true if it is a number
     */
    Kajja.isNumber = function(value) {

        return !isNaN(parseFloat(value)) && isFinite(value);
    };

    return Kajja;
    
})(window, document);
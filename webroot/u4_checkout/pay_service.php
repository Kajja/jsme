<?php

$card = ['name' => 'kalle', 'num' => '4916713787206385', 'cvc' => '123'];

//Payment service
if (isset($_GET['action'])) {

    $action = $_GET['action'];
    $response = [];

    if ($action == 'pay') {

        sleep(1); // To simulate delay

        $ccName = isset($_POST['name']) ? $_POST['name'] : null;
        $ccNum = isset($_POST['card_num']) ? $_POST['card_num'] : null;
        $cvc = isset($_POST['cvc']) ? $_POST['cvc'] : null;

        $infoStatus = checkInfo($ccName, $ccNum, $cvc);

        if ($infoStatus['ok']) {

            if (checkCC($ccName, $ccNum, $cvc, $card)) {

                $response['status'] = 'ok';
                $response['msg'] = 'Betalningen är genomförd';

            } else {
                $response['status'] = 'error';
                $response['msg'] = 'Felaktiga kreditkortsuppgifter';               
            }

        } else {
            $response['status'] = 'error';
            $response['msg'] = $infoStatus['msg'];
        }
    }
    header('Content-type: application/json');
    echo json_encode($response);
}


function checkInfo($name, $num, $cvc) {

    $status = ['ok' => true, 'msg' => ''];

    if (!$name) {
        $status['ok'] = false;
        $status['msg'] = 'Du måste ange namn. ';
    }
    if (!isValidCCNumber($num)) {
        $status['ok'] = false;
        $status['msg'] .= 'Kortnumret är inte giltigt. ';   
    }
    if (!is_numeric($cvc)) {
        $status['ok'] = false;
        $status['msg'] .= 'CVC måste vara ett nummer. ';   
    }
    if (strlen($cvc) != 3) {
        $status['ok'] = false;
        $status['msg'] .= 'CVC måste vara tre siffror'; 
    }
    return $status;
}


function checkCC($name, $num, $cvc, $card) {
    return ($name == $card['name']) && ($num == $card['num']) && ($cvc == $card['cvc']);
}


// Adapted from Java code at http://www.merriampark.com/anatomycc.htm
// by Andy Frey, onesandzeros.biz
// Checks for valid credit card number using Luhn algorithm
// Source from: http://onesandzeros.biz/notebook/ccvalidation.php
// 
// Try the following numbers, they should be valid according to the check:
// 4408 0412 3456 7893
// 4417 1234 5678 9113
//
function isValidCCNumber( $ccNum ) {
    $digitsOnly = "";
    // Filter out non-digit characters
    for( $i = 0; $i < strlen( $ccNum ); $i++ ) {
        if( is_numeric( substr( $ccNum, $i, 1 ) ) ) {
            $digitsOnly .= substr( $ccNum, $i, 1 );
        }
    }
    // Perform Luhn check
    $sum = 0;
    $digit = 0;
    $addend = 0;
    $timesTwo = false;
    for( $i = strlen( $digitsOnly ) - 1; $i >= 0; $i-- ) {
        $digit = substr( $digitsOnly, $i, 1 );
        if( $timesTwo ) {
            $addend = $digit * 2;
            if( $addend > 9 ) {
                $addend -= 9;
            }
        } else {
            $addend = $digit;
        }
        $sum += $addend;
        $timesTwo = !$timesTwo;
    }
    return $sum % 10 == 0;
}


/*
MII Digit Value Issuer Category
0 ISO/TC 68 and other industry assignments
1 Airlines
2 Airlines and other industry assignments
3 Travel and entertainment
4 Banking and financial
5 Banking and financial
6 Merchandizing and banking
7 Petroleum
8 Telecommunications and other industry assignments
9 National assignment
*/


/*
Issuer  Identifier  Card Number Length
Diner's Club/Carte Blanche  300xxx-305xxx,
36xxxx, 38xxxx  14
American Express  34xxxx, 37xxxx  15
VISA  4xxxxx  13, 16
MasterCard  51xxxx-55xxxx   16
Discover  6011xx  16
*/
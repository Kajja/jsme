<?php

session_start();

if (isset($_GET['action'])) {

    $action = $_GET['action'];
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : ['items' => [], 'num_items' => 0, 'sum' => 0];

    switch ($action) {

        case 'add':        
            if (!isset($cart['items'][$_POST['id']])) {
                $cart['items'][$_POST['id']] = [
                    'name' => $_POST['name'], 
                    'num_of' => 1,
                    'sum' => $_POST['price']];
            } else {
                $cart['items'][$_POST['id']]['name'] = $_POST['name'];
                $cart['items'][$_POST['id']]['num_of']++;
                $cart['items'][$_POST['id']]['sum'] += $_POST['price'];
            }
            $cart['num_items']++;
            $cart['sum'] += $_POST['price'];
            $_SESSION['cart'] = $cart;
            break;

        case 'clear':
            $_SESSION['cart'] = ['items' => [], 'num_items' => 0, 'sum' => 0];            
            break;   

        default:
    }
    header('Content-type: application/json');
    echo json_encode($_SESSION['cart']);
}
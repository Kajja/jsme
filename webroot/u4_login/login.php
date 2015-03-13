<?php

$cred = ['user' => 'kalle', 'passw' => 'abc'];

session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();

if (isset($_GET['do'])) {

    $action = $_GET['do'];
    $response = '';

    switch($action) {
        case 'login':
            $user = isset($_POST['user']) ? $_POST['user'] : null;
            $passw = isset($_POST['passw']) ? $_POST['passw'] : null;
            
            if ($user && $passw && ($cred['user'] == $user) && ($cred['passw'] == $passw)) {
                $_SESSION['user'] = $user;
                $response = 'Inloggad som ' . $_SESSION['user'];
            } else {
                $response = 'Användarnamn eller lösenord är fel';
            }
            break;

        case 'logout':

            if (isset($_SESSION['user'])) {
                unset($_SESSION['user']);
                $response = 'Utloggad';
            } else {
                $response = 'Det var ingen som var inloggad';
            }
            break;

        case 'status':
            if (isset($_SESSION['user'])) {
                $response = 'Inloggad som ' . $_SESSION['user'] . '.';
            } else {
                $response = 'Inte inloggad';
            }
            break;
    }

    header('Content-type: application/json');
    echo json_encode(['out' => $response]);
}
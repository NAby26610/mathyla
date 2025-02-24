<?php

include_once (dirname(__DIR__) . '/modeles/modeleClasse.php');
include_once (dirname(__DIR__) . '/fonction/fonction.php');
include_once (dirname(__DIR__) . '/config/domaine.php');
// session_start();

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


try {
    $connect = new PDO('mysql:host=localhost;dbname=afrifish', 'root', '');
} catch (Exception $e) {
    // die('Erreur  de connexion  : ' . $e->getMessage());
    echo json_encode("erreur de connexion : ", $e->getMessage());
}



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


// PRODUCTION
// try {
//     $connect = new PDO('mysql:host=ep5ykc.myd.infomaniak.com;dbname=ep5ykc_matyla_transfert', 'ep5ykc_SPA-DEV', 'KonindouSpa01-DB');
// } catch (Exception $e) {
//     die('Erreur  de connexion  : ' . $e->getMessage());
// }

// DEVELOPPEMENT
try {
    $connect = new PDO('mysql:host=localhost;dbname=db_mathyla', 'root', '');
} catch (Exception $e) {
    // die('Erreur  de connexion  : ' . $e->getMessage());
    echo json_encode("erreur de connexion : ", $e->getMessage());
}



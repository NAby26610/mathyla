<?php

include_once(dirname(__DIR__) . '/modeles/modeleClasse.php');
include_once(dirname(__DIR__) . '/fonction/fonction.php');
// session_start();

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// PRODUCTION
// try {
//     // $connect = new PDO('mysql:host=ep5ykc.myd.infomaniak.com;dbname=ep5ykc_matyla_transfert', 'ep5ykc_SPA-DEV', 'KonindouSpa01-DB');
//     $connect = new PDO("mysql:host=ep5ykc.myd.infomaniak.com;dbname=ep5ykc_matyla_transfert;charset=utf8", "ep5ykc_SPA-DEV", "KonindouSpa01-DB", [
//         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//         PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
//     ]);
// } catch (Exception $e) {
//     die('Erreur  de connexion  : ' . $e->getMessage());
// }

// DEVELOPPEMENT
try {
    // $connect = new PDO('mysql:host=localhost;dbname=db_mathyla', 'root', '');
    $connect = new PDO("mysql:host=localhost;dbname=db_mathyla;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);
} catch (Exception $e) {
    // die('Erreur  de connexion  : ' . $e->getMessage());
    echo json_encode("erreur de connexion : ", $e->getMessage());
}

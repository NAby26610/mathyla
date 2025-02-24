<?php
function deb($data)
{
    // var_dump("<pre>".$data."</pre>");
    echo json_encode($data, true);
    die();
}

/**
 * Convertir une date en format 'YYYY-MM-DD' (GMT)
 */
function dateConvert($votre_date)
{
    try {
        $timezone = new DateTimeZone('GMT');
        $date = new DateTime($votre_date, $timezone);
        return $date->format('Y-m-d');
    } catch (Exception $e) {
        return $e->getMessage(); // Gérer les erreurs si la date est invalide
    }
}
function dateConvertMois($votre_date)
{
    try {
        $timezone = new DateTimeZone('GMT');
        $date = new DateTime($votre_date, $timezone);
        return $date->format('Y-m');
    } catch (Exception $e) {
        return $e->getMessage(); // Gérer les erreurs si la date est invalide
    }
}

// Generation du Token d'Auth
function generateToken_($userId)
{
    return sha1(uniqid($userId . time()));
}
function loginToken($length = 32)
{
    // Génère une chaîne binaire aléatoire
    $randomBinaryString = openssl_random_pseudo_bytes($length);

    // Convertit la chaîne binaire en une chaîne hexadécimale
    $token = bin2hex($randomBinaryString);

    return $token;
}

function format2Chart($data)
{
    $tab = explode('.', $data);
    if (empty($tab[1])):
        $tab[1] = "00";
    endif;
    return $tab[0] . '.' . substr($tab[1], 0, 2);
}

function _Aujourdhui()
{
    // Définir le fuseau horaire GMT+00
    $timezone = new DateTimeZone('GMT');

    // Créer une nouvelle instance de DateTime avec le fuseau horaire défini
    $date = new DateTime('now', $timezone);

    // Formater la date selon votre besoin
    $date_format = $date->format('Y-m-d');

    // Afficher la date
    return $date_format;
}

function CE_MOIS()
{
    // Définir le fuseau horaire GMT+00
    $timezone = new DateTimeZone('GMT');

    // Créer une nouvelle instance de DateTime avec le fuseau horaire défini
    $date = new DateTime('now', $timezone);

    // Formater la date selon votre besoin
    $date_format = $date->format('Y-m');

    // Afficher la date
    return $date_format;
}

function _Hier()
{
    // Définir le fuseau horaire GMT+00
    $timezone = new DateTimeZone('GMT');

    // Créer une nouvelle instance de DateTime avec le fuseau horaire défini
    $date = new DateTime('now', $timezone);

    // Soustraire un jour
    $date->modify('-1 day');

    // Formater la date selon votre besoin
    return $date->format('Y-m-d');
}


function obtenirDateHeureActuelles()
{
    // Définir le fuseau horaire GMT+00
    $timezone = new DateTimeZone('GMT');

    // Créer une nouvelle instance de DateTime avec le fuseau horaire défini
    $date = new DateTime('now', $timezone);

    // Formater la date selon votre besoin
    $date_format = $date->format('d/m/Y');

    // Afficher la date
    return $date_format;
}


function dateFR($votre_date)
{
    // Définir le fuseau horaire GMT+00
    $timezone = new DateTimeZone('GMT');

    // Créer une nouvelle instance de DateTime avec le fuseau horaire défini
    $date = new DateTime($votre_date, $timezone);
    //  $date = $votre_date;

    // Formater la date selon votre besoin
    // $date_format = $date->format('d-m-Y');
    $date_format = $date->format('d/m/Y');

    // Afficher la date
    return $date_format;
}

// Function de protection des trim sur les vars...
function str_secure($str)
{
    return $str = htmlspecialchars(htmlentities($str));
}

function formatNumber1($Number)
{
    return number_format($Number, 0, '.', ' ');
}
function formatNumber2($Number)
{
    return number_format($Number, 2, '.', ' ');
}

function etatProprite($status)
{
    switch ($status):
        case '1':
            return 'Location';
        case '2':
            return 'Vente';
        case '3':
            return 'Chantier';
        default:
            return 'Aucune';
    endswitch;
}
function statutProprite($status)
{
    switch ($status):
        case '1':
            return 'Libre';
        case '2':
            return 'Occuper';
        case '3':
            return 'Reservez';
        default:
            return 'Aucune';
    endswitch;
}
function statutContrat($status)
{
    switch ($status):
        case '1':
            return 'Non Payer';
        case '2':
            return 'En cours';
        case '3':
            return 'Relancer';
        default:
            return 'Aucune';
    endswitch;
}


function nombreDeMoisEntreDeuxDates($date1, $date2)
{
    // Convertit les dates en objets DateTime
    $date1 = new DateTime($date1);
    $date2 = new DateTime($date2);

    // Calculer la différence entre les deux dates
    $difference = $date1->diff($date2);

    // Calculer le nombre total de mois en utilisant l'année et le mois
    $nombreDeMois = ($difference->y * 12) + $difference->m;

    return $nombreDeMois;
}

function nombreDeJoursEntreDeuxDates($date1, $date2)
{
    // Convertit les dates en objets DateTime
    $date1 = new DateTime($date1);
    $date2 = new DateTime($date2);

    // Calculer la différence entre les deux dates
    $difference = $date1->diff($date2);

    // Extraire le nombre de jours de la différence
    $nombreDeJours = $difference->days;

    return $nombreDeJours;
}

// Fonction pour calculer le nombre de jours restants jusqu'à la fin de la location
function joursRestantsLocation($dateFinLocation)
{
    // Convertir la date de fin de location en timestamp Unix
    $dateFinLocationTimestamp = strtotime($dateFinLocation);

    // Timestamp actuel
    $timestampActuel = time();

    // Calculer la différence en secondes
    $difference = $dateFinLocationTimestamp - $timestampActuel;

    // Convertir la différence en jours
    $joursRestants = ceil($difference / (60 * 60 * 24));

    return $joursRestants;
}





// NIMBA SMS DOCUMENTATION FONCTION
function Nimba_SMS($phoneNumber, $sms_)
{
    $url = "https://api.nimbasms.com/v1/messages";

    $headers = array(
        "Authorization: Basic ZDQxNjgxOWNhMzg0MDNmMjM3OGExYmZmOGY4N2I4YTc6SnJhM0V3YnlWOVpuVkR1SW1pNktkUFhCZWRLSklKQU1tLTBuSFJaN2dNejJnT1JVa1l5TUlzTkVfTzhxYWtFQUdPUFRDNTVON2EyZDMyd21LZkk2c2pjWEZTZDVOcGc1RUwybF9GTnZ0U2M=",
        "Content-Type: application/json"
    );

    $body = array(
        "to" => array('+224' . $phoneNumber),
        "sender_name" => "SMS 9080",
        "message" => $sms_
    );

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return curl_error($ch);
    } else {
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status_code == 201) {
            return $response;
        } else {
            return $response;
        }
    }

    curl_close($ch);
}

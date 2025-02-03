<?php
function deb($data)
{
    // var_dump("<pre>".$data."</pre>");
    echo json_encode($data, true);
    die();
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
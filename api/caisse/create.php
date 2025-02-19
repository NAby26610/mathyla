<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $caisse = $_POST['caisse'];
    unset($_POST['caisse']);

    try {
        // Creation d'une caisse
        
            $ajout_partenaire = ModeleClasse::add(" $caisse", $_POST);
            if ($ajout_partenaire) {
                $message = "caisse ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de caisse.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>

<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $initCommande = $_POST['initCommande'];
    unset($_POST['initCommande']);

    try {
        // Creation d'une initCommande
        
            $ajout_partenaire = ModeleClasse::add(" $initCommande", $_POST);
            if ($ajout_partenaire) {
                $message = "initCommande ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de initCommande.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>

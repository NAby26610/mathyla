<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $initVente = $_POST['initVente'];
    unset($_POST['initVente']);

    try {
        // Creation d'une initVente
        
            $ajout_partenaire = ModeleClasse::add(" $initVente", $_POST);
            if ($ajout_partenaire) {
                $message = "initVente ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de initVente.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>

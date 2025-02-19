<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $panierVente = $_POST['panierVente'];
    unset($_POST['panierVente']);

    try {
        // Creation d'une panierVente
        
            $ajout_partenaire = ModeleClasse::add(" $panierVente", $_POST);
            if ($ajout_partenaire) {
                $message = "panierVente ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de panierVente.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>

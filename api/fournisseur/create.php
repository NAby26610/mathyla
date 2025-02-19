<?php
require_once ('../../config/database.php');
$message = "";

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_secure($value);
    }
    extract($_POST);
    $fournisseur = $_POST['fournisseur'];
    unset($_POST['fournisseur']);

    try {
        // Creation d'une fournisseur
        
            $ajout_partenaire = ModeleClasse::add(" fournisseur", $_POST);
            if ($ajout_partenaire) {
                $message = "fournisseur ajouté avec succès.";
            } else {
                $message = "Échec de l'ajout de fournisseur.";
            }
      
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }

    echo json_encode($message);
} else {
    echo json_encode("Aucune donnée reçue");
}
?>

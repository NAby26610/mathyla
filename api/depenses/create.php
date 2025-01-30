<?php 
    require_once('../../config/database.php');
    $message ="";
    if(isset($_POST) && !empty($_POST)){
        foreach($_POST as  $key=>$value){
            $_POST[$key] = str_secure($_POST[$key]);
        }
        extract($_POST);
        try {
            $ajout = ModeleClasse::add("depenses",$_POST);
            if(!$ajout):
                $message = "Enregistrer avec succes";
            else :
                $message = "Enregistrement echoue";
            endif;
            echo json_encode($message);
        } catch (\Throwable $th) {
            echo json_encode($th->getMessage());
        }
    }else{
        echo json_encode("Aucune donnees reçu");
    }
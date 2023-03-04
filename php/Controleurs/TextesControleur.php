<?php

namespace Controleurs;

use Modeles\Bdd;
use Modeles\Entites\Textes;
require_once './Modeles/Entites/Textes.php';
require_once './Modeles/Bdd.php';

class TextesControleur{
    public function liste()
    {
        $t = Bdd::select("textes", "textes");
        
        include "vues/header.html.php";
        include "vues/textes/textes.interface.html.php";
        include "vues/footer.html.php";     
    }

    public function ajouter(){
        if($_POST){
            //debug($_POST);
            $t = new Textes;
            
            //var_dump($f->getImg());
            $t->setText($_POST['txt']);

            // L'objet, la table, la colonne et la valeur que l'on souhaite insérer en base de données
            $resultat = Bdd::insertProjet($t, "textes", "text", ":text");
            var_dump($resultat);
        
            if($resultat){
                redirection("index_back.php");
                exit;
            } else{
                echo "Erreur SQL lors de l'insertion";
            }
        }   
        
        // AFFICHAGE

        $t = new Textes;

        include "./vues/header.html.php";
        include "./vues/textes/textes.form.html.php";
        include "./vues/footer.html.php";
    }

    public function supprimer($id)
    {

        if($id) {
            if( is_numeric($id) ) {
                $t = Bdd::selectById("textes", "textes", $id);

                if($t) {
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        if( Bdd::deleteItem($t, 'textes') == 1) {
                            redirection("index_back.php");
                        }
                    }
                } else {
                    redirection("index_back.php");
                }
            }
        }
        affichage("textes/suppression.textes.html.php", [ "textes" => $t ]);
    }

    public function modifier($id)
    {
        $projet = Bdd::selectById("textes", 'textes', $id);
        //var_dump($projet);
        
        if($_POST){
            $t = new Textes;
            $text = $_POST['txt'] ?? null;
            //$resume = $_POST["resume"] ?? null;
            //var_dump($p->getName());
        
            if( !empty($text) ) {

                if( strlen($text) > 5000 ) {
                    $erreurs["txt"] = "Le texte ne doit pas dépasser 5000 caractères";
                }
            } else {
                $erreurs["generale"] = "Veuillez remplir le champs texte";
            }
        
            if( empty($erreurs) ){
                $t->setId($id);
                $t->setText($text);
                $t->setDate(date('d-m-Y H:i:s'));
                //var_dump($p->getDate());
                //var_dump($p->getOldFile());
                // Le texte que l'on souhaite modifier en base de données
                if( Bdd::updateProjet($t, 'textes', 'text = :text') ){
                    redirection("vues/textes/textes.interface.html.php");
                } else {
                    $erreurs["generale"] = "Erreur lors de la modification en bdd";
                }
            }
        
        }
        include "vues/header.html.php";
        include "vues/textes/textes.form.html.php";
        include "vues/footer.html.php";
    }
}
?>
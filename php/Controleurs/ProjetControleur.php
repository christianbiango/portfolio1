<?php

namespace Controleurs;
if($_SERVER['PHP_SELF'] == '/portfolio/php/Controleurs/ProjetControleur.php') header('Location: ../vues/login/logout.html.php');

use Modeles\Bdd;
use Modeles\Entites\Projet;
use Modeles\Entites\Images;
use Modeles\Entites\Textes;

use const Modeles\IS_NULL;
use const Modeles\UN;
use const Modeles\QUATRE;
use const Modeles\CINQUANTE;
use const Modeles\CINQMILLE;

require_once './Modeles/Entites/Projet.php';
require_once './Modeles/Bdd.php';

class ProjetControleur{
    // // Affiche la liste de projets d'images ou de textes, gérée par le controleur de pagination
    public function liste()
    {
        PaginationControleur::createPage($_GET['outil']);
    }

    // Modifie des informations en base de données
    public function modifier($id)
    {
        $this->ajoutOuModification($id);
    }
    // Ajoute des projets en base de données
    public function ajouter()
    {
        $this->ajoutOuModification();
    }

    // Les 3 méthodes de vérification de tous les formulaires du back-office

    // formOutil prend l'outil (projet, image ou texte) choisit par l'utilisateur et retourne une array des valeurs du formulaire
    public function formInfos($outil){
        if($outil == 'projet'){
            // Pour les projets

            $name = $_FILES['img']['name'] ?? IS_NULL; //Voir constantes dansBdd.php 

            $img = $_FILES['img'] ?? IS_NULL;

            $titre = trim(htmlspecialchars($_POST['titre'])) ?? IS_NULL; // htmlspecialchars renforce l'évitement des failles xss
            
            $text = trim(htmlspecialchars($_POST['txt'])) ?? IS_NULL;

            return $arr = ["name" => $name, "img" => $img, "titre" => $titre, "text" => $text];

        } else if($outil == 'images'){
            // Pour les images
            $name = $_FILES['img']['name'] ?? IS_NULL; 

            $img = $_FILES['img'] ?? IS_NULL;

            return $arr = ["name" => $name, "img" => $img];

        } else if($outil == 'textes'){
            //Pour les textes
            $text = trim(htmlspecialchars($_POST['txt'])) ?? IS_NULL;

            return $arr = ["text" => $text];
        }
 
    }

    // Vérification du formulaire pour les projets
    public function projetFormCheck($arrFormInfos){
        // Si un élément de l'array retournée par formInfos est vide, retourne un string invitant l'utilisateur a remplir les champs
        foreach($arrFormInfos as $el){
            if($el == IS_NULL || empty($el)){
                return $erreurs["generale"] = "Veuillez remplir les champs requis";
            }
        }
            // Vérification du format du titre
            if( strlen($arrFormInfos['titre']) < QUATRE ) {
                return $erreurs["titre"] = "Le titre doit avoir au moins 4 caractères";
            }

            // Vérification du format du texte
            $textCheck = $this->texteCheck($arrFormInfos['text']);
            if(!($textCheck == '')) return $textCheck;

            // Vérification du format du nom de l'image
            $nameCheck = $this->nameCheck($arrFormInfos['name']);
            if(!($nameCheck == '')) return $nameCheck;

            // Si tout est correct, aucune indication ne sera retournée

    }

    // Vérification du formulaire pour les textes. Prend en paramètre le texte du formulaire
    public function texteCheck($texte){

        if(isset($texte) && !empty($texte)){
            if( strlen(trim($texte)) > CINQMILLE ) {
                return $erreurs["texte"] = "Le texte ne doit pas dépasser 5000 caractères";
            }
        } else{
            return $erreur["generale"] = "Veuillez remplir le texte";
        }
    }

    // Vérification du formulaire pour les images. Prend en paramètre l'image du formulaire
    public function nameCheck($name){
  
        if(isset($name) && !empty($name)){
            if( strlen(trim($name)) > CINQUANTE ) {
                return $erreurs["name"] = "Veuillez raccourcir le nom de l'image";
            }
        } else{
            return $erreur["generale"] = "Veuillez insérer une image";
        }
    }

    // La méthode de suppression
    public function supprimer($id)
    {
        $outil = $_GET['outil'];

        if($id) {
            if( is_numeric($id) ) {
                $projet = Bdd::selectById($outil, $outil, $id);

                if($projet) {
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        if( Bdd::deleteItem($projet, $outil) == UN) {
                            redirection("index.back.php");
                        }
                    }
                } else {
                    redirection("index.back.php");
                }
            }
        }
        affichage("projet/suppression.projet.html.php", [ "projet" => $projet ]);
    }


    /**
     * ajoutOuModification function : ajoute ou modifie un projet en base de données.
     *
     * @param [type] $id
     * @return void
     */
    public function ajoutOuModification($id = IS_NULL){
        $outil = $_GET['outil'];
        $methode = $_GET['methode'];

        // En fonction de ce qui se trouve dans l'url, on créer un nouvel objet de cette classe
        $outilArr = ["projet" => new Projet, "images" => new Images, "textes" => new Textes];

        if($_POST || $_FILES){

            $projet = $outilArr[$outil];
            $formArr = $this->formInfos($outil);

            
            if($outil == "images"){
                // Pour les images

                // form verif
                $formCheck = $this->nameCheck($formArr['name']);
                if(empty($formCheck)){
                    // Les informations sont correctes
                    $projet->setImg($formArr['img']);

                    if($methode == "ajouter"){

                        $colonnes = "name, img";
                        $valeurs = ":name, :img";
                    } else if($methode == "modifier") $colonnesEtValeurs = "name = :name, img = :img";
                }
                
            } else if($outil == "textes"){
                // Pour les textes

                // form verif
                $formCheck = $this->texteCheck($formArr['text']);
                if(empty($formCheck)){
                    // Les informations sont correctes

                    $projet->setText($formArr['text']);
                    if($methode == "ajouter"){

                        $colonnes = "text";
                        $valeurs = ":text";
                    } else if($methode == "modifier") $colonnesEtValeurs = "text = :text";
                }
                
            } else if($outil == "projet"){
                // Pour les projets

                //form Check
                $formCheck = $this->projetFormCheck($formArr);
                if(empty($formCheck)){
                    // Les informations sont correctes

                    $projet->setImg($formArr['img']);
                    $projet->setText($formArr['text']);
                    $projet->setTitre($formArr['titre']);
                    if($methode == "ajouter"){

                        $colonnes = "name, img, titre, text";
                        $valeurs = ":name, :img, :titre, :text";
                    } else if($methode == "modifier") $colonnesEtValeurs = "name = :name, img = :img, titre = :titre, text = :text";
                }
            }

            if(empty($formCheck)){
                // Le formulaire est valide

                if($methode == 'ajouter'){
                    // Le projet, la table, les colonnes et leurs valeurs qu'on souhaite ajouter en base de données
                    $resultat = Bdd::insertProjet($projet, $outil, $colonnes, $valeurs);
    
                    if($resultat){
                        redirection("index.back.php");
                        exit;
                    } else{
                        $err = "Erreur SQL lors de l'insertion";
                    }

                } else if ($methode == 'modifier'){

                    // On sélectionne le projet à remplacer
                    $oldProject = Bdd::selectById($outil, $outil, $id);
                    $projet->setId($id);

                    if($outil != 'textes'){
                        $projet->setOldFile($oldProject->getName()); // Uniquement pour les projets contenant des images
                    }

                    // Le projet, la table, les colonnes et les valeurs que l'on souhaite modifier en base de données
                    if( Bdd::updateProjet($projet, $outil, $colonnesEtValeurs) ){

                        // Uniquement pour les projets contenant des images car il faut potentiellement effacer leur image du dossier uploads
                        if($outil != 'textes'){

                            // Si le projet conserve la même image, redirection immédiate
                            if($projet->getName() == $projet->getOldFile()){
                                redirection("vues/$outil/$outil.interface.html.php");

                                // Si le projet ne conserve pas la même image et qu'aucun autre projet ne possède cette dernière, l'effacer du dossier uploads
                            } else if(unlink('./uploads/' . $projet->getOldFile())){
                            redirection("vues/$outil/$outil.interface.html.php");
                            }

                            // Pour les textes, redirection immédiate car il n'y a rien à faire
                        } else if($outil == 'textes') redirection("vues/$outil/$outil.interface.html.php");

                        // Si updateProjet retourne une erreur
                    } else {
                    $erreurs["generale"] = "Erreur lors de la modification en bdd";
                    }

                }
            }   
        }
        
        
        // AFFICHAGE

        include "./vues/header.html.php";
        include "./vues/projet/form.html.php";
        include "./vues/footer.html.php";
    }

}
?>
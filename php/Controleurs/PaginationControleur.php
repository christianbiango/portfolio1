<?php

namespace Controleurs;


use Modeles\Bdd;
use Modeles\Entites\Pagination;
use const Modeles\CINQ;
use const Modeles\UN;
require_once './Modeles/Bdd.php';
require_once './includes/functions.inc.php';

class PaginationControleur{
    // Affiche une liste d'items
    public static function createPage($item){
        if($item == "contact"){
            // On veut des options de tri uniquement pour les messages

            if($_POST){
                // Plus récent ou plus ancien d'abord
                ($_POST['dateOption'] == "Plus récent d'abord" ? $dateOption = $_SESSION['dateOption'] = 'desc' : $dateOption = $_SESSION['dateOption'] = 'asc');
    
                // Afficher les messages traités uniquement ou tous les messages
                (isset($_POST['traitementOption']) && $_POST['traitementOption'] == "on" ? $traitementOption = $_SESSION['traitementOption'] = $_POST['traitementOption'] : $traitementOption = $_SESSION['traitementOption'] = '');
            } else{
                // Pas de $_POST
                (empty($_SESSION['dateOption']) ? $dateOption = $_SESSION['dateOption'] = 'desc' : $dateOption = $_SESSION['dateOption']);
    
                (empty($_SESSION['traitementOption']) ? $traitementOption = $_SESSION['traitementOption'] = '' : $traitementOption = $_SESSION['traitementOption'] );
    
            }
        }

        $pagination = new Pagination;

        (isset($_GET['page']) && !empty($_GET['page']) ?  $pagination->setCurrentPage(strip_tags($_GET['page'])) : $pagination->setCurrentPage(UN) );

        // Il faut définir traitementOption ainsi dans le cas où ce n'est pas la liste des messages que l'on souhaite
        if($_GET['controleur'] != 'msg') {
            $dateOption = ''; 
            $traitementOption = '';
        }

        // Calcul le nombre total de pages.

        $result = Bdd::calcTotalPages('nb_items', $item, $traitementOption);

        // Le nombre d'articles récupérés
        $pagination->setNbItems($result['nb_items']);

        // On détermine le nombre d'articles par page
        $pagination->setParPage(CINQ);
        
        // On calcule le nombre de pages total
        $pagination->setPages(ceil($pagination->getNbItems() / $pagination->getParPage()));

        // Calcul du 1er article de la page
        $pagination->setPremier(($pagination->getCurrentPage() * $pagination->getParPage()) - $pagination->getParPage());

        // Affiche les 5 items de la page actuelle
        $finalResult = Bdd::itemsPerPage($item, $pagination, $dateOption, $traitementOption);

        // La table et la classe msg n'ont pas le même nom, donc ça a créé ce problème de nom de fichier
        if($item == 'contact') $item = 'msg';
        include "vues/header.html.php";
        include "vues/$item/$item.interface.html.php";
        include "vues/pagination.html.php";
        include "vues/footer.html.php";  

    }
}
?>
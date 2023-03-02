<?php

namespace Controleurs;

use Modeles\Bdd;
use Modeles\Entites\Pagination;
require_once './Modeles/Bdd.php';
require_once './includes/functions.inc.php';

class PaginationControleur{
    public static function checkPage($msg){
        if($_POST){
            //var_dump($_POST['traitementOption']);
             if($_POST['dateOption'] == "Plus récent d'abord"){
                $dateOption = $_SESSION['dateOption'] = 'desc';
             } else{
                $dateOption = $_SESSION['dateOption'] = 'asc';
             }

             if(isset($_POST['traitementOption']) && $_POST['traitementOption'] == "on"){
                $traitementOption = $_SESSION['traitementOption'] = $_POST['traitementOption'];
             } else{
                $traitementOption = $_SESSION['traitementOption'] = '';
             }
        } else{
            if(empty($_SESSION['dateOption'])){
                $dateOption = $_SESSION['dateOption'] = 'desc';
            } else{
                $dateOption = $_SESSION['dateOption']; 
            }
            if(empty($_SESSION['traitementOption'])){
                $traitementOption = $_SESSION['traitementOption'] = ''; 
            } else{
                $traitementOption = $_SESSION['traitementOption'];
            }
        }
        //var_dump($dateOption);
        $msg = Bdd::select("contact", "msg", $dateOption, $traitementOption);
        //var_dump($msg);
        $pagination = new Pagination;

        if(isset($_GET['page']) && !empty($_GET['page'])){
            $pagination->setCurrentPage(strip_tags($_GET['page']));
        }else{
            $pagination->setCurrentPage(1);
        }

        $result = Bdd::calcTotalPages('nb_items', 'contact', 'pagination', $dateOption, $traitementOption);
        //var_dump($result);
        //var_dump($pagination->getNbItems());
        $pagination->setNbItems($result['nb_items']);

        // On détermine le nombre d'articles par page
        $pagination->setParPage(5);
        
        // On calcule le nombre de pages total
        $pagination->setPages(ceil($pagination->getNbItems() / $pagination->getParPage()));

        // Calcul du 1er article de la page
        $pagination->setPremier(($pagination->getCurrentPage() * $pagination->getParPage()) - $pagination->getParPage());

        $result2 = Bdd::itemsPerPage('contact', $pagination, 'pagination', $dateOption, $traitementOption);
        //var_dump($result2);

        include "vues/header.html.php";
        include "vues/msg/table.html.php";
        include "vues/footer.html.php";  
        //var_dump($result2);
        return $result2;

    }
}
?>
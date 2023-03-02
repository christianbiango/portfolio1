<?php

namespace Modeles\Entites;

class Pagination{
    private $currentPage;
    private $nb_items;
    private $parPage;
    private $pages;
    private $premier;

    public function getCurrentPage(){
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage){
        return $this->currentPage = $currentPage;
    }
    public function getNbItems(){
        return $this->nb_items;
    }

    public function setNbItems($nb_items){
        return $this->nb_items = $nb_items;
    }
    public function getParPage(){
        return $this->parPage;
    }

    public function setParPage($parPage){
        return $this->parPage = $parPage;
    }
    
    public function getPages(){
        return $this->pages;
    }

    public function setPages($pages){
        return $this->pages = $pages;
    }

    public function getPremier(){
        return $this->premier;
    }

    public function setPremier($premier){
        return $this->premier = $premier;
    }
}

?>
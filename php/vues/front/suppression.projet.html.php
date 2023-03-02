<?php
//include "../../includes/functions.inc.php";

//isLogged('../login/login.html.php');
if(!$_SESSION['loggedin']) {
    header('Location: ../login/login.html.php');
exit;
}
?>
<h1>Confirmation de la suppression du projet n°<?= $front->getId() ?> ?</h1>

<ul class="list-group">
    <li class="list-group-item">
        <strong>Nom : </strong> <?= $front->getName() ?>
    </li>
    <li class="list-group-item">
        <strong>Image : </strong> <?= '<img src="' . $front->getImg() . '" title="' . $front->getName() . '" width="100" height="100" />'; ?>
    </li>
    <li class="list-group-item">
        <strong>Titre : </strong> <?= $front->getTitre() ?>
    </li>
</ul>

<form method="post">
    <div class="d-flex justify-content-between">
        <button class="btn btn-success">Confirmer</button>
        <a href="<?= lien("front", "liste") ?>" class="btn btn-danger">Annuler</a>
    </div>
</form>
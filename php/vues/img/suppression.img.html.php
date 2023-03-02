<?php
//include "../../includes/functions.inc.php";

//isLogged('../login/login.html.php');
if(!$_SESSION['loggedin']) {
    header('Location: ../login/login.html.php');
exit;
}
?>
<h1>Confirmation de la suppression de l'image n°<?= $images->getId() ?> ?</h1>

<ul class="list-group">
    <li class="list-group-item">
        <strong>Nom : </strong> <?= $images->getName() ?>
    </li>
    <li class="list-group-item">
        <strong>Image : </strong> <?= '<img src="' . $images->getImg() . '" title="' . $images->getName() . '" width="100" height="100" />'; ?>
    </li>
</ul>

<form method="post">
    <div class="d-flex justify-content-between">
        <button class="btn btn-success">Confirmer</button>
        <a href="<?= lien("images", "liste") ?>" class="btn btn-danger">Annuler</a>
    </div>
</form>
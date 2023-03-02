<?php
//include "../../includes/functions.inc.php";

//isLogged('../login/login.html.php');
if(!$_SESSION['loggedin']) {
    header('Location: ../login/login.html.php');
exit;
}
?>
<h1>Confirmation de la suppression du projet n°<?= $textes->getId() ?> ?</h1>

<ul class="list-group">
    <li class="list-group-item">
        <strong>Texte : </strong> <?= $textes->getText() ?>
    </li>
</ul>

<form method="post">
    <div class="d-flex justify-content-between">
        <button class="btn btn-success">Confirmer</button>
        <a href="<?= lien("textes", "liste") ?>" class="btn btn-danger">Annuler</a>
    </div>
</form>
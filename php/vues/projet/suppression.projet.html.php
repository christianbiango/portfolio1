<?php
isLogged();

use const Modeles\IS_NULL;

if(!$_SESSION['loggedin']) {
    header('Location: ../login/login.html.php');
exit;
}
?>
<h1>Confirmation de la suppression du <?= ($_GET['outil'] == 'projet' ? 'projet' : rtrim($_GET['outil'], 's')); ?> n°<?= $projet->getId() ?> ?</h1>

<ul class="list-group">
    <?php $outil = $_GET['outil'];
    if($outil == 'images' || $outil == 'projet') : ?>
    <li class="list-group-item">
        <strong>Nom : </strong> <?= $projet->getName() ?>
    </li>
    <?php endif ?>
    <?php if($outil == 'images' || $outil == 'projet') : ?>
    <li class="list-group-item">
        <strong>Image : </strong> <?= '<img src="' . $projet->getImg() . '" title="' . $projet->getName() . '" width="100" height="100" />'; ?>
    </li>
    <?php endif ?>
    <?php if($outil == 'projet') : ?>
    <li class="list-group-item">
        <strong>Titre : </strong> <?= $projet->getTitre() ?>
    </li>
    <?php endif ?>
    <?php if($outil == 'textes') : ?>
        <li class="list-group-item">
        <strong>Texte : </strong> <?= $projet->getText() ?>
    </li>
    <?php endif ?>
</ul>

<form method="post">
    <div class="d-flex justify-content-between">
        <button class="btn btn-success">Confirmer</button>
        <a href="<?= lien("projet", "liste", IS_NULL, $outil) ?>" class="btn btn-danger">Annuler</a>
    </div>
</form>
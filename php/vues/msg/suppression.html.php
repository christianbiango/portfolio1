

<h1>Confirmation <?= ($_GET['methode'] === 'modifier' ? 'du traitement ' : 'de la suppression '); ?> du message n°<?= $msg->getId() ?> ?</h1>

<ul class="list-group">
    <li class="list-group-item">
        <strong>Nom : </strong> <?= $msg->getNom() ?>
    </li>
    <li class="list-group-item">
        <strong>Email : </strong> <?= $msg->getEmail() ?>
    </li>
    <li class="list-group-item">
        <strong>Message : </strong> <?= $msg->getMsg() ?>
    </li>
</ul>

<form method="post">
    <div class="d-flex justify-content-between">
        <button class="btn btn-success">Confirmer</button>
        <a href="<?= lien("msg", "liste") ?>" class="btn btn-danger">Annuler</a>
    </div>
</form>
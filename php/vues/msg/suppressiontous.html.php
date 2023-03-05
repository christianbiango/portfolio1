<?php isLogged(); ?>
<h1>Voulez-vous supprimer tous les messages traités ?</h1>

<form method="post">
    <div class="d-flex justify-content-between">
        <button class="btn btn-success">Confirmer</button>
        <a href="<?= lien("msg", "liste") ?>" class="btn btn-danger">Annuler</a>
    </div>
</form>


<?php if(isset($formCheck)) : ?>
        <div class="alert alert-danger"><?= $formCheck ?></div>
<?php endif ?>
<?php if(isset($err)) : ?>
    <div class="alert alert-danger"><?= $err ?></div>
<?php endif ?>

<h1><?= ($_GET['methode'] === 'modifier' ? 'Modifier' : 'Ajouter'); ?> un <?= ($outil == 'projet' ? 'projet' : rtrim($outil, 's')); ?></h1>

<form method="post" enctype="multipart/form-data">

    <?php if($outil == 'images' || $outil == 'projet') : ?>
    <div class="form-group">
        <label for="img">Image (formats acceptés : png, jpeg, jpg, gif, webp) </label>
        <input type="file" name="img" id="img" class="form-control" required>
    </div>
    <?php endif ?>

    <?php if($outil == 'projet') : ?>
    <div class="form-group">
        <label for="titre">Titre</label>
        <input type="text" name="titre" id="titre" class="form-control" required>
    </div>
    <?php endif ?>
    <?php if($outil == 'textes' || $outil == 'projet') : ?>
    <div class="form-group">
        <label for="txt">Texte</label>
        <input type="text" name="txt" id="txt" class="form-control" required>
    </div>
    <?php endif ?>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary"><?= ($_GET['methode'] === 'modifier' ? 'Enregistrer' : 'Confirmer' ) ?></button>
        <a href="<?= lien($outil, "liste") ?>" class="btn btn-danger">Annuler</a>
    </div>
</form>
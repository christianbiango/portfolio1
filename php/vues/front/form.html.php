<?php
if(!$_SESSION['loggedin']) {
    header('Location: ../login/login.html.php');
exit;
}
?>
<?php if(isset($erreurs)) : ?>
    <?php foreach($erreurs as $champ => $message): ?>
        <div class="alert alert-danger"><?= $champ ?> : <?= $message ?></div>
    <?php endforeach ?>
<?php endif ?>

<h1><?= ($_GET['methode'] === 'modifier' ? 'Modifier' : 'Ajouter'); ?> un projet</h1>

<form method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="img">Image</label>
        <input type="file" name="img" id="img" class="form-control">
    </div>
    <div class="form-group">
        <label for="titre">Titre</label>
        <input type="text" name="titre" id="titre" class="form-control" required
                value="<?= !empty($projet) ? $projet->getTitre() : "" ?>">
    </div>
    <div class="form-group">
        <label for="txt">Text</label>
        <input type="text" name="txt" id="txt" class="form-control" required
                value="<?= !empty($projet) ? $projet->getText() : "" ?>">
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <button type="reset" class="btn btn-secondary">Effacer</button>
    </div>
</form>
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

<h1><?= ($_GET['methode'] === 'modifier' ? 'Modifier' : 'Ajouter'); ?> une image</h1>

<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="img">Image</label>
        <input type="file" name="img" id="img-unique" class="form-control">
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <button type="reset" class="btn btn-secondary">Effacer</button>
    </div>
</form>
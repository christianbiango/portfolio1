<?php

use Controleurs\LoginControleur;
use Modeles\Entites\Login;
require_once '../../Controleurs/LoginControleur.php';
require_once '../../Modeles/Entites/Login.php';
$a = new LoginControleur;
$login = new Login;
$a->checkCurrentSession($login);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        <h1>Connexion</h1>

        <?php
        if(!empty($login->getLoginErr())){
            echo '<div class="alert alert-danger">' . $login->getLoginErr() . '</div>';
            
        } 
        ?>

        <form method="post" enctype="multipart/form-data">

            <label for="username">Identifiant</label>
            <input type="text" name="username" class="<?= (!empty($login->getUsernameErr())) ? 'is-invalid' : ''; ?>" placeholder="Entrer le nom d'utilisateur" required>
            <span class="invalid-feedback"><?= $login->getUsernameErr(); ?></span>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" class="<?= (!empty($login->getPasswordErr())) ? 'is-invalid' : ''; ?>" placeholder="Entrer le mot de passe" required>
            <span class="invalid-feedback"><?= $login->getPasswordErr(); ?></span>

            <button type="submit">Se Connecter</button>
        </form>
    </div>
</body>
</html>
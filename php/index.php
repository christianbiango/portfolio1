<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
</head>
<body>
    
    <?php

    use Controleurs\MsgControleur;
    use Modeles\Entites\Msg;
    require_once './Controleurs/MsgControleur.php';
    require_once './Modeles/Entites/Msg.php';


    $b = new MsgControleur;
    $b->ajouter();
    ?>

    <h2>Ajouter un message</h2>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nom">Votre nom</label>
            <input type="text" name="nom" id="nom" class="form-control" required
                    value="">
        </div>
        <div class="form-group">
            <label for="email">Votre email</label>
            <input type="text" name="email" id="email" class="form-control" required
                    value="">
        </div>
        <div class="form-group">
            <label for="msg">Votre message</label>
            <input type="msg" name="msg" id="msg" class="form-control">
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
    </form>
</body>
</html>
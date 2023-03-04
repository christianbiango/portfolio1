<?php
if(!$_SESSION['loggedin']) {
    header('Location: ../login/login.html.php');
exit;
}
?>

<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Titre</th>
            <th>Text</th>
            <th>Publié le</th>
            <th>Modifié le</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($front as $p): ?>
        <tr>
            <td>
                <?= $p->getId() ?>
            </td>
            <td>
                <?= '<img src="' . $p->getImg() . '" title="' . $p->getName() . '" width="100" height="100" />'; ?>
            </td>
            <td>
                <?= $p->getTitre() ?>
            </td>
            <td>
                <?= $p->getText() ?>
            </td>
            <td>
                <?= date("d/m/Y à H:i:s", strtotime($p->getDate())); ?>
            </td>
            <td>
                <?= ($p->getDateModification() == null ? 'Jamais modifié' : date("d/m/Y à H:i:s", strtotime($p->getDateModification()))); ?>
            </td>

            <td>
                <a href="<?= lien("front", "modifier", $p->getId()) ?>">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="<?= lien("front", "supprimer", $p->getid()) ?>">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>

    </tfoot>
</table>
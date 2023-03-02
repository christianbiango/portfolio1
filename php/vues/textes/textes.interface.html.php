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
            <th>Text</th>
            <th>Publié le</th>
            <th>Modifié le</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($t as $p): ?>
        <tr>
            <td>
                <?= $p->getId() ?>
            </td>
            <td>
                <?= $p->getText() ?>
            </td>
            <td>
                <?= date("d/m/Y à H:i:s", strtotime($p->getDate())); ?>
            </td>
            <td>
                <?= (empty($p->getDateModification()) ? 'Jamais modifié' : date("d/m/Y à H:i:s", strtotime($p->getDateModification()))); ?>
            </td>

            <td>
                <a href="<?= lien("textes", "modifier", $p->getId()) ?>">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="<?= lien("textes", "supprimer", $p->getid()) ?>">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>

    </tfoot>
</table>
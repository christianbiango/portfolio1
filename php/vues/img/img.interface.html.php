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
            <th>Publié le</th>
            <th>Modifié le</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($image as $i): ?>
        <tr>
            <td>
                <?= $i->getId() ?>
            </td>
            <td>
                <?= '<img src="' . $i->getImg() . '" title="' . $i->getName() . '" width="100" height="100" />'; ?>
            </td>
            <td>
                <?= date("d/m/Y à H:i:s", strtotime($i->getDate())); ?>
            </td>
            <td>
                <?= (empty($i->getDateModification()) ? 'Jamais modifié' : date("d/m/Y à H:i:s", strtotime($i->getDateModification()))); ?>
            </td>

            <td>
                <a href="<?= lien("images", "modifier", $i->getId()) ?>">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="<?= lien("images", "supprimer", $i->getid()) ?>">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>

    </tfoot>
</table>
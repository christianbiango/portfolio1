<?php
isLogged();
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
        <?php foreach($finalResult as $r): ?>
        <tr>
            <td>
                <?= $r['id'] ?>
            </td>
            <td>
                <?= $r['text'] ?>
            </td>
            <td>
                <?= date("d/m/Y à H:i:s", strtotime($r['date'])); ?>
            </td>
            <td>
                <?= (empty($r['date_modification']) ? 'Jamais modifié' : date("d/m/Y à H:i:s", strtotime($r['date_modification']))); ?>
            </td>

            <td>
                <a href="<?= lien("projet", "modifier", $r['id'], "textes"); ?>">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="<?= lien("projet", "supprimer", $r['id'], "textes"); ?>">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>

    </tfoot>
</table>
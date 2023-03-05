

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
        <?php foreach($finalResult as $r): ?>
        <tr>
            <td>
                <?= $r['id'] ?>
            </td>
            <td>
                <?= '<img src="' . $r['img'] . '" title="' . $r['name'] . '" width="100" height="100" />'; ?>
            </td>
            <td>
                <?= date("d/m/Y à H:i:s", strtotime($r['date'])); ?>
            </td>
            <td>
                <?= (empty($r['date_modification'] ? 'Jamais modifié' : date("d/m/Y à H:i:s", strtotime($r['date_modification'])))); ?>
            </td>

            <td>
                <a href="<?= lien("projet", "modifier", $r['id'], 'images') ?>">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="<?= lien("projet", "supprimer", $r['id'], 'images') ?>">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>

    </tfoot>
</table>
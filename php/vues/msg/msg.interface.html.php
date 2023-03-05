<?php isLogged(); ?> 

<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Message</th>
            <th>Publié le</th>
            <th>Traité</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($finalResult as $r): ?>
        <tr>
            <td>
                <?= /*$m->getId()*/ $r['id']; ?>
            </td>
            <td>
                <?= /*$m->getNom()*/ $r['nom']; ?>
            </td>
            <td>
                <?= $r['email']; ?>
            </td>
            <td>
                <?= $r['msg']; ?>
            </td>
            <td>
                <?= date("d/m/Y à H:i:s", strtotime($r['date'])); ?>
            </td>
            <td>
                <?= ($r['traitement'] == 0 ? '❌' : '✔ Le ' . date("d/m/Y à H:i:s", strtotime($r['traitement_date']))); ?>
                <a href='<?= lien('msg', 'modifier', $r['id'])?>'>
                    <i class="fa fa-edit"></i>
                </a>
            </td>

            <td>
                <a href="<?= lien("msg", "supprimer", $r['id']) ?>">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
    </tfoot>
</table>

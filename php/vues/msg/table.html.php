<?php
//include "../../includes/functions.inc.php";

//isLogged('../login/login.html.php');
if(!$_SESSION['loggedin']) {
    header('Location: ../login/login.html.php');
exit;
}
?>
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
        <?php foreach($result2 as $r): ?>
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
                <a href="<?= lien("msg", "supprimer", $$r['id']) ?>">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
    </tfoot>
</table>
<section>

    <nav>

        <ul class="pagination">
            <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
            <li class="page-item <?= ($pagination->getCurrentPage() == 1) ? "disabled" : "" ?>">
                <a href="<?=$_SERVER['REQUEST_URI']?>&page=<?= $pagination->getCurrentPage() - 1 ?>" class="page-link">Précédente</a>
            </li>
            <?php for($page = 1; $page <= $pagination->getPages(); $page++ ): ?>
                <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
            <li class="page-item <?= ($pagination->getCurrentPage() == $page) ? "active" : "" ?>">
                <a href="<?=$_SERVER['REQUEST_URI']?>&page=<?= $page ?>" class="page-link"><?= $page ?></a>
            </li>
            <?php endfor ?>
            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
            <li class="page-item <?= ($pagination->getCurrentPage() == $pagination->getPages()) ? "disabled" : "" ?>">
                <a href="<?=$_SERVER['REQUEST_URI']?>&page=<?= $pagination->getCurrentPage() + 1 ?>" class="page-link">Suivante</a>
            </li>
        </ul>
    </nav>
</section>
<form method="post">
    <div>
        <a class ="btn btn-danger" href='<?= lien('msg', 'supprimertous'); ?>'>Supprimer tous les messages traités</a>
    </div>
</form>
<form method="post">
    <label>Trier par date :</label>
    <select class="form-select" name="dateOption" aria-label="Default select example">
            <option value="Plus récent d'abord">Plus récent d'abord</option>
            <option value="Plus ancien d'abord" <?= ($_SESSION['dateOption'] == 'asc' ? 'selected' : '') ?>>Plus ancien d'abord</option>
    </select>
    <input type="checkbox" value="on" name="traitementOption" <?= ($_SESSION['traitementOption'] != '' ? 'checked' : '') ?>>Montrer uniquement les messages non traités</input>
    <div>
        <button type="submit" class ="btn btn-primary">Trier</button>
    </div>
</form>
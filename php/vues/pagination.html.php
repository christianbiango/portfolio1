<?php
use const Modeles\UN;

?>
<section>

    <nav aria-label="Menu secondaire" role="navigation">

        <ul class="pagination" role="menu">
            <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
            <li role="menuitem" class="page-item <?= ($pagination->getCurrentPage() == UN) ? "disabled" : "" ?>">
                <a title="Aller à la page précédente" href="<?=$_SERVER['REQUEST_URI']?>&page=<?= $pagination->getCurrentPage() - UN ?>" class="page-link">Précédente</a>
            </li>
            <?php for($page = 1; $page <= $pagination->getPages(); $page++ ): ?>
                <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
            <li role="menuitem" class="page-item <?= ($pagination->getCurrentPage() == $page) ? "active" : "" ?>">
                <a title="Aller à la page suivante" href="<?=$_SERVER['REQUEST_URI']?>&page=<?= $page ?>" class="page-link"><?= $page ?></a>
            </li>
            <?php endfor ?>
            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
            <li role="menuitem" class="page-item <?= ($pagination->getCurrentPage() == $pagination->getPages()) ? "disabled" : "" ?>">
                <a href="<?=$_SERVER['REQUEST_URI']?>&page=<?= $pagination->getCurrentPage() + UN ?>" class="page-link">Suivante</a>
            </li>
        </ul>
    </nav>
</section>

<?php if($item == 'msg') : ?>
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
<?php endif ?>
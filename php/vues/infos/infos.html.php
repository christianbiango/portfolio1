<?php isLogged(); ?>

<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Fonction</th>
            <th>Création du compte</th>
            <th>Dernière connexion</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($info as $i): ?>
        <tr>
            <td>
                <?= $i->getNom() ?>
            </td>
            <td>
                <?= $i->getPrenom() ?>
            </td>
            <td>
                <?= $i->getFonction() ?>
            </td>
            <td>
                <?= 'Le ' . date("d/m/Y à H:i:s", strtotime($i->getCreatedAt())); ?>
            </td>
            <td>
                <?= 'Le ' . date("d/m/Y à H:i:s", strtotime($i->getLastActivity())); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>

    </tfoot>
</table>
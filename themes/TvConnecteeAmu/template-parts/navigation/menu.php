<?php

$current_user = wp_get_current_user();
$model = new CodeAdeManager();
$years = $model->getCodeYear();?>

<?php if($current_user->role != "television") {?>
<div class="menu" id="myMenu">
    <?php if (!is_user_logged_in()) { ?>
    <a class="menu-item" href="<?php echo site_url('wp-login.php') ?>">CONNEXION</a>
    <?php } elseif (is_user_logged_in()) { ?>
    <div class="menu-item_dropdown menu-item">
        <button class="dropbtn">EMPLOI DU TEMPS
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-item_dropdown-content">
            <?php if (isset($years)) {
                foreach ($years as $year) { ?>
                    <a href="/emploi-du-temps/<?php echo $year['code']; ?>/"> <?php echo $year['title'] ?></a>
                <?php }
            } ?>
        </div>
    </div>
    <?php if ($current_user->role == "secretaire" || $current_user->role == "administrator") { ?>
        <div class="menu-item_dropdown menu-item">
            <button class="dropbtn">UTILISATEURS
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="menu-item_dropdown-content">
                <a href="/creation-des-comptes"> Création des comptes</a>
                <a href="/gestion-des-utilisateurs">Gestion des utilisateurs</a>
            </div>
        </div>
    <?php }  if ($current_user->role == "secretaire" || $current_user->role == "administrator" || $current_user->role == "enseignant") { ?>
        <div class="menu-item_dropdown menu-item">
            <button class="dropbtn">ALERTES
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="menu-item_dropdown-content">
                <a href="/creer-une-alerte">Créer une alerte</a>
                <a href="/gerer-les-alertes">Gestion des alertes</a>
            </div>
        </div>
                <?php if ($current_user->role == "secretaire" || $current_user->role == "administrator") { ?>
                <div class="menu-item_dropdown menu-item">
                <button class="dropbtn">INFORMATIONS
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="menu-item_dropdown-content">
                    <a href="/creer-information">Créer une information</a>
                    <a href="/gerer-les-informations">Gestion des informations</a>
                </div>
                </div>
                <?php } ?>
        <?php if ($current_user->role == "secretaire" || $current_user->role == "administrator") { ?>
            <a class="menu-item" href="/gestion-codes-ade/"> CODES ADE</a>
        <?php }
    } ?>
    <a class="menu-item" href="/mon-compte">MON COMPTE</a>
    <a class="menu-item" href="<?php echo wp_logout_url(); ?>">DÉCONNEXION</a>
        <a href="javascript:void(0);" style="font-size:30px;" class="icon" onclick="switchMenu()">&#9776;</a>
    <?php } ?>
</div>
<?php } else {?>
    <a class="ninja" href="<?php echo wp_logout_url(); ?>">DÉCONNEXION</a>
<?php } ?>
<?php

$current_user = wp_get_current_user();
$model = new CodeAdeManager();
$years = $model->getCodeYear();?>

<?php if(! in_array("television", $current_user->roles)) {?>
<div class="menu" id="myMenu">
    <?php if (!is_user_logged_in()) { ?>
    <a class="menu-item" href="<?php echo site_url('wp-login.php') ?>">CONNEXION</a>
    <?php } elseif (is_user_logged_in() && ! in_array("technician", $current_user->roles)) { ?>
    <div class="menu-item_dropdown menu-item">
        <button class="dropbtn">Emploi du temps
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
    <?php if (in_array('secretaire',$current_user->roles) || in_array('administrator',$current_user->roles)) { ?>
        <div class="menu-item_dropdown menu-item">
            <button class="dropbtn">Utilisateurs
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="menu-item_dropdown-content">
                <a href="/creation-des-comptes"> Création des comptes</a>
                <a href="/gestion-des-utilisateurs">Gestion des utilisateurs</a>
            </div>
        </div>
    <?php }  if (in_array('secretaire',$current_user->roles) || in_array('administrator',$current_user->roles) || in_array('enseignant',$current_user->roles)) { ?>
        <div class="menu-item_dropdown menu-item">
            <button class="dropbtn">Alertes
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="menu-item_dropdown-content">
                <a href="/creer-une-alerte">Créer une alerte</a>
                <a href="/gerer-les-alertes">Gestion des alertes</a>
            </div>
        </div>
                <?php if (in_array('secretaire',$current_user->roles) || in_array('administrator',$current_user->roles)) { ?>
                <div class="menu-item_dropdown menu-item">
                <button class="dropbtn">Informations
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="menu-item_dropdown-content">
                    <a href="/creer-information">Créer une information</a>
                    <a href="/gerer-les-informations">Gestion des informations</a>
                </div>
                </div>
                <?php } ?>
        <?php if (in_array('secretaire',$current_user->roles) || in_array('administrator',$current_user->roles)) { ?>
            <a class="menu-item" href="/gestion-codes-ade/"> CODES ADE</a>
        <?php }
    } ?>
    <a class="menu-item" href="/mon-compte">MON COMPTE</a>
    <a class="menu-item" href="<?php echo wp_logout_url(); ?>">DÉCONNEXION</a>
        <a href="javascript:void(0);" style="font-size:30px;" class="icon" onclick="switchMenu()">&#9776;</a>
    <?php } ?>
</div>
<?php } else {?>
    <a class="ninja" href="<?php echo wp_logout_url(); ?>">Déconnexion</a>
<?php } ?>
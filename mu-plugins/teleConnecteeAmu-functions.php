<?php
/*
Plugin Name: Télé Connectée Amu functions
Description: L'ensemble des fonctions globales du site.
Version: 0.3
License: GPL
Author: Nicolas Rohrbach
Author URI: https://wptv/
*/

//Met la bonne heure
global $wpdb;
date_default_timezone_set('Europe/Paris');
$wpdb->time_zone = 'Europe/Paris';

//error_reporting(E_ERROR);

/**
 * Enlève la barre admin de Wordpress
 */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

/**
 * Seul les admins peuvent aller sur wp-admin
 */
add_action( 'init', 'wpm_admin_redirection' );
function wpm_admin_redirection() {
    //Si on essaye d'accéder à L'administration Sans avoir le rôle administrateur
    if ( is_admin() && ! current_user_can( 'administrator' ) ) {
        // On redirige vers la page d'accueil
        wp_redirect( home_url() );
        exit;
    }
}

/**
 * Change the url for the image
 * @return mixed
 */
function my_login_logo_url() {
    return $_SERVER['HTTP_HOST'];
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

/**
 * Change the title of the image
 * @return string
 */
function my_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );
//
//add_filter( 'wp_nav_menu_items', 'add_menu', 10, 1);
//function add_menu( $items) {
//    $current_user = wp_get_current_user();
//    $model = new CodeAdeManager();
//    $years = $model->getCodeYear();
//    if (!is_user_logged_in()) {
//        $items .= '<li><a href="'. site_url('wp-login.php') .'">Connexion</a></li>';
//    }
//    elseif($current_user->role != "television" && is_user_logged_in()){
//        $items .= '
//            <li class="menu-item-type-custom menu-item-object-custom menu-item-has-children white">
//                <a href="#" title="Emploi du temps">Emploi du temps</a>
//                <button class="ast-menu-toggle" role="button" aria-expanded="true"><span class="screen-reader-text">Permutateur de Menu</span></button>
//                <ul class="sub-menu">';
//        if(isset($years)){
//            foreach ($years as $year){
//                $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a class="dropdown-item" href="'.home_url().'/emploi-du-temps/'.$year['code'].'">'.$year['title'].'</a></li>';
//            }
//        }
//        $items .= '</ul>
//        </li>';
//        if($current_user->role == "secretaire" || $current_user->role == "administrator") {
//            $items .= '
//            <li class="menu-item-type-custom menu-item-object-custom menu-item-has-children">
//                <a href="#" title="Gestion des utilisateurs">Utilisateurs</a>
//                <button class="ast-menu-toggle" role="button" aria-expanded="true"><span class="screen-reader-text">Permutateur de Menu</span></button>
//                <ul class="sub-menu">
//                    <li><a href="/creation-des-comptes"> Création des comptes</a></li>
//                    <li><a href="/gestion-des-utilisateurs">Gestion des utilisateurs</a></li>
//                </ul>
//            </li>';
//            $items .= '
//            <li><a href="/gestion-codes-ade/"> Codes ADE</a></li>';
//            if($current_user->role == "secretaire" || $current_user->role == "administrator" || $current_user->role == "enseignant") {
//                $items .= '
//                <li class="menu-item-type-custom menu-item-object-custom menu-item-has-children">
//                <a href="#" title="Gestion des alertes & informations">Alertes & informations</a>
//                <button class="ast-menu-toggle" role="button" aria-expanded="true"><span class="screen-reader-text">Permutateur de Menu</span></button>
//                <ul class="sub-menu">
//                    <li><a href="/creer-une-alerte">Créer une alerte</a></li>
//                    <li><a href="/gerer-les-alertes">Gestion des alertes</a></li>';
//                if ($current_user->role == "secretaire" || $current_user->role == "administrator") {
//                    $items .= '<li><a href="/creer-information">Créer une information</a></li>';
//                    $items .= '<li><a href="/gerer-les-informations">Gestion des informations</a></li>';
//                }
//                $items .= '
//                 </ul>
//             </li>';
//            }
//        }
//        $items .= '<li><a href="/mon-compte">Mon compte</a></li>';
//        $items .= '<li><a href="'. wp_logout_url() .'">Déconnexion</a></li>';
//    }
//    else {
//        $items .= '<li class="ninja"><a href="'. wp_logout_url() .'">Déconnexion</a></li>';
//    }
//    return $items;
//}
<?php

include_once 'inc/customizer/back-office.php';

/**
 * Used by hook: 'customize_preview_init'
 *
 * @see add_action('customize_preview_init',$func)
 */
function mytheme_customizer_live_preview()
{
    wp_enqueue_script(
        'mytheme-themecustomizer',			//Give the script an ID
        get_template_directory_uri().'/assets/js/theme-customizer.js',//Point to file
        array( 'jquery','customize-preview' ),	//Define dependencies
        '',						//Define a version (optional)
        true						//Put script in footer?
    );
}
add_action( 'customize_preview_init', 'mytheme_customizer_live_preview' );

function add_theme_scripts() {
    wp_enqueue_style('bootstrap-style', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', false, '', 'all');
    wp_enqueue_style('main-style', get_template_directory_uri().'/style.css', false, '1.0', 'all');
    wp_enqueue_style('header-style', get_template_directory_uri().'/assets/css/header.css', false, '1.0', 'all');
    wp_enqueue_style('content-style', get_template_directory_uri().'/assets/css/content.css', false, '1.0', 'all');
    wp_enqueue_style('sidebar-style', get_template_directory_uri().'/assets/css/sidebar.css', false, '1.0', 'all');
    wp_enqueue_style('footer-style', get_template_directory_uri().'/assets/css/footer.css', false, '1.0', 'all');
    //wp_enqueue_script( 'theme-jquery', get_template_directory_uri() . '/assets/js/jquery-3.3.1.min.js', array (), '', false);
    //wp_enqueue_script( 'theme-jqueryUI', get_template_directory_uri() . '/assets/js/jquery-ui.min.js', array ( 'jquery' ), '', false);
    wp_enqueue_script( 'theme-menu', get_template_directory_uri() . '/assets/js/menu.js', array ( 'jquery' ), '', false);
    wp_enqueue_script( 'theme-popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array(), '', true);
    wp_enqueue_script( 'theme-bootstrapjs', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array(), '', true);
}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );

function admin_css() {

    $admin_handle = 'admin_css';
    $admin_stylesheet = get_template_directory_uri() . '/assets/css/admin.css';

    wp_enqueue_style( $admin_handle, $admin_stylesheet );
}
add_action('admin_print_styles', 'admin_css', 11 );

/**
 * Change the image of the logo
 */
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri()."/assets/images/logo.png" ?>);
            height:65px;
            width:320px;
            background-size: 120px 120px;
            background-repeat: no-repeat;
            padding-bottom: 60px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

/**
 * Ajoute une nouvelle feuille de style pour la page de connexion
 */
function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/css/login.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );
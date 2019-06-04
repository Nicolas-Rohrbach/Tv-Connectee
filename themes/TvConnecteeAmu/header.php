<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php wp_head(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name') ?><?php if ( is_404() ) : ?> &raquo; <?php _e('Not Found') ?><?php elseif ( is_home() ) : ?> &raquo; <?php bloginfo('description') ?><?php else : ?><?php wp_title() ?><?php endif ?></title>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
    <link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <script src="/wp-content/themes/TvConnecteeAmu/assets/js/jquery-3.3.1.min.js"></script>
    <script src="/wp-content/themes/TvConnecteeAmu/assets/js/jquery-ui.min.js"></script>
    <?php if ( wp_is_mobile() ) { ?> <link rel="stylesheet" href="/wp-content/themes/TvConnecteeAmu/assets/css/mobile.css"> <?php } ?>
    <?php wp_get_archives('type=monthly&format=link'); ?> <?php //comments_popup_script(); <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="header">
    <div class="">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" rel="home">
            <img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
        </a>
    </div>
    <?php //wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
</div>
<?php include_once 'template-parts/navigation/menu.php'; ?>
<div id="page">
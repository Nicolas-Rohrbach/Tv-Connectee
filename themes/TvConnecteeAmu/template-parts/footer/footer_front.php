<?php $current_user = wp_get_current_user(); ?>
<?php if(is_user_logged_in() && ! in_array("technicien", $current_user->roles)) { ?>
<div id="footer-left">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer gauche') ) : endif; ?>
</div>
<div id="footer-right">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer droite') ) : endif; ?>
</div>

<div id="footer">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : endif; ?>
    <?php wp_footer(); ?>
</div>
<?php } ?>
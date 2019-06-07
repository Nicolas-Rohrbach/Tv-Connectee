<?php
$current_user = wp_get_current_user();
if(is_user_logged_in() && ! in_array("technicien",$current_user->roles)) { ?>
<div class="sidebar" id="sidebar-right">
    <ul>
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Colonne Droite') ) :
        endif; ?>
    </ul>
</div>
<?php } ?>
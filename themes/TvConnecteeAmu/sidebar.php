<?php if(is_user_logged_in()) { ?>
<div class="sidebar" id="sidebar-right">
    <ul>
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Colonne Droite') ) :
        endif; ?>
    </ul>
</div>
<?php } ?>
<?php get_header();
$current_user = wp_get_current_user();
if(in_array("technicien", $current_user->roles)){ ?>
<div id="content">
<?php } else { ?>
<div id="content-twocolumns">
    <?php } ?>
    <br/>
    <?php $controller = new Schedule();
    $controller->displaySchedules(); ?>
</div>
    <br/>
<?php get_sidebar(); ?>
<?php include_once 'template-parts/footer/footer_front.php'; ?>
</div>
</body>
</html>
<?php get_header(); ?>
<div id="content-twocolumns">
    <br/>
    <?php $controller = new Schedule();
    $controller->displaySchedules(); ?>
</div>
<?php get_sidebar(); ?>
<?php include_once 'template-parts/footer/footer_front.php'; ?>
</div>
</body>
</html>
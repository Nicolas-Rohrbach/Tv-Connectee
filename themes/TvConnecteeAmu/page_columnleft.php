<?php /* Template Name: Colonne Gauche */ ?>
<?php get_header(); ?>
<?php get_sidebar('left'); ?>
<div id="content-twocolumns">
    <?php if(have_posts()) :
        while(have_posts()) : the_post(); ?>
            <div class= "post" id="post-<?php the_ID(); ?>">
                <!--    <h2><a href="<?php //the_permalink(); ?>" title="<?php //the_title(); ?>"><?php //the_title(); ?></a></h2> -->
                <div class= "post_content"><?php the_content(); ?></div>
            </div>
        <?php endwhile; ?>
        <?php edit_post_link('Modifier cette page', '<p>', '</p>'); ?>
    <?php endif; ?>
</div>
<?php if ( wp_is_mobile() ) {
    get_sidebar('left');
} ?>
<?php include_once 'template-parts/footer/footer_left.php'; ?>
</body>
</html>
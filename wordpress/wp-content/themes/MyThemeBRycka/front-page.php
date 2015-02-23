<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 11/02/15
 * Time: 17:09
 */

get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
        <article class="post page" xmlns="http://www.w3.org/1999/html">

            <?php if (has_children() OR $post->post_parent > 0) { ?>
                <nav class="site-nav children-links clearfix">
                    <span class="parent-link">
                        <a href="<?php echo get_the_permalink(get_top_ancestor_id()); ?>">
                            <?php echo get_the_title(get_top_ancestor_id()); ?>
                        </a>
                    </span>
                    <ul>
                        <?php $args = array(
                            'child_of' => get_top_ancestor_id(),
                            'title_li' => ''
                        ); ?>

                        <?php wp_list_pages($args); ?>
                    </ul>
                </nav>
            <?php } ?>
            <h2><?php the_title(); ?></h2>
            <p>This is text from code!</p>
            <?php the_content(); ?>
            <p>This is text from code! - this theame is created by <?php the_author(); ?></p>
        </article>
    <?php }
} else {
    echo '<p>No Content Found</p>';
}?>

    <div class="home-columns clearfix">

        <div class="one-half">
            <?php
            // postas posts loop begins here
            $postasPosts = new WP_Query('cat=9&posts_per_page=2&orderby=date&order=ASC');
            if ($postasPosts->have_posts()) {
                while ($postasPosts->have_posts()){
                    $postasPosts->the_post();
                    ?>
                    <h2>Last two posts in 1st category</h2>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <?php the_excerpt(); ?>
                <?php
                }
            }
            wp_reset_postdata(); ?>
        </div>

        <div class="one-half last">
            <?php
            // gallery posts loop begins here
            $galleryPosts = new WP_Query('cat=10&posts_per_page=2&orderby=date&order=ASC');
            if ($galleryPosts->have_posts()) {
                while ($galleryPosts->have_posts()){
                    $galleryPosts->the_post();
                    ?>
                    <h2>Last two posts in 2nd category</h2>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <?php the_excerpt(); ?>
                    <?php
                }
            }
            wp_reset_postdata(); ?>
        </div>
    </div>

<?php get_footer(); ?>

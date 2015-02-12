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
            //                'child_of' => $post->ID,
                            'child_of' => get_top_ancestor_id(),
                            'title_li' => ''
                        ); ?>

                        <?php wp_list_pages($args); ?>
                    </ul>
                </nav>
                <?php } ?>
                <h2><?php the_title(); ?></h2>
                <?php the_content(); ?>
        </article>
    <?php }
} else {
    echo '<p>No Content Found</p>';
}

get_footer();

?>

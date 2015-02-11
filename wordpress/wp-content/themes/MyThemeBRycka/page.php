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
        <article class="post page">
            <h2><?php the_title(); ?></h2>
            <?php the_content(); ?>
        </article>
    <?php }
} else {
    echo '<p>No Content Found</p>';
}

get_footer();

?>

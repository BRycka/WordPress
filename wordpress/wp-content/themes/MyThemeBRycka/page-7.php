<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 12/02/15
 * Time: 11:00
 */

get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
        <article class="post page">

            <div class="column-container clearfix">

                <div class="title-column">
                    <h2><?php the_title(); ?></h2>
                </div>

                <div class="text-column">
                    <?php the_content(); ?>
                </div>

            </div>

        </article>
    <?php }
} else {
    echo '<p>No Content Found</p>';
}

get_footer();

?>

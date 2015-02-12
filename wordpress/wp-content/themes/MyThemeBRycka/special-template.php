<?php
/*
Template Name: Special Layout
*/
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 12/02/15
 * Time: 11:16
 */

get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
        <article class="post page">
            <h2><?php the_title(); ?></h2>

            <div class="info-box clearfix">
                <h4>Some title</h4>
                <p>
                    Some info in info box. Some info in info box. Some info in info box. Some info in info box.
                    Some info in info box. Some info in info box.
                </p>
            </div>

            <?php the_content(); ?>
        </article>
        <div class="clearfix"></div>
    <?php }
} else {
    echo '<p>No Content Found</p>';
}

get_footer();

?>

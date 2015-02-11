<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 11/02/15
 * Time: 15:34
 */

get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
        <article class="post">
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_content(); ?>
        </article>
<?php }
} else {
    echo '<p>No Content Found</p>';
}

get_footer();

?>

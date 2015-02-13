<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 13/02/15
 * Time: 11:00
 */

get_header();

if (have_posts()) { ?>
    <h2>Search results for: <?php the_search_query(); ?></h2>
    <?php while (have_posts()) {
        the_post();
        get_template_part('content');
    }
} else {
    echo '<p>No Content Found</p>';
}

get_footer();

?>

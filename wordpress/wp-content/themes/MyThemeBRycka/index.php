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
        the_post();
        get_template_part('content', get_post_format());
    }
} else {
    echo '<p>No Content Found</p>';
}

get_footer();

?>

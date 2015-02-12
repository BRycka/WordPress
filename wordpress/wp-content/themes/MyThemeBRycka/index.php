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

            <p class="post-info">
                <?php the_time('F jS, Y g:i a'); ?> | by
                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                    <?php the_author(); ?>
                </a> | Posted in
                <?php
                    $categories = get_the_category();
                    $separator = ", ";
                    $output = "";
                    if ($categories) {
                        foreach ($categories as $category) {
                            $output .= '<a href="'.get_category_link($category->term_id).'">'.$category->cat_name.'</a>'.$separator;
                        }
                        echo trim($output, $separator);
                    }
                ?>
            </p>

            <p>
                <?php echo get_the_excerpt(); ?>
                <a href="<?php the_permalink(); ?>"> more &raquo;</a>
            </p>
        </article>
<?php }
} else {
    echo '<p>No Content Found</p>';
}

get_footer();

?>

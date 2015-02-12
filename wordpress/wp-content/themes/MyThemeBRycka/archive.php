<?php
/**
 * Created by PhpStorm.
 * User: Ricardas
 * Date: 12/02/15
 * Time: 14:47
 */

get_header();

if (have_posts()) {

	?>
	<h2>
		<?php
			switch(is_archive()) {
				case is_category():
					single_cat_title();
					break;
				case is_tag():
					single_tag_title();
					break;
				case is_author():
					the_post();
					echo "Author Archives: ".get_the_author();
					rewind_posts();
					break;
				case is_day():
					echo "Daily Archives: ".get_the_date();
					break;
				case is_month():
					echo "Daily Archives: ".get_the_date("F Y");
					break;
				case is_year():
					echo "Daily Archives: ".get_the_date("Y");
					break;
				default:
					echo "Archives";
			}
		?>
	</h2>
	<?php

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

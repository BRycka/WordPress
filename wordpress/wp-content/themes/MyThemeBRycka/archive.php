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
		the_post();
		get_template_part('content', get_post_format());
	}
} else {
	echo '<p>No Content Found</p>';
}

get_footer();

?>

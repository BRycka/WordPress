<form action="<?php echo home_url('/'); ?>" method="get" role="search" id="searchform">
    <div>
        <label for="s" class="screen-reader-text">Search for:</label>
        <input type="text" value="" name="s" id="s" placeholder="<?php the_search_query(); ?>"/>
        <input type="submit" id="searchsubmit" value="Search"/>
    </div>
</form>

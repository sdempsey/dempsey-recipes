<form role="search" method="get" class="search-form" action="<?php echo home_url(); ?>">
    <label for="s" class="search-form-label screen-reader-text">Search&hellip;</label>
    <input type="search" class="search-form-field" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search&hellip;">
    <input type="submit" class="search-form-submit" value="Search">
</form>
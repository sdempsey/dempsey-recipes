<div class="meta">
    <div class="avatar"><?php echo get_avatar(get_the_author_meta('ID'), 32); ?></div>
    <div class="author"><?php the_author_posts_link() ; ?></div>
    <div class="categories"><?php echo get_the_category_list(', '); ?></div>
</div>
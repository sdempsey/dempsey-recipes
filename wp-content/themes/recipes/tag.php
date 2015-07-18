<?php get_header(); ?>

    <?php if (have_posts()) : ?>

        <h2><?php echo single_tag_title('', false); ?></h2>

        <?php while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php get_template_part('parts/meta'); ?>
            <?php get_template_part('parts/excerpt'); ?>
        </article>

        <?php endwhile; ?>

    <?php else : ?>

        <h2>No posts to display</h2>

    <?php endif; ?>

    <?php the_posts_pagination(array(
        'prev_text'          => '&larr; Previous',
        'next_text'          => 'Next &rarr;',
        'before_page_number' => '<span class="meta-nav screen-reader-text">Page</span>',
    )); ?>

<?php get_footer(); ?>
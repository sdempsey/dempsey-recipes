<?php get_header(); ?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>">
            <h1><?php the_title(); ?></h1>
            <?php get_template_part('parts/meta'); ?>
            <div class="entry"><?php the_content(); ?></div>
        </article>

        <?php comments_template('', true); ?>

    <?php endwhile; endif; ?>

<?php get_footer(); ?>
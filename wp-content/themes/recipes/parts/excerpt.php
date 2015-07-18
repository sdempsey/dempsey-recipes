<div class="excerpt entry">
    <?php
    if (strpos($post->post_content, '<!--more-->') >= 1) {
        the_content('Read more &rarr;');
    } else {
        smart_excerpt(200);
    } ?>
</div>
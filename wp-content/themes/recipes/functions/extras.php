<?php
/**
 * CUSTOM FUNCTIONS (Independent of theme template)
 */

/**
 * Custom nav menu walker
 *
 * Outputs a much simpler markup as well as useful classes to style
 * menu item children better.
 */
class Nucleus_Nav_Walker extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth = 0, $args = array()) {

        $level = $depth + 1;
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu sub-menu-level-$level\">\n";

    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {

        $default_classes = empty($item->classes) ? array () : (array) $item->classes;

        $custom_classes = (array)get_post_meta($item->ID, '_menu_item_classes', true);

        // Global class for all items
        $custom_classes[] = 'menu-item';

        // Is this a top-level menu item?
        if ($depth == 0)
            $custom_classes[] = 'menu-item-top-level';

        // Does this menu item have children?
        if (in_array('menu-item-has-children', $default_classes))
            $custom_classes[] = 'menu-item-has-children';

        // Is this menu item active? (Top level only)
        $active_classes = array('current-menu-item', 'current-menu-parent', 'current-menu-ancestor', 'current_page_item', 'current-page-parent', 'current-page-ancestor');
        if ($depth == 0 && array_intersect($default_classes, $active_classes))
            $custom_classes[] = 'menu-item-active';

        // Give menu item a class based on its level/depth
        $level = $depth + 1;
        if ($depth > 0)
            $custom_classes[] = "menu-item-level-$level";

        $classes = join(' ', $custom_classes);

        !empty($classes)
            and $classes = ' class="'. trim(esc_attr($classes)) . '"';

        $output .= "<li $classes>";

        $attributes  = '';

        !empty($item->attr_title)
            and $attributes .= ' title="'  . esc_attr($item->attr_title) .'"';
        !empty($item->target)
            and $attributes .= ' target="' . esc_attr($item->target    ) .'"';
        !empty($item->xfn)
            and $attributes .= ' rel="'    . esc_attr($item->xfn       ) .'"';
        !empty($item->url)
            and $attributes .= ' href="'   . esc_attr($item->url       ) .'"';

        $title = apply_filters('the_title', $item->title, $item->ID);

        $item_output = $args->before
            . "<a $attributes>"
            . $args->link_before
            . $title
            . '</a> '
            . $args->link_after
            . $args->after;

        $output .= apply_filters(
            'walker_nav_menu_start_el'
        ,   $item_output
        ,   $item
        ,   $depth
        ,   $args
        );
    }
}


/**
 * Custom comment walker
 */
class Nucleus_Comments extends Walker_Comment {
    var $tree_type = 'comment';
    var $db_fields = array('parent' => 'comment_parent', 'id' => 'comment_ID');

    // constructor – wrapper for the comments list
    function __construct() { ?>

        <section class="comments-list">

    <?php }

    // start_lvl – wrapper for child comments list
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $GLOBALS['comment_depth'] = $depth + 2; ?>

        <section class="child-comments comments-list">

    <?php }

    // end_lvl – closing wrapper for child comments list
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $GLOBALS['comment_depth'] = $depth + 2; ?>

        </section>

    <?php }

    // start_el – HTML for comment template
    function start_el(&$output, $comment, $depth = 0, $args = array(), $id = 0) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;
        $parent_class = (empty($args['has_children']) ? '' : 'parent');

        if ('article' == $args['style']) {
            $tag = 'article';
            $add_below = 'comment';
        } else {
            $tag = 'article';
            $add_below = 'comment';
        } ?>

        <article <?php comment_class(empty($args['has_children']) ? '' :'parent') ?> id="comment-<?php comment_ID(); ?>" itemscope itemtype="http://schema.org/Comment">
            <figure class="gravatar"><?php echo get_avatar($comment, 65, '', 'Author’s gravatar' ); ?></figure>
            <div class="comment-meta post-meta" role="complementary">
                <h2 class="comment-author">
                    <a class="comment-author-link" href="<?php comment_author_url(); ?>" itemprop="author"><?php comment_author(); ?></a>
                </h2>
                <time class="comment-meta-item" datetime="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP'); ?>" itemprop="datePublished"><?php comment_date('jS F Y'); ?>, <a href="#comment-<?php comment_ID(); ?>" itemprop="url"><?php comment_time(); ?></a></time>
                <?php edit_comment_link('<p class="comment-meta-item">Edit this comment</p>', '', ''); ?>
                <?php if ($comment->comment_approved == '0') : ?>
                <p class="comment-meta-item">Your comment is awaiting moderation.</p>
                <?php endif; ?>
            </div>
            <div class="comment-content post-content" itemprop="text">
                <?php comment_text() ?>
                <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>

    <?php }

    // end_el – closing HTML for comment template
    function end_el(&$output, $comment, $depth = 0, $args = array()) { ?>

        </article>

    <?php }

    // destructor – closing wrapper for the comments list
    function __destruct() { ?>

        </section>

    <?php }

}


/**
 * Smart Excerpt
 *
 * Returns an excerpt which is not longer than the given length and always
 * ends with a complete sentence. If first sentence is longer than length,
 * it will add the standard ellipsis… Length is the number of characters.
 *
 * Usage: <?php smart_excerpt(450); >
 *
 * http://www.distractedbysquirrels.com/blog/wordpress-improved-dynamic-excerpt
 */
function smart_excerpt($length) {
    global $post;
    $text = $post->post_excerpt;
    if ('' == $text) {
        $text = get_the_content('');
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
    }
    $text = strip_shortcodes($text);
    $text = strip_tags($text); // use ' $text = strip_tags($text,'<p><a>'); ' if you need to keep some tags
    if (empty($length)) {
        $length = 300;
    }
    $text = substr($text,0,$length);
    $excerpt = nucleus_reverse_strrchr($text, '.', 1);
    if ($excerpt) {
        echo apply_filters('the_excerpt',$excerpt);
    } else {
        echo apply_filters('the_excerpt',$text . '…');
    }
}
function nucleus_reverse_strrchr($haystack, $needle, $trail) {
    return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) + $trail) : false;
}

/**
 * Nice Search
 *
 * Redirects search results from /?s=query to /search/query/ and converts %20 to +
 * http://txfx.net/wordpress-plugins/nice-search
 */
function nucleus_search_redirect() {
    global $wp_rewrite;
    if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks()) {
        return;
    }

    $search_base = $wp_rewrite->search_base;
    if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false) {
        wp_redirect(home_url("/{$search_base}/" . urlencode(get_query_var('s'))));
        exit();
    }
}
add_action('template_redirect', 'nucleus_search_redirect');

/**
 * Add Custom Class Field to Widgets
 *
 * http://kucrut.org/add-custom-classes-to-any-widget
 */
function nucleus_widget_form_extend($instance, $widget) {
    if (!isset($instance['classes']))
        $instance['classes'] = null;
    $row = "<p>\n";
    $row .= "\t<label for='widget-{$widget->id_base}-{$widget->number}-classes'>Additional Classes <small>(separate with spaces)</small></label>\n";
    $row .= "\t<input type='text' name='widget-{$widget->id_base}[{$widget->number}][classes]' id='widget-{$widget->id_base}-{$widget->number}-classes' class='widefat' value='{$instance['classes']}'/>\n";
    $row .= "</p>\n";

    echo $row;
    return $instance;
}
add_filter('widget_form_callback', 'nucleus_widget_form_extend', 10, 2);

function nucleus_widget_update($instance, $new_instance) {
    $instance['classes'] = $new_instance['classes'];
    return $instance;
}
add_filter('widget_update_callback', 'nucleus_widget_update', 10, 2);

function nucleus_dynamic_sidebar_params($params) {
    global $wp_registered_widgets;
    $widget_id  = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_num = $widget_obj['params'][0]['number'];

    if (isset($widget_opt[$widget_num]['classes']) && !empty($widget_opt[$widget_num]['classes']))
        $params[0]['before_widget'] = preg_replace('/class="/', "class=\"{$widget_opt[$widget_num]['classes']} ", $params[0]['before_widget'], 1);

    return $params;
}
add_filter('dynamic_sidebar_params', 'nucleus_dynamic_sidebar_params');
?>
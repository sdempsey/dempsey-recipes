<?php
/**
 * CLEAN UP WORDPRESS
 */

/**
 * Clean up wp_head()
 */
function nucleus_head_cleanup() {

    // Remove unnecessary LINKs
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

    // Remove inline CSS used by Recent Comments widget
    global $wp_widget_factory;
    if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
        remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
    }


}
add_action('init', 'nucleus_head_cleanup');

/**
 * Remove the WordPress version from wp_head() and RSS feeds
 */
add_filter('the_generator', '__return_false');

/**
 * Clean up output of stylesheet LINK tags
 */
function nucleus_clean_style_tags($input) {
    preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
    $media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
    return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}
add_filter('style_loader_tag', 'nucleus_clean_style_tags');

/**
 * Clean up output of SCRIPT tags
 */
function nucleus_clean_script_tags($input) {
    $input = str_replace("type='text/javascript' ", '', $input);
    return str_replace("'", '"', $input);
}
add_filter('script_loader_tag', 'nucleus_clean_script_tags');

/**
 * Remove version query string from all styles and scripts
 */
function nucleus_remove_script_version($src) {
    return remove_query_arg('ver', $src);
}
add_filter('script_loader_src', 'nucleus_remove_script_version', 15, 1);
add_filter('style_loader_src', 'nucleus_remove_script_version', 15, 1);

/**
 * Add and remove body classes
 */
function nucleus_body_classes($classes) {
    // Add post/page slug if not present and template slug
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
        $classes[] = str_replace('.php', '', basename(get_page_template()));
    }
    // Remove unnecessary classes
    $home_id_class = 'page-id-' . get_option('page_on_front');
    $remove_classes = array(
        'page-template-default',
        $home_id_class
        );
    $classes = array_diff($classes, $remove_classes);
    return $classes;
}
add_filter('body_class', 'nucleus_body_classes');

/**
 * Wrap embedded media properly
 */
function nucleus_embed_wrap($cache) {
    return '<div class="entry-oembed">' . $cache . '</div>';
}
add_filter('embed_oembed_html', 'nucleus_embed_wrap');

/**
 * Add class="thumbnail img-thumbnail" to attachment items
 */
function roots_attachment_link_class($html) {
  $html = str_replace('<a', '<a class="thumbnail img-thumbnail"', $html);
  return $html;
}
add_filter('wp_get_attachment_link', 'roots_attachment_link_class', 10, 1);


/**
 * Limit number of post revisions kept
 */
function nucleus_revisions_number($num, $post) {
    $num = 12;
    return $num;
}
add_filter('wp_revisions_to_keep', 'nucleus_revisions_number', 10, 2);

/**
 * Remove unnecessary self-closing tags
 */
function nucleus_remove_self_closing_tags($input) {
    return str_replace(' />', '>', $input);
}
add_filter('get_avatar', 'nucleus_remove_self_closing_tags');
add_filter('comment_id_fields', 'nucleus_remove_self_closing_tags');
add_filter('post_thumbnail_html', 'nucleus_remove_self_closing_tags');

/**
 * Don't return the default description in the RSS feed if it hasn't been changed
 */
function nucleus_remove_default_description($bloginfo) {
    $default_tagline = 'Just another WordPress site';
    return ($bloginfo === $default_tagline) ? '' : $bloginfo;
}
add_filter('get_bloginfo_rss', 'nucleus_remove_default_description');

/**
 * Use root relative URLs
 * http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative
 */
function nucleus_root_relative_url($input) {
    preg_match('|https?://([^/]+)(/.*)|i', $input, $matches);
    if (!isset($matches[1]) || !isset($matches[2])) {
        return $input;
    } elseif (($matches[1] === $_SERVER['SERVER_NAME']) || $matches[1] === $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']) {
        return wp_make_link_relative($input);
    } else {
        return $input;
    }
}

function nucleus_enable_root_relative_urls() {
    return !(is_admin() || preg_match('/sitemap(_index)?\.xml/', $_SERVER['REQUEST_URI']) || in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')));
}

if (nucleus_enable_root_relative_urls()) {
    $root_rel_filters = array('bloginfo_url',
                              'the_permalink',
                              'wp_list_pages',
                              'wp_list_categories',
                              'the_content_more_link',
                              'the_tags',
                              'get_pagenum_link',
                              'get_comment_link',
                              'month_link',
                              'day_link',
                              'year_link',
                              'term_link',
                              'the_author_posts_link',
                              'script_loader_src',
                              'style_loader_src'
                              );
    nucleus_add_filters($root_rel_filters, 'nucleus_root_relative_url');
}

function nucleus_add_filters($tags, $function) {
    foreach($tags as $tag) {
        add_filter($tag, $function);
    }
}

/**
 * Disable emojis
 */
function nucleus_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles' );
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'nucleus_disable_emojis_tinymce');
}
add_action('init', 'nucleus_disable_emojis');

/**
 * Filter function used to remove the tinymce emoji plugin.
 */
function nucleus_disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}
?>
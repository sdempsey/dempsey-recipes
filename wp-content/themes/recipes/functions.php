<?php
/**
 * Setup Theme Defaults and Features
 */
require_once locate_template('/functions/setup.php');

/**
 * Administration Functions
 */
require_once locate_template('/functions/admin.php');

/**
 * Tidying-up WordPress
 */
require_once locate_template('/functions/cleanup.php');

/**
 * Widgets Setup
 */
require_once locate_template('/functions/widgets.php');

/**
 * Custom Functions (Independent of theme template)
 */
require_once locate_template('/functions/extras.php');

/**
 * Script and Stylesheet Enqueuer
 */

function nucleus_script_enqueuer() {

    // Use Google CDN's jQuery in the frontend
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js');
        add_filter('script_loader_src', 'nucleus_jquery_local_fallback', 10, 2);
    }
    
    wp_enqueue_style('style', get_stylesheet_uri());

    wp_enqueue_script('global', get_template_directory_uri() . '/scripts/site/global.js', array('jquery'), null, true);

    /**
     * Localize site URLs for use in JavaScripts
     * Usage: SiteInfo.theme_directory + '/scripts/widget.js'
     */
    $site_info = array(
        'home_url'        => get_home_url(),
        'theme_directory' => get_template_directory_uri(),
    );
    wp_localize_script('polyfills', 'SiteInfo', $site_info);
    wp_localize_script('global', 'SiteInfo', $site_info);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

}
add_action('wp_enqueue_scripts', 'nucleus_script_enqueuer');


// Register menu locations
register_nav_menus(array(
    'main_nav' => 'Main Navigation',
    'footer_nav' => 'Footer Navigation'
));

// Custom Image Sizes
//add_image_size('your_custom_size', 2000, 600, true);


// Custom Login Page
function nucleus_login_logo() {
    echo "<style>
    body.login #login h1 a {
         background: url('" . get_template_directory_uri() . "/images/wp-logo.png') no-repeat scroll center top transparent;
         width: 80px;
         height: 80px;
    }
    </style>";
}
add_filter('login_headerurl', create_function(false,"return '" . home_url() . "';"));
add_filter('login_headertitle', create_function(false,"return '" . get_bloginfo('name') . "';"));
add_action('login_head', 'nucleus_login_logo');
?>
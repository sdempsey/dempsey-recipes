<?php
/**
 * ADMINISTRATION FUNCTIONS
 */

/**
 * Add capabilities to Editor users
 */
function nucleus_add_editor_cap() {
    $role = get_role('editor');
    $role->add_cap('gform_full_access'); // Gravity Forms
    $role->add_cap('edit_theme_options'); // Menus
}
add_action('admin_init','nucleus_add_editor_cap');

/**
 * Sort by PDF in Media Library
 */
function nucleus_post_mime_types($post_mime_types) {
    $post_mime_types['application/pdf'] = array(__('PDF'), __('Manage PDF'), _n_noop('PDF <span class="count">(%s)</span>', 'PDF <span class="count">(%s)</span>'));
    return $post_mime_types;
}
add_filter('post_mime_types', 'nucleus_post_mime_types');

/**
 * Replace "Howdy" with "Logged in as" on admin bar
 */
function nucleus_replace_howdy($wp_admin_bar) {
    $my_account = $wp_admin_bar->get_node('my-account');
    $newtitle   = str_replace('Howdy,', 'Logged in as', $my_account->title);
    $wp_admin_bar->add_node(array(
        'id' => 'my-account',
        'title' => $newtitle,
    ));
}
add_filter('admin_bar_menu', 'nucleus_replace_howdy', 25);

/**
 * Custom admin footer
 */
function nucleus_admin_footer_text() {
    echo 'Copyright &copy; '. date("Y") .' '. get_bloginfo('name') .' | <a href="http://vtldesign.com" target="_blank">Made by Vital</a>';
}
add_filter('admin_footer_text', 'nucleus_admin_footer_text');

/**
 * Remove unnecessary dashboard widgets
 */
function nucleus_remove_dashboard_widgets() {
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_primary', 'dashboard', 'normal');
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}
add_action('admin_init', 'nucleus_remove_dashboard_widgets');

/**
 * Remove unused meta boxes
 */
function nucleus_remove_meta_boxes() {
    remove_meta_box('slugdiv','page','normal');       // Slug
    remove_meta_box('postcustom','page','normal');    // Custom fields (WordPress)
    remove_meta_box('postexcerpt','page','normal');   // Excerpt
    remove_meta_box('trackbacksdiv','page','normal'); // Trackbacks
    remove_meta_box('formatdiv','page','normal');     // Formats
}
add_action('admin_init','nucleus_remove_meta_boxes');

/**
 * Validate Image Uploads
 */
function nucleus_handle_upload_prefilter($file) {

    $file_dims      = getimagesize($file['tmp_name']);
    $file_width     = $file_dims[0];
    $file_height    = $file_dims[1];
    $max_dims       = array('width' => '2000', 'height' => '2000');

    $file_size      = $file['size'];
    $file_size_mb   = number_format($file_size / (1<<20), 2);

    $file_type      = $file['type'];
    $allowed_images = array('image/jpeg',
                            'image/gif',
                            'image/png');

    // Only process image files
    if (in_array($file_type, $allowed_images)) {

        // No images files larger than 1MB
        if ($file_size > 1000 * 1024)
            return array("error" => "Image size is too large. Maximum image size is 1MB. Uploaded image size is {$file_size_mb}MB.");

        // No images bigger than 2000x2000px
        elseif ($file_width > $max_dims['width'])
            return array("error" => "Image dimensions are too large. Maximum width is {$max_dims['width']}px. Uploaded image width is {$file_width}px.");

        elseif ($file_height > $max_dims['height'])
            return array("error" => "Image dimensions are too large. Maximum height is {$max_dims['height']}px. Uploaded image height is {$file_height}px.");

        else
            return $file;

    } else {

            return $file;

    }


}
add_filter('wp_handle_upload_prefilter','nucleus_handle_upload_prefilter');
?>
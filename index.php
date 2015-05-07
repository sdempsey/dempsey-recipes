<?php

/** Tells WordPress to load the WordPress theme and output it. */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/core/wp-blog-header.php' );
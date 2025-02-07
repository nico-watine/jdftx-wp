<?php

// Avada function - load Child Theme CSS
function theme_enqueue_styles() {
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 20 );

// Avada funtion - add Language ability
function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

// Remove "This site is optimized with Yoast"
add_filter( 'wpseo_debug_markers', '__return_false' );

// Remove <meta name="generator" content="WordPress 6.4.2" />
remove_action('wp_head', 'wp_generator');

// Remove the WordPress shortlink for your page/post
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Remove rsd link
remove_action('wp_head', 'rsd_link');

// Remove RSS feed links
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

// Remove the link to the Windows Live Writer manifest file
remove_action('wp_head', 'wlwmanifest_link');

// Remove the adjacent post links
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

// Remove <link rel="index" href"URL"> which is unnecessary for SEO in modern times
remove_action('wp_head', 'index_rel_link');

// Remove <link rel-"start" href="URL">, which links to the first post in chronological order
remove_action('wp_head', 'start_post_rel_link', 10, 0);

// Remove <link rel="prev"> which points to the parent post
remove_action('wp_head', 'parent_post_rel_link', 10, 0);

// Remove <link rel="https://api.w.org/" href="URL"> which is useful for API clients but not needed for JDF
remove_action('wp_head', 'rest_output_link_wp_head');

// Disabling this removes recource hints (DNS prefetching and preloadinng)
remove_action( 'wp_head', 'wp_resource_hints', 2, 99 );

// Add the new expanded viewport meta tag
function modify_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">';
}
add_action('wp_head', 'modify_viewport_meta_tag', 1);

// Set the browser theme-color
function set_browser_theme_color() {
	echo '<meta name="theme-color" content="#758ca3">';
}
add_action('wp_head', 'set_browser_theme_color', 1);

// Enable animated gifs for feature images for posts, as displayed on main Blog roll
function use_original_gif($html, $post_id, $post_thumbnail_id, $size, $attr) {
	$image_url = wp_get_attachment_url($post_thumbnail_id);
	if (strpos($image_url, '.gif') !== false) {
		return '<img src="' . esc_url($image_url) . '" class="wp-post-image">';
	}
	return $html;
}
add_filter('post_thumbnail_html', 'use_original_gif', 10, 5);

// ----------------------
// Below all are related to oEmbed, so disabling might affect my Instagram feed from loading:

// Remove 2 oEmbed discovery links from <head>:
// <link rel="alternate" title="oEmbed (JSON)" type="application/json+oembed"
// <link rel="alternate" title="oEmbed (XML)" type="text/xml+oembed"
// remove_action('wp_head', 'wp_oembed_add_discovery_links');

// No need to load oEmbed JavaScript since disabled the discovery links above
// remove_action('wp_head', 'wp_oembed_add_host_js'); // Even with "wp_oembed_add_discovery_links" active, seems like this action isn't loading anything additional

// Disabling this prevents WordPress from adding oEmbed-related routes to the REST API
// remove_action('rest_api_init', 'wp_oembed_register_route');

// Having disabled oEmbed, this ensures WordPress doesn't modify oEmbed results
// remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

// Disabling this prevents WordPress from handling oEmbed responses
// remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);

// Disabling this prevents WordPress from attempting to discover oEmbed providers automatically
// add_filter('embed_oembed_discover', '__return_false');
// ----------------------

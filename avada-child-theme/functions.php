<?php

// Avada function - load Child Theme CSS
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 20 );
function theme_enqueue_styles() {
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], wp_get_theme()->get( 'Version' ) );
}

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

// Removes the adjacent post links
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


// Additional functions to be annotated
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action( 'wp_head', 'wp_resource_hints', 2, 99 );
remove_action('rest_api_init', 'wp_oembed_register_route');
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
add_filter('embed_oembed_discover', '__return_false');

// Enable animated gifs for feature images for posts, as displayed on main Blog roll
function use_original_gif($html, $post_id, $post_thumbnail_id, $size, $attr) {
	$image_url = wp_get_attachment_url($post_thumbnail_id);
	if (strpos($image_url, '.gif') !== false) {
		return '<img src="' . esc_url($image_url) . '" class="wp-post-image">';
	}
	return $html;
}
add_filter('post_thumbnail_html', 'use_original_gif', 10, 5);

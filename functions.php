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

// Disabling this removes recource hints (DNS prefetching and preloadinng)
remove_action( 'wp_head', 'wp_resource_hints', 2, 99 );

// Add the new expanded viewport meta tag
function modify_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">';
}
add_action('wp_head', 'modify_viewport_meta_tag', 1);

// Set the browser theme-color
function set_browser_theme_color() {
	echo '<meta name="theme-color" content="#4A6473">';
}
add_action('wp_head', 'set_browser_theme_color', 1);


// Disable image compression
add_filter('wp_editor_set_quality', function($q,$m){ return 100; }, 10, 2);
add_filter('jpeg_quality', function($q){ return 100; }, 10);

// Prevent WordPress from changing output format (keep upload format).
add_filter('image_editor_output_format', function( $formats ) {
	$formats['image/webp'] = 'image/webp';
	return $formats;
});

// Enable animated gifs for feature images for posts, as displayed on main Blog roll
function use_original_gif($html, $post_id, $post_thumbnail_id, $size, $attr) {
	$image_url = wp_get_attachment_url($post_thumbnail_id);
	if (strpos($image_url, '.gif') !== false) {
		return '<img src="' . esc_url($image_url) . '" class="wp-post-image">';
	}
	return $html;
}
add_filter('post_thumbnail_html', 'use_original_gif', 10, 5);

// Disable creation of static smaller .gif uploads
function disable_gif_resizing($metadata, $attachment_id) {
	$mime = get_post_mime_type($attachment_id);
	if ($mime === 'image/gif') {
		// Prevent thumbnail generation
		$metadata['sizes'] = array();
	}
	return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'disable_gif_resizing', 10, 2);

// Enable Avada Back-end Builder for Yoast Local SEO "Location" Pages
add_action( 'admin_init', function () {
	if ( ! post_type_exists( 'wpseo_locations' ) ) {
		return;
	}
	$settings = get_option( 'fusion_builder_settings', array() );
	if ( empty( $settings['post_types'] ) || ! is_array( $settings['post_types'] ) ) {
		$settings['post_types'] = array(
			'page',
			'post',
			'avada_portfolio',
			'avada_faq',
		);
	}
	if ( ! in_array( 'wpseo_locations', $settings['post_types'], true ) ) {
		$settings['post_types'][] = 'wpseo_locations';
		update_option( 'fusion_builder_settings', $settings );
	}
} );

// Remove /locations/ from Yoast Location slugs
function remove_cpt_slug( $post_link, $post, $leavename ) {
	$options = get_option( 'wpseo_local' );
	$slug = ! empty( $options['locations_slug'] )
		? $options['locations_slug']
		: 'locations';

	if (
		! in_array( $post->post_type, array( 'wpseo_locations' ) )
		&& ( 'publish' != $post->post_status || 'draft' != $post->post_status )
	) {
		return $post_link;
	}

	$post_link = str_replace( '/' . $slug . '/', '/', $post_link );

	return $post_link;
}
add_filter( 'post_type_link', 'remove_cpt_slug', 10, 3 );

// Rewrite rules so Yoast Locations can replace original Pages
add_action( 'init', function() {
	add_rewrite_rule(
		'^houston-shop/?$',
		'index.php?post_type=wpseo_locations&name=houston-shop',
		'top'
	);
	add_rewrite_rule(
		'^jardin-de-france-round-top/?$',
		'index.php?post_type=wpseo_locations&name=jardin-de-france-round-top',
		'top'
	);
} );

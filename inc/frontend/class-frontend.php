<?php

namespace DK_Hidden_Tags\Inc\Frontend;

use DK_Hidden_Tags\Inc\Common\Settings;
use WP_Query;

/**
 * The public-facing functionality of the plugin.
 *
 * @author Dmitry Kokorin
 */
class Frontend {

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			Settings::get_instance()->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/dk-hidden-tags-frontend.css',
			array(),
			Settings::get_instance()->version,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
			Settings::get_instance()->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/dk-hidden-tags-frontend.js',
			array( 'jquery' ),
			Settings::get_instance()->version,
			false
		);

	}

	/**
	 * Search term in hidden tags
	 *
	 * @param string $term the search term.
	 *
	 * @return WP_Query
	 */
	public function search_terms( string $term ) {
		wp_reset_postdata();

		return new WP_Query(
			array(
				'fields'           => 'ids',
				//'suppress_filters' => true,
				'posts_per_page'   => - 1,
				'meta_query'       => array(
					array(
						'key'     => Settings::get_instance()->meta_key,
						'value'   => $term,
						'compare' => 'LIKE',
					),
				),
				'tax_query'        => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => array( 62 ),
						'operator' => 'NOT IN',
					),
				),
				'post_type'        => array( 'product' ),
				'orderby'          => array( 'title' => 'ASC' ),
				'limit'            => - 1,
			)
		);
	}
}

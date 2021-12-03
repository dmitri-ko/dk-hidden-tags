<?php

namespace DK_Hidden_Tags\Inc\Admin;

use DK_Hidden_Tags\Inc\Common\Settings;
use DK_Hidden_Tags\Inc\Common\Hidden_Tags_Tab;

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @author     Dmitry Kokorin
 */
class Admin {

	/**
	 * Hidden tags tab for Product tabs
	 *
	 * @var Hidden_Tags_Tab
	 */
	private Hidden_Tags_Tab $tab;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 */
	public function __construct() {
		$this->tab = new Hidden_Tags_Tab();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			Settings::get_instance()->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/dk-hidden-tags-admin.css',
			array(),
			Settings::get_instance()->version,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
			Settings::get_instance()->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/dk-hidden-tags-admin.js',
			array( 'jquery' ),
			Settings::get_instance()->version,
			false
		);

	}

	/**
	 * Get hidden tags tab
	 *
	 * @return Hidden_Tags_Tab
	 */
	public function get_tabs() {
		return $this->tab;
	}

	/**
	 * Add data field for hidden tags
	 */
	public function add_hidden_tags_data_fields() {
		$this->tab->add_hidden_tags_data_fields();
	}

	/**
	 *  Save data for hidden tags field
	 *
	 * @param int $post_id the post_id.
	 */
	public function process_hidden_tags_meta_fields_save( $post_id ) {
		$this->tab->process_hidden_tags_meta_fields_save( $post_id );
	}

	/**
	 * Add new tab to product tabs
	 *
	 * @param array $product_data_tabs the tabs array.
	 *
	 * @return array
	 */
	public function add_hidden_tags_data_tab( $product_data_tabs ) {
		return $this->tab->add_hidden_tags_data_tab( $product_data_tabs );
	}
}

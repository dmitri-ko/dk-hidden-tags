<?php

namespace DK_Hidden_Tags\Inc\Core;

use DK_Hidden_Tags as NS;
use DK_Hidden_Tags\Inc\Admin as Admin;
use DK_Hidden_Tags\Inc\Common\Settings;
use DK_Hidden_Tags\Inc\Common\Updater;
use DK_Hidden_Tags\Inc\Frontend as Frontend;

/**
 * The core plugin class.
 * Defines internationalization, admin-specific hooks, and public-facing site hooks.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @author     Your Name or Your Company
 */
class Init {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @var Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Initialize and define the core functionality of the plugin.
	 */
	public function __construct() {
		Settings::get_instance()->meta_key        = '_dk_hidden_tags';
		Settings::get_instance()->plugin_name     = NS\PLUGIN_NAME;
		Settings::get_instance()->version         = NS\PLUGIN_VERSION;
		Settings::get_instance()->plugin_basename = NS\PLUGIN_BASENAME;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Loads the following required dependencies for this plugin.
	 *
	 * - Loader - Orchestrates the hooks of the plugin.
	 * - Internationalization_I18n - Defines internationalization functionality.
	 * - Admin - Defines all hooks for the admin area.
	 * - Frontend - Defines all hooks for the public side of the site.
	 *
	 * @access    private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Internationalization_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access    private
	 */
	private function set_locale() {

		$plugin_i18n = new Internationalization_I18n( 'dk-hidden-tags' );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access    private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin\Admin();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'add_hidden_tags_data_fields' );
		$this->loader->add_action(
			'woocommerce_process_product_meta',
			$plugin_admin,
			'process_hidden_tags_meta_fields_save'
		);
		$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'add_hidden_tags_data_tab', 99, 1 );

		$plugin_updater = new Updater(
			Settings::get_instance()->plugin_name,
			Settings::get_instance()->version,
			Settings::get_instance()->plugin_basename,
			'http://dmitriko.ru/wp/wp-content/uploads/updater/' . Settings::get_instance()->plugin_name . '/info.json'
		);
		$this->loader->add_action( 'plugins_api', $plugin_updater, 'view_plugin_info', 20, 3 );
		$this->loader->add_action( 'site_transient_update_plugins', $plugin_updater, 'push_update' );
		$this->loader->add_action( 'upgrader_process_complete', $plugin_updater, 'purge', 10, 2 );
	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access    private
	 */
	private function define_public_hooks() {

		$plugin_public = new Frontend\Frontend();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'dk_search_hidden', $plugin_public, 'search_terms' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}

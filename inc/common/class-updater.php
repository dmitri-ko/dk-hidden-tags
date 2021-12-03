<?php
/**
 * Class for plugin updater
 *
 * @link       http://example.com
 * @since      1.0.2
 *
 * @author     Dmitry Kokorin<dmitri.kokorin@gmail.com>
 *
 * @package    Cart_Persistence
 * @subpackage Cart_Persistence/core
 */

namespace DK_Hidden_Tags\Inc\Common;

use stdClass;

/**
 * The updater functionality class.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 *
 * @author     Dmitry Kokorin
 */
class Updater {

	/**
	 * The plugin name
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The plugin version
	 *
	 * @access private
	 * @var string
	 */
	private $version;

	/**
	 * The plugin base name
	 *
	 * @access private
	 * @var string
	 */
	private $plugin_basename;

	/**
	 * The plugin cache key
	 *
	 * @access private
	 * @var string
	 */
	private $plugin_cache_key;

	/**
	 * The plugin info URL
	 *
	 * @access private
	 * @var string
	 */
	private $plugin_info_url;

	/**
	 * If the plugin cache available
	 *
	 * @access private
	 * @var bool
	 */
	private $is_with_cache;

	/**
	 * Constuct Updateer object
	 *
	 * @access public
	 *
	 * @param string $plugin_name     the plugin name.
	 * @param string $version         the plugin version.
	 * @param string $plugin_basename the plugin base name.
	 * @param string $plugin_info_url the plugin info URL.
	 * @param bool   $is_with_cache   if the plugin with cache.
	 */
	public function __construct( $plugin_name, $version, $plugin_basename, $plugin_info_url, $is_with_cache = true ) {
		$this->plugin_name      = $plugin_name;
		$this->version          = $version;
		$this->plugin_basename  = $plugin_basename;
		$this->plugin_cache_key = $plugin_name . '_updater';
		$this->plugin_info_url  = $plugin_info_url;
		$this->is_with_cache    = $is_with_cache;
	}

	/**
	 * Push the plugin update.
	 *
	 * @access public
	 *
	 * @param object $transient the transient object.
	 *
	 * @return object
	 */
	public function push_update( $transient ) {

		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$remote = $this->get_remote_config();

		if (
			$remote
			&& version_compare( $this->version, $remote->version, '<' )
			&& version_compare( $remote->requires, get_bloginfo( 'version' ), '<' )
			&& version_compare( $remote->requires_php, PHP_VERSION, '<' )
		) {

			$res                                 = new stdClass();
			$res->slug                           = $remote->slug;
			$res->plugin                         = $this->plugin_basename;
			$res->new_version                    = $remote->version;
			$res->tested                         = $remote->tested;
			$res->package                        = $remote->download_url;
			$transient->response[ $res->plugin ] = $res;
		}

		return $transient;

	}

	/**
	 * Get remote configuration file in JSON format
	 *
	 * @access protected
	 * @return false|mixed
	 */
	protected function get_remote_config() {

		$remote = get_transient( $this->plugin_cache_key );

		if ( ! $this->is_with_cache || false === $remote ) {
			$remote = wp_remote_get(
				$this->plugin_info_url,
				array(
					'timeout'   => 10,
					'headers'   => array(
						'Accept' => 'application/json',
					),
					'sslverify' => false,
				)
			);
			if (
				is_wp_error( $remote )
				|| 200 !== wp_remote_retrieve_response_code( $remote )
				|| empty( wp_remote_retrieve_body( $remote ) )
			) {
				return false;
			}
			if ( $this->is_with_cache ) {
				set_transient( $this->plugin_cache_key, $remote, DAY_IN_SECONDS );
			}
		}

		return json_decode( wp_remote_retrieve_body( $remote ) );
	}

	/**
	 * Gets plugin info from remote repository.
	 *
	 * @access public
	 *
	 * @param StdClass $res    the response object.
	 * @param string   $action the action.
	 * @param StdClass $args   the parameters.
	 *
	 * @return false|stdClass
	 */
	public function view_plugin_info( $res, $action, $args ) {

		if ( 'plugin_information' !== $action ) {
			return false;
		}

		if ( $this->plugin_name !== $args->slug ) {
			return false;
		}

		$remote = $this->get_remote_config();

		if ( $remote ) {
			$res                 = new stdClass();
			$res->name           = $remote->name;
			$res->slug           = $remote->slug;
			$res->author         = $remote->author;
			$res->author_profile = $remote->author_profile;
			$res->version        = $remote->version;
			$res->tested         = $remote->tested;
			$res->requires       = $remote->requires;
			$res->requires_php   = $remote->requires_php;
			$res->download_link  = $remote->download_url;
			$res->trunk          = $remote->download_url;
			$res->last_updated   = $remote->last_updated;
			$res->sections       = array(
				'description'  => $remote->sections->description,
				'installation' => $remote->sections->installation,
				'changelog'    => $remote->sections->changelog,
			);
			if ( ! empty( $remote->sections->screenshots ) ) {
				$res->sections['screenshots'] = $remote->sections->screenshots;
			}
			$res->banners = array(
				'low'  => $remote->banners->low,
				'high' => $remote->banners->high,
			);

			return $res;
		} else {
			return false;
		}
	}

	/**
	 * Reset cache
	 *
	 * @access public
	 *
	 * @param StdClass $upgrader_object the updater object.
	 * @param array    $options         the options.
	 */
	public function purge( $upgrader_object, $options ) {

		if ( $this->is_with_cache && 'update' === $options['action'] && 'plugin' === $options['type'] ) {
			delete_transient( $this->plugin_cache_key );
		}

	}
}

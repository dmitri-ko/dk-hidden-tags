<?php

namespace DK_Hidden_Tags\Inc\Common;

/**
 * Settings class for the plugin
 */
class Settings {
	/**
	 *  The instance storage
	 *
	 * @var array
	 */
	private static $instances = array();

	/**
	 * The settings storage
	 *
	 * @var array
	 */
	private $storage = array();

	/**
	 * Private constructor
	 */
	protected function __construct() {
	}

	/**
	 * Get the instance
	 *
	 * @return Settings
	 */
	public static function get_instance(): Settings {
		$cls = static::class;
		if ( ! isset( self::$instances[ $cls ] ) ) {
			self::$instances[ $cls ] = new static();
		}

		return self::$instances[ $cls ];
	}

	/**
	 * Magic wakeup
	 *
	 * @throws \Exception The message.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize a singleton.' );
	}

	/**
	 * Get option
	 *
	 * @param string $name the option name.
	 *
	 * @return mixed|string
	 */
	public function __get( string $name ) {
		return $this->storage[ $name ] ?? '';
	}

	/**
	 * Set option
	 *
	 * @param string $name  the option name.
	 * @param mixed  $value the option value.
	 */
	public function __set( string $name, $value ) {
		$this->storage[ $name ] = $value;
	}

	/**
	 * Private clone method
	 */
	protected function __clone() {
	}


}

<?php

namespace DK_Hidden_Tags\Inc\Common;

/**
 * WP Meta data class
 */
class Meta {
	/**
	 *  Meta name
	 *
	 * @var string
	 */
	private $name;
	/**
	 * Meta description
	 *
	 * @var string
	 */
	private $description;
	/**
	 * Post type for meta
	 *
	 * @var string
	 */
	private $posttype;
	/**
	 * Meta type
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Constructor
	 *
	 * @param string $name        the meta name.
	 * @param string $description the meta description.
	 * @param string $posttype    the meta post type.
	 * @param string $type        the meta type.
	 */
	public function __construct(
		string $name,
		string $description,
		string $posttype = 'post',
		string $type = 'string'
	) {
		$this->name        = $name;
		$this->description = $description;
		$this->posttype    = $posttype;
		$this->type        = $type;

		$this->register_meta();
	}

	/**
	 * Register meta type
	 */
	protected function register_meta() {
		register_post_meta(
			$this->posttype,
			$this->name,
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => $this->type,
				'description'   => $this->description,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Get meta value
	 *
	 * @param int $id the post id.
	 *
	 * @return mixed
	 */
	public function get( int $id ) {
		return get_post_meta( $id, $this->name, true );
	}

	/**
	 * Set meta value
	 *
	 * @param int   $id    the post id.
	 * @param mixed $value the value.
	 */
	public function set( int $id, $value ) {
		update_post_meta( $id, $this->name, $value );
	}

	/**
	 * Get the meta name
	 *
	 * @return mixed
	 */
	public function get_name() {
		return $this->name;
	}
}

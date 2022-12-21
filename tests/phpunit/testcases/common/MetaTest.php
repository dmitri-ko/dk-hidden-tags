<?php


use Brain\Monkey\Functions;
use DK_Hidden_Tags\Inc\Common\Meta;

class MetaTest extends PluginTestCase {
	private $storage;
	private $meta;

	public function setUp(): void {
		parent::setUp();

		Functions\stubs(
			array(
				'register_post_meta' => true,
				'update_post_meta'   => function ( $id, $name, $value ) {
					$this->storage[ $id ] = array( $name => $value );
				},
				'get_post_meta'      => function ( $id, $name, $single ) {
					return isset( $this->storage[ $id ] ) ? $this->storage[ $id ][ $name ] : '';
				},
			)
		);

		$this->meta = new Meta( '_meta_key1', 'Meta key One' );
	}


	public function testGetNameReturnCorrectName() {
		$this->assertEquals( '_meta_key1', $this->meta->get_name() );
	}


	public function testGetNotEmpty() {
		$this->storage[2] = array( '_meta_key1' => 'second' );

		$this->assertEquals( 'second', $this->meta->get( 2 ) );

		$this->storage = array();
	}

	public function testGetEmpty() {
		$this->storage[2] = array( '_meta_key1' => 'second' );

		$this->assertEmpty( $this->meta->get( 1 ) );

		$this->storage = array();
	}

	public function testSetNotEmpty() {
		$this->meta->set( 1, 'first' );

		$this->assertNotEmpty( $this->storage );

		$this->storage = array();
	}

	public function testSetCorrectKey() {
		$this->meta->set( 1, 'first' );

		$this->assertArrayHasKey( 1, $this->storage );

		$this->storage = array();
	}

	public function testSetTheCorrectValue() {
		$this->meta->set( 1, 'first' );

		$this->assertEquals( 'first', $this->storage[1]['_meta_key1'] );

		$this->storage = array();
	}

	public function testSetExactlyOneValue() {
		$this->meta->set( 1, 'first' );

		$this->assertEquals( 1, count( $this->storage ) );

		$this->storage = array();
	}

	public function testGetReturnsWhatWasSet() {
		$this->storage = array();

		$this->meta->set( 1, 'first' );

		$this->assertEquals( 'first', $this->meta->get( 1 ) );

		$this->storage = array();
	}

	public function testConstructCreatesObject() {
		// Assert
		$this->assertNotEmpty( $this->meta );
	}
}

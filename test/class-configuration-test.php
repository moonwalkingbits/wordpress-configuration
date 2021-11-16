<?php

namespace Moonwalking_Bits\Configuration;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Moonwalking_Bits\Configuration\Configuration
 */
class Configuration_Test extends TestCase {

	private Configuration $configuration;

	/**
	 * @before
	 */
	public function set_up(): void {
		$this->configuration = new Configuration();
	}

	/**
	 * @test
	 */
	public function should_implement_configuration_interface(): void {
		$this->assertContains( Configuration_Interface::class, class_implements( $this->configuration::class ) );
	}

	/**
	 * @test
	 */
	public function should_be_empty_by_default(): void {
		$this->assertEmpty( $this->configuration->all() );
	}

	/**
	 * @test
	 */
	public function should_accept_store(): void {
		$store = array( 'key' => 'value' );
		$configuration = new Configuration( $store );

		$this->assertEquals( $store, $configuration->all() );
	}

	/**
	 * @test
	 */
	public function should_return_all_values(): void {
		$this->configuration->set( 'key', 'value' );

		$this->assertEquals( array( 'key' => 'value' ), $this->configuration->all() );
	}

	/**
	 * @test
	 */
	public function should_determine_if_key_has_value(): void {
		$this->assertFalse( $this->configuration->has( 'key' ) );

		$this->configuration->set( 'key', 'value' );

		$this->assertTrue( $this->configuration->has( 'key' ) );
	}

	/**
	 * @test
	 */
	public function should_determine_if_key_has_nested_value(): void {
		$this->assertFalse( $this->configuration->has( 'nested.key' ) );

		$this->configuration->set( 'nested', array( 'key' => 'value' ) );

		$this->assertTrue( $this->configuration->has( 'nested.key' ) );
	}

	/**
	 * @test
	 */
	public function should_return_null_if_key_not_found(): void {
		$this->assertNull( $this->configuration->get( 'key' ) );
	}

	/**
	 * @test
	 */
	public function should_return_null_if_nested_key_not_found(): void {
		$this->assertNull( $this->configuration->get( 'nested.key' ) );
	}

	/**
	 * @test
	 */
	public function should_return_default_value_if_provided(): void {
		$this->assertEquals( 'test', $this->configuration->get( 'key', 'test' ) );
	}

	/**
	 * @test
	 */
	public function should_return_value_for_key(): void {
		$configuration = new Configuration( array( 'key' => 'value' ) );

		$this->assertEquals( 'value', $configuration->get( 'key' ) );
	}

	/**
	 * @test
	 */
	public function should_return_value_for_nested_key(): void {
		$configuration = new Configuration( array( 'nested' => array( 'key' => 'value' ) ) );

		$this->assertEquals( 'value', $configuration->get( 'nested.key' ) );
	}

	/**
	 * @test
	 */
	public function should_set_value_for_key(): void {
		$this->configuration->set( 'key', 'value' );

		$this->assertEquals( 'value', $this->configuration->get( 'key' ) );
	}

	/**
	 * @test
	 */
	public function should_set_value_for_nested_key(): void {
		$this->configuration->set( 'nested.key', 'value' );

		$this->assertEquals( array( 'key' => 'value' ), $this->configuration->get( 'nested' ) );
	}

	/**
	 * @test
	 */
	public function should_remove_value_for_key(): void {
		$configuration = new Configuration( array( 'key' => 'value' ) );
		$configuration->remove( 'key' );

		$this->assertFalse( $configuration->has( 'key' ) );

		// Make sure no exception is thrown if the key doesn't exist.
		$this->configuration->remove( 'non_existing_key' );
	}

	/**
	 * @test
	 */
	public function should_remove_value_for_nested_key(): void {
		$configuration = new Configuration( array( 'nested' => array( 'key' => 'value' ) ) );
		$configuration->remove( 'nested.key' );

		$this->assertFalse( $configuration->has( 'nested.key' ) );
		$this->assertIsArray( $configuration->get( 'nested' ) );
		$this->assertEmpty( $configuration->get( 'nested' ) );

		// Make sure no exception is thrown if the key doesn't exist.
		$this->configuration->remove( 'nested.non_existing_key' );
	}

	/**
	 * @test
	 */
	public function should_clear_all_values(): void {
		$configuration = new Configuration( array( 'key' => 'value' ) );
		$configuration->clear();

		$this->assertEmpty( $configuration->all() );
	}

	/**
	 * @test
	 */
	public function should_merge_configuration(): void {
		$configuration = new Configuration( array( 'key' => 'value' ) );
		$another_configuration = new Configuration( array( 'another_key' => 'another_value' ) );

		$configuration->merge( $another_configuration );

		$this->assertEquals(
			array(
				'key' => 'value',
				'another_key' => 'another_value',
			),
			$configuration->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_configuration_at_key(): void {
		$configuration = new Configuration( array( 'key' => 'value' ) );
		$another_configuration = new Configuration( array( 'another_key' => 'another_value' ) );

		$configuration->merge( $another_configuration, 'nested' );

		$this->assertEquals(
			array(
				'key' => 'value',
				'nested' => array(
					'another_key' => 'another_value',
				),
			),
			$configuration->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_configuration_at_nested_key(): void {
		$configuration = new Configuration( array( 'key' => 'value' ) );
		$another_configuration = new Configuration( array( 'another_key' => 'another_value' ) );

		$configuration->merge( $another_configuration, 'nested.section' );

		$this->assertEquals(
			array(
				'key' => 'value',
				'nested' => array(
					'section' => array(
						'another_key' => 'another_value',
					),
				),
			),
			$configuration->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_configuration_at_existing_key(): void {
		$configuration = new Configuration( array( 'nested' => array( 'key' => 'value' ) ) );
		$another_configuration = new Configuration( array( 'another_key' => 'another_value' ) );

		$configuration->merge( $another_configuration, 'nested.section' );

		$this->assertEquals(
			array(
				'nested' => array(
					'key' => 'value',
					'section' => array(
						'another_key' => 'another_value',
					),
				),
			),
			$configuration->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_associative_arrays(): void {
		$configuration = new Configuration( array( 'nested' => array( 'key' => 'value' ) ) );
		$another_configuration = new Configuration( array( 'nested' => array( 'another_key' => 'another_value' ) ) );

		$configuration->merge( $another_configuration );

		$this->assertEquals(
			array(
				'nested' => array(
					'key' => 'value',
					'another_key' => 'another_value',
				),
			),
			$configuration->all()
		);
	}

	/**
	 * @test
	 */
	public function should_replace_indexed_arrays(): void {
		$configuration = new Configuration( array( 'indexed' => array( 1, 2, 3 ) ) );
		$another_configuration = new Configuration( array( 'indexed' => array( 1, 2 ) ) );

		$configuration->merge( $another_configuration );

		$this->assertEquals( array( 'indexed' => array( 1, 2 ) ), $configuration->all() );
	}

	/**
	 * @test
	 */
	public function should_merge_indexed_arrays(): void {
		$configuration = new Configuration( array( 'indexed' => array( 1, 2, 3 ) ) );
		$another_configuration = new Configuration( array( 'indexed' => array( 3, 4, 5 ) ) );

		$configuration->merge( $another_configuration, null, Merge_Strategy::from( Merge_Strategy::MERGE_INDEXED ) );

		$this->assertEquals( array( 'indexed' => array( 1, 2, 3, 4, 5 ) ), $configuration->all() );
	}
}

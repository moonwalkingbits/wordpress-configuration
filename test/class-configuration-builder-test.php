<?php

namespace Moonwalking_Bits\Configuration;

use PHPUnit\Framework\TestCase;
use Moonwalking_Bits\Configuration\Configuration_Source\Array_Configuration_Source;

/**
 * @coversDefaultClass \Moonwalking_Bits\Configuration\Configuration_Builder
 */
class Configuration_Builder_Test extends TestCase {

	private Configuration_Builder $configuration_builder;

	/**
	 * @before
	 */
	public function set_up(): void {
		$this->configuration_builder = new Configuration_Builder();
	}

	/**
	 * @test
	 */
	public function should_produce_empty_configuration_object_if_no_sources(): void {
		$this->assertEmpty( $this->configuration_builder->build()->all() );
	}

	/**
	 * @test
	 */
	public function should_accept_configuration_source(): void {
		$content = array( 'key' => 'value' );

		$this->assertEquals(
			$content,
			$this->configuration_builder
				->add_configuration_source( new Array_Configuration_Source( $content ) )
				->build()
				->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_configuration_source_contents(): void {
		$source_1 = new Array_Configuration_Source( array( 'key' => 'value' ) );
		$source_2 = new Array_Configuration_Source( array( 'another_key' => 'another_value' ) );

		$this->assertEquals(
			array(
				'key' => 'value',
				'another_key' => 'another_value',
			),
			$this->configuration_builder
				->add_configuration_source( $source_1 )
				->add_configuration_source( $source_2 )
				->build()
				->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_configuration_source_contents_recursively(): void {
		$source_1 = new Array_Configuration_Source( array( 'nested' => array( 'key' => 'value' ) ) );
		$source_2 = new Array_Configuration_Source( array( 'nested' => array( 'another_key' => 'another_value' ) ) );

		$this->assertEquals(
			array(
				'nested' => array(
					'key' => 'value',
					'another_key' => 'another_value',
				),
			),
			$this->configuration_builder
				->add_configuration_source( $source_1 )
				->add_configuration_source( $source_2 )
				->build()
				->all()
		);
	}

	/**
	 * @test
	 */
	public function should_replace_indexed_arrays_in_configuration_source_contents(): void {
		$source_1 = new Array_Configuration_Source( array( 'key' => array( 1, 2, 3 ) ) );
		$source_2 = new Array_Configuration_Source( array( 'key' => array( 1, 2 ) ) );

		$this->assertEquals(
			array(
				'key' => array( 1, 2 ),
			),
			$this->configuration_builder
				->add_configuration_source( $source_1 )
				->add_configuration_source( $source_2 )
				->build()
				->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_indexed_arrays_in_configuration_source_contents(): void {
		$source_1 = new Array_Configuration_Source( array( 'key' => array( 1, 2, 3 ) ) );
		$source_2 = new Array_Configuration_Source( array( 'key' => array( 3, 4, 5 ) ) );

		$this->assertEquals(
			array(
				'key' => array( 1, 2, 3, 4, 5 ),
			),
			$this->configuration_builder
				->add_configuration_source( $source_1 )
				->add_configuration_source( $source_2 )
				->build( Merge_Strategy::from( Merge_Strategy::MERGE_INDEXED ) )
				->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_configuration_source_contents_at_key(): void {
		$source_1 = new Array_Configuration_Source( array( 'key' => 'value' ) );
		$source_2 = new Array_Configuration_Source( array( 'another_key' => 'another_value' ) );

		$this->assertEquals(
			array(
				'key' => 'value',
				'nested' => array(
					'another_key' => 'another_value',
				),
			),
			$this->configuration_builder
				->add_configuration_source( $source_1 )
				->add_configuration_source( $source_2, 'nested' )
				->build()
				->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_configuration_source_contents_at_nested_key(): void {
		$source_1 = new Array_Configuration_Source( array( 'key' => 'value' ) );
		$source_2 = new Array_Configuration_Source( array( 'another_key' => 'another_value' ) );

		$this->assertEquals(
			array(
				'key' => 'value',
				'nested' => array(
					'section' => array(
						'another_key' => 'another_value',
					),
				),
			),
			$this->configuration_builder
				->add_configuration_source( $source_1 )
				->add_configuration_source( $source_2, 'nested.section' )
				->build()
				->all()
		);
	}

	/**
	 * @test
	 */
	public function should_merge_configuration_source_contents_at_existing_key(): void {
		$source_1 = new Array_Configuration_Source( array( 'nested' => array( 'key' => 'value' ) ) );
		$source_2 = new Array_Configuration_Source( array( 'another_key' => 'another_value' ) );

		$this->assertEquals(
			array(
				'nested' => array(
					'key' => 'value',
					'another_key' => 'another_value',
				),
			),
			$this->configuration_builder
				->add_configuration_source( $source_1 )
				->add_configuration_source( $source_2, 'nested' )
				->build()
				->all()
		);
	}
}

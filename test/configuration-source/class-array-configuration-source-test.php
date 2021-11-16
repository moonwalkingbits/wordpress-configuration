<?php

namespace Moonwalking_Bits\Configuration\Configuration_Source;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Moonwalking_Bits\Configuration\Array_Configuration_Source
 */
class Array_Configuration_Source_Test extends TestCase {

	/**
	 * @test
	 */
	public function should_return_content(): void {
		$content = array( 'key' => 'value' );
		$configuration_source = new Array_Configuration_Source( $content );

		$this->assertEquals( $content, $configuration_source->fetch() );
	}
}

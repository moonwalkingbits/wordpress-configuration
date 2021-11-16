<?php

namespace Moonwalking_Bits\Configuration\Configuration_Source;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @coversDefaultClass \Moonwalking_Bits\Configuration\Abstract_File_Configuration_Source
 */
class Abstract_File_Configuration_Source_Test extends TestCase {

	/**
	 * @test
	 */
	public function should_fetch_file_content(): void {
		$configuration_source = new Fixtures\Test_File_Configuration_Source( __DIR__ . '/fixtures/content.txt' );
		$content = 'content';

		$this->assertEquals( compact( 'content' ), $configuration_source->fetch() );
	}

	/**
	 * @test
	 */
	public function should_throw_if_file_not_readable(): void {
		$this->expectException( RuntimeException::class );

		( new Fixtures\Test_File_Configuration_Source( __DIR__ . '/fixtures/not-found.txt' ) )->fetch();
	}
}

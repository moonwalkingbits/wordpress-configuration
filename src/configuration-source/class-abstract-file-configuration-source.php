<?php
/**
 * Configuration: File configuration source
 *
 * @package Moonwalking_Bits\Configuration\Configuration_Source
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Configuration\Configuration_Source;

use RuntimeException;

/**
 * File configuration source implementation.
 *
 * This is an abstract configuration source that is intended to be extended by
 * any configuration source loading configuration from files.
 *
 * @since 0.1.0
 */
abstract class Abstract_File_Configuration_Source implements Configuration_Source_Interface {

	/**
	 * The file to load the content from.
	 *
	 * @var string
	 */
	private string $file;

	/**
	 * Creates a new configuration source instance.
	 *
	 * @since 0.1.0
	 * @param string $file The file to load the content from.
	 */
	public function __construct( string $file ) {
		$this->file = $file;
	}

	/**
	 * Fetches the file content.
	 *
	 * @SuppressWarnings(PHPMD.ErrorControlOperator)
	 * @since 0.1.0
	 * @return string The configuration source content.
	 * @throws \RuntimeException When unable to read file.
	 */
	protected function fetch_content(): string {
		// phpcs:ignore WordPress
		$file_content = @file_get_contents( $this->file );

		if ( false === $file_content ) {
			throw new RuntimeException( "Unable to fetch content from file: {$this->file}" );
		}

		return $file_content;
	}
}

<?php
/**
 * Configuration: Array configuration source
 *
 * @package Moonwalking_Bits\Configuration\Configuration_Source
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Configuration\Configuration_Source;

/**
 * Array configuration source implementation.
 *
 * @since 0.1.0
 */
class Array_Configuration_Source implements Configuration_Source_Interface {

	/**
	 * The configuration source content.
	 *
	 * @var array
	 */
	private array $content;

	/**
	 * Creates a new configuration source instance.
	 *
	 * @since 0.1.0
	 * @param array $content The configuration source content.
	 */
	public function __construct( array $content ) {
		$this->content = $content;
	}

	/**
	 * Fetches the configuration source content.
	 *
	 * @since 0.1.0
	 * @return array The configuration source content.
	 */
	public function fetch(): array {
		return $this->content;
	}
}

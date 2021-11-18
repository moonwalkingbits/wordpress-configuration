<?php
/**
 * Configuration: Configuration source interface
 *
 * @package Moonwalking_Bits\Configuration\Configuration_Source
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Configuration\Configuration_Source;

/**
 * Represents a configuration source.
 *
 * @since 0.1.0
 */
interface Configuration_Source_Interface {

	/**
	 * Fetches the configuration source content.
	 *
	 * @since 0.1.0
	 * @return array The configuration source content.
	 * @throws \RuntimeException If unable to produce the content.
	 */
	public function fetch(): array;
}

<?php
/**
 * Configuration: Configuration interface
 *
 * @package Moonwalking_Bits\Configuration
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Configuration;

/**
 * Represents a configuration object.
 *
 * @since 0.1.0
 */
interface Configuration_Interface {

	/**
	 * Retrieves all available settings.
	 *
	 * @return array All available settings.
	 */
	public function all(): array;

	/**
	 * Determines if the given key is present in the configuration object.
	 *
	 * @param string $key Configuration key to look for.
	 * @return bool True if the given key is present in the configuration object.
	 */
	public function has( string $key ): bool;

	/**
	 * Retrieves the value of the given key.
	 *
	 * @param string $key Configuration key to look for.
	 * @param mixed  $default An optional default value to return if the key is not found.
	 * @return mixed Value of the given key if found or the given default value.
	 */
	public function get( string $key, $default = null );

	/**
	 * Sets the value of the given key.
	 *
	 * @param string $key Configuration key to set value of.
	 * @param mixed  $value Configuration value.
	 */
	public function set( string $key, $value ): void;

	/**
	 * Removes the given key from the configuration object.
	 *
	 * @param string $key Configuration key to remove.
	 */
	public function remove( string $key ): void;

	/**
	 * Clears all settings from the configuration object.
	 */
	public function clear(): void;

	/**
	 * Merges in the given configuration object at the given key.
	 *
	 * @param \Moonwalking_Bits\Configuration\Configuration_Interface    $configuration Configuration object to merge.
	 * @param string|null                                                $key Configuration key to merge the object at.
	 * @param \Moonwalking_Bits\Configuration\Merge_Strategy|string|null $strategy Strategy to use when merging.
	 */
	public function merge( Configuration_Interface $configuration, ?string $key = null, $strategy = null ): void;
}

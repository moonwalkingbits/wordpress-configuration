<?php
/**
 * Configuration: Configuration implementation
 *
 * @package Moonwalking_Bits\Configuration
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Configuration;

/**
 * A configuration object implementation.
 *
 * @since 0.1.0
 */
class Configuration implements Configuration_Interface {

	use Configuration_Trait;

	/**
	 * The store holding all configuration variables.
	 *
	 * @var array
	 */
	private array $store;

	/**
	 * Creates a new configuration object instance.
	 *
	 * @param array $store The store holding all configuration variables.
	 */
	public function __construct( array $store = array() ) {
		$this->store = $store;
	}

	/**
	 * Retrieves all available settings.
	 *
	 * @return array All available settings.
	 */
	public function all(): array {
		return $this->store;
	}

	/**
	 * Determines if the given key is present in the configuration object.
	 *
	 * @param string $key Configuration key to look for.
	 * @return bool True if the given key is present in the configuration object.
	 */
	public function has( string $key ): bool {
		$store = $this->store;

		foreach ( explode( '.', $key ) as $section ) {
			if ( ! is_array( $store ) || ! array_key_exists( $section, $store ) ) {
				return false;
			}

			$store = $store[ $section ];
		}

		return true;
	}

	/**
	 * Retrieves the value of the given key.
	 *
	 * @param string $key Configuration key to look for.
	 * @param mixed  $default An optional default value to return if the key is not found.
	 * @return mixed Value of the given key if found or the given default value.
	 */
	public function get( string $key, $default = null ) {
		$store = $this->store;

		foreach ( explode( '.', $key ) as $section ) {
			if ( ! is_array( $store ) || ! array_key_exists( $section, $store ) ) {
				return $default;
			}

			$store = $store[ $section ];
		}

		return $store;
	}

	/**
	 * Sets the value of the given key.
	 *
	 * @param string $key Configuration key to set value of.
	 * @param mixed  $value Configuration value.
	 */
	public function set( string $key, $value ): void {
		$store = &$this->store;

		foreach ( explode( '.', $key ) as $section ) {
			if ( ! $this->is_associative( $store ) ) {
				$store = array();
			}

			$store = &$store[ $section ];
		}

		$store = $value;
	}

	/**
	 * Removes the given key from the configuration object.
	 *
	 * @param string $key Configuration key to remove.
	 */
	public function remove( string $key ): void {
		$store         = &$this->store;
		$sections      = explode( '.', $key );
		$section_count = count( $sections );
		$section       = reset( $sections );

		for ( $i = 0; $i < $section_count; $section = $sections[ ++$i ] ) {
			if ( ! array_key_exists( $section, $store ) ) {
				break;
			}

			if ( count( $sections ) === $i + 1 ) {
				unset( $store[ $section ] );

				break;
			}

			$store = &$store[ $section ];
		}
	}

	/**
	 * Clears all settings from the configuration object.
	 */
	public function clear(): void {
		$this->store = array();
	}

	/**
	 * Merges in the given configuration object at the given key.
	 *
	 * @param \Moonwalking_Bits\Configuration\Configuration_Interface    $configuration Configuration object to merge.
	 * @param string|null                                                $key Configuration key to merge the object at.
	 * @param \Moonwalking_Bits\Configuration\Merge_Strategy|string|null $strategy Strategy to use when merging.
	 */
	public function merge(
		Configuration_Interface $configuration,
		?string $key = null,
		$strategy = null
	): void {
		$store = ! is_null( $key ) ? $this->get( $key ) : $this->all();

		if ( ! is_array( $store ) ) {
			$store = array();
		}

		if ( ! $strategy instanceof Merge_Strategy ) {
			$strategy = Merge_Strategy::from( $strategy ?? Merge_Strategy::REPLACE_INDEXED );
		}

		$merged_store = $this->merge_arrays( $store, $configuration->all(), $strategy );

		if ( ! is_null( $key ) ) {
			$this->set( $key, $merged_store );

			return;
		}

		$this->store = $merged_store;
	}
}

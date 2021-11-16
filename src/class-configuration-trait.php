<?php
/**
 * Configuration: Configuration trait
 *
 * @package Moonwalking_Bits\Configuration
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Configuration;

/**
 * A set of helpful methods for working with configuration objects.
 *
 * @since 0.1.0
 */
trait Configuration_Trait {

	/**
	 * Merges the two given arrays giving favor to the second.
	 *
	 * @param array                                          $a One of two arrays to merge.
	 * @param array                                          $b One of two arrays to merge.
	 * @param \Moonwalking_Bits\Configuration\Merge_Strategy $strategy Merge strategy to use.
	 * @return array The result of merging the two given arrays.
	 */
	protected function merge_arrays( array $a, array $b, Merge_Strategy $strategy ): array {
		foreach ( $b as $key => $value ) {
			if ( ! array_key_exists( $key, $a ) ) {
				$a[ $key ] = $value;

				continue;
			}

			if ( $this->is_associative( $a[ $key ] ) && $this->is_associative( $value ) ) {
				$a[ $key ] = $this->merge_arrays( $a[ $key ], $value, $strategy );

				continue;
			}

			if (
				$strategy->value() === Merge_Strategy::MERGE_INDEXED &&
				is_array( $a[ $key ] ) &&
				is_array( $value )
			) {
				$a[ $key ] = array_values( array_unique( array_merge( $a[ $key ], $value ) ) );

				continue;
			}

			$a[ $key ] = $value;
		}

		return $a;
	}

	/**
	 * Determines if the given value is an associative array.
	 *
	 * @param mixed $value Value to check.
	 * @return bool True if the given value is an associative array.
	 */
	protected function is_associative( $value ): bool {
		return is_array( $value ) && count( array_filter( array_keys( $value ), 'is_string' ) ) > 0;
	}
}

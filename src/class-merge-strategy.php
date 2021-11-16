<?php
/**
 * Configuration: Merge strategy enum
 *
 * @package Moonwalking_Bits\Configuration
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Configuration;

use Moonwalking_Bits\Enum\Abstract_Enum;

/**
 * Represents a fixed set of merge strategies.
 *
 * @since 0.1.0
 */
class Merge_Strategy extends Abstract_Enum {

	/**
	 * Replaces conflicting indexed arrays.
	 */
	const REPLACE_INDEXED = 0;

	/**
	 * Merges conflicting indexed arrays.
	 */
	const MERGE_INDEXED = 1;
}

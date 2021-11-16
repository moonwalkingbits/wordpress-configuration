<?php
/**
 * Configuration: Configuration builder
 *
 * @package Moonwalking_Bits\Configuration
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Configuration;

use Closure;
use Moonwalking_Bits\Configuration\Configuration_Source\Configuration_Source_Interface;

/**
 * Has the capability to build configuration objects.
 *
 * @since 0.1.0
 */
class Configuration_Builder {

	use Configuration_Trait;

	/**
	 * List of configuration sources to use when building the configuration object.
	 *
	 * @var array
	 */
	private array $configuration_sources = array();

	/**
	 * Adds a source to the list of configuration sources to use when building the configuration object.
	 *
	 * @since 0.1.0
	 * @param \Moonwalking_Bits\Configuration\Configuration_Source\Configuration_Source_Interface $source Configuration source instance.
	 * @param string|null                                                                         $section Section to add the source to.
	 * @return \Moonwalking_Bits\Configuration\Configuration_Builder Same instance for method chaining.
	 */
	public function add_configuration_source( Configuration_Source_Interface $source, ?string $section = null ): self {
		array_push( $this->configuration_sources, array( $source, $section ) );

		return $this;
	}

	/**
	 * Builds a configuration object from the available configuration sources.
	 *
	 * @param \Moonwalking_Bits\Configuration\Merge_Strategy|null $strategy Merge strategy to use.
	 * @return \Moonwalking_Bits\Configuration\Configuration_Interface Configuration object instance.
	 */
	public function build( ?Merge_Strategy $strategy = null ): Configuration_Interface {
		if ( is_null( $strategy ) ) {
			$strategy = Merge_Strategy::from( Merge_Strategy::REPLACE_INDEXED );
		}

		return array_reduce(
			$this->configuration_sources,
			$this->create_configuration_source_accumulator( $strategy ),
			new Configuration()
		);
	}

	/**
	 * Returns an accumulator function for configuration sources reduction.
	 *
	 * @param \Moonwalking_Bits\Configuration\Merge_Strategy $strategy Merge strategy to use.
	 * @return \Closure Configuration source accumulator.
	 */
	private function create_configuration_source_accumulator( Merge_Strategy $strategy ): Closure {
		return function ( Configuration_Interface $configuration, array $configuration_source ) use ( $strategy ) {
			list( $source, $section ) = $configuration_source;

			$configuration->merge( new Configuration( $source->fetch() ), $section, $strategy );

			return $configuration;
		};
	}
}

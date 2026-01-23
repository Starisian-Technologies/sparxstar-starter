<?php

declare(strict_types=1);

namespace Starisian\Sparxstar\Gluon;

use Starisian\Sparxstar\helpers\SparxstarGluonLogger as Logger;
use Exception;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SPARXSTAR Gluon Orchestrator Class
 *
 * The main orchestrator class that manages plugin initialization, dependency loading,
 * and coordination between different plugin components. Implements the Singleton pattern
 * to ensure a single instance throughout the plugin lifecycle.
 *
 * This class is responsible for:
 * - Loading plugin dependencies (Core, Rules, etc.)
 * - Registering WordPress hooks
 * - Managing plugin assets (CSS/JS)
 * - Loading text domain for internationalization
 * - Coordinating between different plugin modules
 *
 * @package    Starisian\Sparxstar\Gluon
 * @subpackage Core
 * @since      1.0.0
 * @version    1.0.0
 * @author     Starisian Technologies (Max Barrett) <support@starisian.com>
 * @final
 */
final class SparxstarGluonOrchestrator {


	/**
	 * Plugin version number.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const VERSION = GLUON_PLUGIN_VERSION;

	/**
	 * Singleton instance of the orchestrator.
	 *
	 * @since 1.0.0
	 * @var SparxstarGluonOrchestrator|null
	 */
	private static ?SparxstarGluonOrchestrator $instance = null;

	/**
	 * Plugin file path.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $pluginPath;

	/**
	 * Plugin URL.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $pluginUrl;

	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $version;

	/**
	 * Plugin display name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $pluginName;

	/**
	 * Array of loaded dependencies.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private array $dependencies = array();

	/**
	 * Core plugin instance.
	 *
	 * @since 1.0.0
	 * @var mixed
	 */
	private $core;

	/**
	 * Get singleton instance of the orchestrator.
	 *
	 * Implements the Singleton pattern to ensure only one instance
	 * of the orchestrator exists throughout the plugin lifecycle.
	 *
	 * @since 1.0.0
	 * @return SparxstarGluonOrchestrator The singleton instance.
	 */
	public static function gluonGetInstance(): SparxstarGluonOrchestrator {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor to prevent direct instantiation.
	 *
	 * Initializes the plugin by setting up paths, loading translations,
	 * loading dependencies, registering hooks, and setting up assets.
	 * Private to enforce Singleton pattern.
	 *
	 * @since 1.0.0
	 * @internal
	 */
	private function __construct() {
		$this->gluonPath = GLUON_PLUGIN_PATH;
		$this->gluonUrl  = GLUON_PLUGIN_URL;
		$this->version   = GLUON_PLUGIN_VERSION;
		$this->gluonName = GLUON_PLUGIN_NAME;

		// Load textdomain for translations.
		$this->gluonLoadTextdomain();
		// Load dependencies.
		$this->gluonLoadDependencies();
		// Register WordPress hooks.
		$this->gluonRegisterHooks();
		// Register assets.
		$this->gluonRegisterAssets();
	}

	/**
	 * Register WordPress hooks for the plugin.
	 *
	 * Sets up action and filter hooks that the plugin needs to function.
	 * Called during plugin initialization.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function gluonRegisterHooks(): void {
		\add_action( 'init', array( $this, 'gluonInit' ) );
	}

	/**
	 * Initialize plugin functionality
	 *
	 * @return void
	 */
	public function gluonInit(): void {
		// Plugin initialization logic here
	}
	/**
	 * Register plugin js and css assets for enqueing
	 *
	 * @return void
	 */
	private function gluonRegisterAssets(): void {
		// Register CSS and JS assets here
	}
	/**
	 * initializes a dependency.
	 *
	 * @internal
	 * @param string $dependency
	 * @return void
	 */
	private function gluonInitDependencies( string $dependency ): void {
		// Check if dependency class exists
		if ( ! class_exists( $dependency::class, false ) ) {
			Logger::log( 'Starisian Plugin: Dependency class ' . $dependency . ' not found.' );
			return;
		}
		// Store dependency
		$this->dependencies[ $dependency::class ] = $dependency;
		try {
			// Check if dependency has getInstance method (Singleton pattern)
			if ( method_exists( $dependency::class, 'pluginGetInstance' ) ) {
				// Use Singleton pattern to get instance
				$this->dependencies[ $dependency::class ]::gluonGetInstance();
			} else {
				// Instantiate dependency normally
				$this->dependencies[ $dependency::class ] = new $dependency();
			}
		} catch ( \Exception $e ) {
			Logger::log( 'Starisian Plugin: Failed to instantiate dependency class ' . $dependency . ': ' . $e->getMessage() );
			return;
		}
	}
	/**
	 * Loads all plugin dependencies.
	 *
	 * @return void
	 */
	private function gluonLoadDependencies(): void {
		$this->dependencies = array();
		$dependencies       = array(
			\Starisian\Sparxstar\core\SparxstarGluonCore::class,
			\Starisian\Sparxstar\integrations\SparxstarGluonRules::class,
		);
		foreach ( $dependencies as $dependency ) {
			$this->gluonInitDependencies( $dependency );
		}
	}
	/**
	 * Loads the plugin textdomain for translations.
	 *
	 * @return void
	 */
	private function gluonLoadTextdomain(): void {
		\load_textdomain( 'sparxstar-gluon', false, dirname( \plugin_basename( __FILE__ ) ) . '/languages' );
	}
	/**
	 * Runs the plugin.
	 *
	 * @return void
	 */
	public function gluonRun(): void {
		$this->gluonGetInstance();
	}

	/**
	 * Prevent cloning of the instance
	 *
	 * @return void
	 */
	private function __clone(): void {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is not allowed.', 'plugin-textdomain' ), self::VERSION );
	}

	/**
	 * Prevent unserializing of the instance
	 *
	 * @return void
	 */
	public function __wakeup(): void {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing is not allowed.', 'plugin-textdomain' ), self::VERSION );
	}

	/**
	 * Prevent serialization of the instance
	 *
	 * @return array
	 */
	public function __sleep(): array {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Serialization is not allowed.', 'plugin-textdomain' ), self::VERSION );
		return array();
	}

	/**
	 * Prevent calling undefined methods
	 *
	 * @param string $name Method name
	 * @param array  $arguments Method arguments
	 * @return void
	 */
	public function __call( string $name, array $arguments ): void {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Calling undefined methods is not allowed.', 'plugin-textdomain' ), self::VERSION );
	}
}

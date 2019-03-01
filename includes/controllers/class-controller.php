<?php
/**
 * Abstract controller.
 *
 * @package HivePress\Controllers
 */

namespace HivePress\Controllers;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Abstract controller class.
 *
 * @class Controller
 */
abstract class Controller {

	/**
	 * Controller name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Controller routes.
	 *
	 * @var array
	 */
	protected $routes = [];

	/**
	 * Class constructor.
	 *
	 * @param array $args Controller arguments.
	 */
	public function __construct( $args = [] ) {

		// Set name.
		$this->name = strtolower( ( new \ReflectionClass( $this ) )->getShortName() );

		// Set properties.
		foreach ( $args as $arg_name => $arg_value ) {
			call_user_func_array( [ $this, 'set_' . $arg_name ], [ $arg_value ] );
		}
	}

	/**
	 * Sets controller routes.
	 *
	 * @param array $routes Controller routes.
	 */
	final private function set_routes( $routes ) {
		$this->routes = $routes;
	}

	/**
	 * Gets controller routes.
	 *
	 * @return array
	 */
	final public function get_routes() {
		return $this->routes;
	}

	/**
	 * Gets controller name.
	 *
	 * @return string
	 */
	final public function get_name() {
		return $this->name;
	}
}

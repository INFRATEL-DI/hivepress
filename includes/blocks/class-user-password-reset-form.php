<?php
/**
 * User password reset form block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * User password reset form block class.
 *
 * @class User_Password_Reset_Form
 */
class User_Password_Reset_Form extends Form {

	/**
	 * Class constructor.
	 *
	 * @param array $args Block arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'form' => 'user_password_reset',
			],
			$args
		);

		parent::__construct( $args );
	}

	/**
	 * Bootstraps block properties.
	 */
	protected function boot() {

		// Set values.
		$this->values = array_merge(
			$this->values,
			[
				'username'           => hp\get_array_value( $_GET, 'username' ),
				'password_reset_key' => hp\get_array_value( $_GET, 'password_reset_key' ),
			]
		);

		// Set class.
		$this->attributes = hp\merge_arrays(
			$this->attributes,
			[
				'class' => [ 'hp-form--narrow' ],
			]
		);

		parent::boot();
	}

	/**
	 * Renders block HTML.
	 *
	 * @return string
	 */
	public function render() {
		$output = '';

		if ( ! is_wp_error( check_password_reset_key( $this->values['password_reset_key'], $this->values['username'] ) ) ) {
			$output .= parent::render();
		} else {
			$output .= ( new Part( [ 'path' => 'user/password/user-password-reset-message' ] ) )->render();
		}

		return $output;
	}
}

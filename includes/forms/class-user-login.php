<?php
/**
 * User login form.
 *
 * @package HivePress\Forms
 */

namespace HivePress\Forms;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * User login form class.
 *
 * @class User_Login
 */
class User_Login extends Model_Form {

	/**
	 * Class constructor.
	 *
	 * @param array $args Form arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'title'    => esc_html__( 'Login User', 'hivepress' ),
				'model'    => 'user',
				'action'   => hp\get_rest_url( '/users/login' ),
				'redirect' => true,

				'fields'   => [
					'username_or_email' => [
						'label'      => esc_html__( 'Username or Email', 'hivepress' ),
						'type'       => 'text',
						'max_length' => 254,
						'required'   => true,
						'order'      => 10,
					],

					'password'          => [
						'order' => 20,
					],
				],

				'button'   => [
					'label' => esc_html__( 'Sign In', 'hivepress' ),
				],
			],
			$args
		);

		parent::__construct( $args );
	}
}

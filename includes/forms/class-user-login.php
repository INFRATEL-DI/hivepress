<?php
/**
 * User login form.
 *
 * @package HivePress\Forms
 */

namespace HivePress\Forms;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * User login form class.
 *
 * @class User_Login
 */
class User_Login extends Form {

	/**
	 * Form captcha.
	 *
	 * @var bool
	 */
	protected $captcha = false;

	/**
	 * Class constructor.
	 *
	 * @param array $args Form arguments.
	 */
	public function __construct( $args = [] ) {
		parent::__construct( $args );

		// Set title.
		$this->set_title( esc_html__( 'Login User', 'hivepress' ) );

		// Set fields.
		$this->set_fields(
			[
				'username' => [
					'label'      => esc_html__( 'Username or Email', 'hivepress' ),
					'type'       => 'text',
					'max_length' => 254,
					'required'   => true,
					'order'      => 10,
				],

				'password' => [
					'label'    => esc_html__( 'Password', 'hivepress' ),
					'type'     => 'password',
					'required' => true,
					'order'    => 20,
				],
			]
		);
	}

	/**
	 * Submits form.
	 */
	public function submit() {
		parent::submit();

		// Set credentials.
		$credentials = [
			'user_password' => $values['password'],
			'remember'      => true,
		];

		if ( is_email( $values['username'] ) ) {
			$credentials['user_email'] = $values['username'];
		} else {
			$credentials['user_login'] = $values['username'];
		}

		// Authenticate user.
		$user = wp_signon( $credentials, is_ssl() );

		if ( is_wp_error( $user ) ) {
			$this->errors[] = esc_html__( 'Username or password is incorrect.', 'hivepress' );
		}
	}
}

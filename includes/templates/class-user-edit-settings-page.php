<?php
/**
 * User edit settings page template.
 *
 * @template user_edit_settings_page
 * @description User page in edit context (settings).
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * User edit settings page template class.
 *
 * @class User_Edit_Settings_Page
 */
class User_Edit_Settings_Page extends User_Account_Page {

	/**
	 * Class constructor.
	 *
	 * @param array $args Template arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'page_content' => [
						'blocks' => [
							'user_delete_modal' => [
								'type'    => 'modal',
								'caption' => esc_html__( 'Delete Account', 'hivepress' ),
								'_order'  => 5,

								'blocks'  => [
									'user_delete_form' => [
										'type'       => 'form',
										'form'       => 'user_delete',
										'_order'     => 10,

										'attributes' => [
											'class' => [ 'hp-form--narrow' ],
										],
									],
								],
							],

							'user_update_form'  => [
								'type'   => 'form',
								'form'   => 'user_update',
								'_order' => 10,

								'footer' => [
									'form_actions' => [
										'type'       => 'container',
										'_order'     => 10,

										'attributes' => [
											'class' => [ 'hp-form__actions' ],
										],

										'blocks'     => [
											'user_delete_link' => [
												'type'   => 'part',
												'path'   => 'user/edit/user-delete-link',
												'_order' => 10,
											],
										],
									],
								],
							],
						],
					],
				],
			],
			$args,
			'blocks'
		);

		parent::__construct( $args );
	}
}

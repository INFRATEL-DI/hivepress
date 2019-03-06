<?php
/**
 * Listing controller.
 *
 * @package HivePress\Controllers
 */

namespace HivePress\Controllers;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Forms;
use HivePress\Blocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing controller class.
 *
 * @class Listing
 */
class Listing extends Controller {

	/**
	 * Class constructor.
	 *
	 * @param array $args Controller arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			$args,
			[
				'routes' => [
					[
						'path'      => '/listings',
						'rest'      => true,
						'endpoints' => [
							[
								'path'    => '/(?P<id>\d+)',
								'methods' => 'POST',
								'action'  => 'update_listing',
							],

							[
								'path'    => '/(?P<id>\d+)',
								'methods' => 'DELETE',
								'action'  => 'delete_listing',
							],
						],
					],

					[
						'rule'   => 'is_listings_page',
						'action' => 'render_listings_page',
					],

					[
						'rule'   => 'is_listing_page',
						'action' => 'render_listing_page',
					],
				],
			]
		);

		parent::__construct( $args );
	}

	/**
	 * Updates listing.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function update_listing( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp\rest_error( 401 );
		}

		// Get listing.
		$listing = Models\Listing::get( $request->get_param( 'id' ) );

		if ( is_null( $listing ) ) {
			return hp\rest_error( 404 );
		}

		// Check permissions.
		// todo add author_id and status fields to model.
		if ( ! current_user_can( 'edit_others_posts' ) && ( get_current_user_id() !== $listing->get_author_id() || ! in_array( $listing->get_status(), [ 'auto-draft', 'draft', 'publish' ], true ) ) ) {
			return hp\rest_error( 403 );
		}

		// Validate form.
		$form = new Forms\Listing_Update();

		$form->set_values( $request->get_params() );

		if ( ! $form->validate() ) {
			return hp\rest_error( 400, $form->get_errors() );
		}

		// Update listing.
		$listing->fill( $form->get_values() );

		if ( ! $listing->save() ) {
			return hp\rest_error( 400, esc_html__( 'Error updating listing', 'hivepress' ) );
		}

		return new \WP_Rest_Response(
			[
				'data' => [
					'id' => $listing->get_id(),
				],
			],
			200
		);
	}

	/**
	 * Deletes listing.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function delete_listing( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp\rest_error( 401 );
		}

		// Get listing.
		$listing = Models\Listing::get( $request->get_param( 'id' ) );

		if ( is_null( $listing ) ) {
			return hp\rest_error( 404 );
		}

		// Check permissions.
		// todo add fields to model.
		if ( ! current_user_can( 'delete_others_posts' ) && ( get_current_user_id() !== $listing->get_author_id() || ! in_array( $listing->get_status(), [ 'auto-draft', 'draft', 'publish' ], true ) ) ) {
			return hp\rest_error( 403 );
		}

		// Delete listing.
		if ( ! $listing->delete() ) {
			return hp\rest_error( 400, esc_html__( 'Error deleting listing', 'hivepress' ) );
		}

		return new \WP_Rest_Response( (object) [], 204 );
	}

	/**
	 * Checks listings page.
	 *
	 * @return bool
	 */
	public function is_listings_page() {
		return is_page( absint( get_option( 'hp_page_listings' ) ) ) || is_post_type_archive( 'hp_listing' ) || is_tax( get_object_taxonomies( 'hp_listing' ) );
	}

	/**
	 * Renders listings page.
	 *
	 * @return string
	 */
	public function render_listings_page() {
		$output  = ( new Blocks\Element( [ 'attributes' => [ 'file_path' => 'header' ] ] ) )->render();
		$output .= ( new Blocks\Template( [ 'attributes' => [ 'template_name' => 'listings_page' ] ] ) )->render();
		$output .= ( new Blocks\Element( [ 'attributes' => [ 'file_path' => 'footer' ] ] ) )->render();

		return $output;
	}

	/**
	 * Checks listing page.
	 *
	 * @return bool
	 */
	public function is_listing_page() {
		return is_singular( 'hp_listing' );
	}

	/**
	 * Renders listing page.
	 *
	 * @return string
	 */
	public function render_listing_page() {
		$output  = ( new Blocks\Element( [ 'attributes' => [ 'file_path' => 'header' ] ] ) )->render();
		$output .= ( new Blocks\Template( [ 'attributes' => [ 'template_name' => 'listing_page' ] ] ) )->render();
		$output .= ( new Blocks\Element( [ 'attributes' => [ 'file_path' => 'footer' ] ] ) )->render();

		return $output;
	}
}

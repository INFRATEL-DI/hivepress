<?php
/**
 * Modal block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Modal block class.
 *
 * @class Modal
 */
class Modal extends Container {

	/**
	 * Modal caption.
	 *
	 * @var string
	 */
	protected $caption;

	/**
	 * Model name.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Bootstraps block properties.
	 */
	protected function boot() {

		// Get ID.
		$id = $this->name;

		if ( isset( $this->model ) ) {
			$id .= '_' . get_the_ID();
		}

		// Set attributes.
		$this->attributes = hp\merge_arrays(
			$this->attributes,
			[
				'id'             => $id,
				'class'          => [ 'hp-modal' ],
				'data-component' => 'modal',
			]
		);

		// Add title.
		array_unshift(
			$this->blocks,
			new Part(
				[
					'type'    => 'part',
					'path'    => 'page/modal-title',
					'context' => array_merge( $this->context, [ 'modal_title' => $this->caption ] ),
				]
			)
		);

		parent::boot();
	}
}

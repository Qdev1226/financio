<?php
/**
 * Block Style Class
 *
 * @author Jegstudio
 * @package financio
 * @since 1.0.0
 */

namespace Financio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init Class
 *
 * @package financio
 */
class Block_Styles {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->register_block_styles();
	}

	/**
	 * Register Block Patterns
	 */
	private function register_block_styles() {
		register_block_style(
			'core/button',
			array(
				'name'  => 'custombuttonfill',
				'label' => __( 'Button Fill', 'financio' ),
			)
		);

		register_block_style(
			'core/button',
			array(
				'name'  => 'custombuttonfillwhite',
				'label' => __( 'Button Fill White', 'financio' ),
			)
		);

		register_block_style(
			'core/columns',
			array(
				'name'  => 'customboxshadow',
				'label' => __( 'Box Shadow', 'financio' ),
			)
		);

		register_block_style(
			'core/column',
			array(
				'name'  => 'customboxshadow',
				'label' => __( 'Box Shadow', 'financio' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'customboxshadow',
				'label' => __( 'Box Shadow', 'financio' ),
			)
		);

		register_block_style(
			'core/columns',
			array(
				'name'  => 'customboxshadowhover',
				'label' => __( 'Box Shadow on Hover', 'financio' ),
			)
		);

		register_block_style(
			'core/column',
			array(
				'name'  => 'customboxshadowhover',
				'label' => __( 'Box Shadow on Hover', 'financio' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'customboxshadowhover',
				'label' => __( 'Box Shadow on Hover', 'financio' ),
			)
		);
	}
}

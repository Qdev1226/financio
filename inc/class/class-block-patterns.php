<?php
/**
 * Block Pattern Class
 *
 * @author Jegstudio
 * @package financio
 * @since 1.0.0
 */

namespace Financio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_Block_Pattern_Categories_Registry;

/**
 * Init Class
 *
 * @package financio
 */
class Block_Patterns {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->register_block_patterns();
	}

	/**
	 * Register Block Patterns
	 */
	private function register_block_patterns() {
		$block_pattern_categories = array(
			'financio-basic' => array( 'label' => __( 'Financio Basic Patterns', 'financio' ) ),
		);

		if ( defined( 'GUTENVERSE' ) ) {
			$block_pattern_categories['financio-gutenverse'] = array( 'label' => __( 'Financio Gutenverse Patterns', 'financio' ) );
		}

		$block_pattern_categories = apply_filters( 'financio_block_pattern_categories', $block_pattern_categories );

		foreach ( $block_pattern_categories as $name => $properties ) {
			if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
				register_block_pattern_category( $name, $properties );
			}
		}

		$block_patterns = array(
			'section-1',
			'section-2',
			'section-3',
			'section-4',
			'faq',
			'feature',
			'feature-2',
			'call-to-action',
			'404',
			'post-title',
			'hero-archive-title',
			'hero-index-title',
			'hero-search-title',
		);

		if ( defined( 'GUTENVERSE' ) ) {
			$block_patterns[] = 'gutenverse-header';
			$block_patterns[] = 'gutenverse-footer';
			$block_patterns[] = 'gutenverse-404-title';
			$block_patterns[] = 'gutenverse-about-title';
			$block_patterns[] = 'gutenverse-archive-title';
			$block_patterns[] = 'gutenverse-blog-title';
			$block_patterns[] = 'gutenverse-blog-content';
			$block_patterns[] = 'gutenverse-contact-title';
			$block_patterns[] = 'gutenverse-contact-content';
			$block_patterns[] = 'gutenverse-faq-title';
			$block_patterns[] = 'gutenverse-faq-content';
			$block_patterns[] = 'gutenverse-hero';
			$block_patterns[] = 'gutenverse-feature';
			$block_patterns[] = 'gutenverse-feature-2';
			$block_patterns[] = 'gutenverse-section-1';
			$block_patterns[] = 'gutenverse-section-2';
			$block_patterns[] = 'gutenverse-cta';
			$block_patterns[] = 'gutenverse-team';
			$block_patterns[] = 'gutenverse-faq';
			$block_patterns[] = 'gutenverse-testimonial';
			$block_patterns[] = 'gutenverse-description';
			$block_patterns[] = 'gutenverse-service-title';
			$block_patterns[] = 'gutenverse-service-content';
			$block_patterns[] = 'gutenverse-index-title';
			$block_patterns[] = 'gutenverse-page-title';
			$block_patterns[] = 'gutenverse-search-title';
			$block_patterns[] = 'gutenverse-single-title';
			$block_patterns[] = 'gutenverse-single-content';
		}

		$block_patterns = apply_filters( 'financio_block_patterns', $block_patterns );

		if ( function_exists( 'register_block_pattern' ) ) {
			foreach ( $block_patterns as $block_pattern ) {
				$pattern_file = get_theme_file_path( '/inc/patterns/' . $block_pattern . '.php' );

				register_block_pattern(
					'financio/' . $block_pattern,
					require $pattern_file
				);
			}
		}
	}
}

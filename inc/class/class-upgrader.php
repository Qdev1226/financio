<?php
/**
 * Upgrader Class
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
 * Block Pattern Class
 *
 * @package financio
 */
class Upgrader {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'index_notice' ) );
		add_action( 'admin_notices', array( $this, 'page_content_notice' ) );
		add_action( 'init', array( $this, 'determine_old_template' ) );
		add_action( 'init', array( $this, 'set_upgrader_page_content' ) );
		add_action( 'wp_ajax_themes_upgrader_index_close', array( $this, 'upgrader_index_close' ) );
		add_action( 'wp_ajax_themes_upgrader_page_close', array( $this, 'upgrader_page_close' ) );
		add_action( 'wp_ajax_themes_upgrader_page_content_close', array( $this, 'upgrader_page_content_close' ) );
		add_action( 'wp_ajax_themes_upgrader_process', array( $this, 'upgrade_index_process' ) );
	}

	/**
	 * Enqueue Script.
	 */
	public function enqueue_script() {
		if ( defined( 'GUTENVERSE_URL' ) && defined( 'GUTENVERSE_VERSION' ) ) {
			wp_enqueue_style( 'fontawesome-gutenverse' );
		}
	}

	/**
	 * Change option to false.
	 */
	public function upgrader_index_close() {
		update_option( $this->get_index_option_name(), false );
	}

	/**
	 * Change option to false.
	 */
	public function upgrader_page_close() {
		update_option( $this->get_page_option_name(), false );
	}

	/**
	 * Change option to false.
	 */
	public function upgrader_page_content_close() {
		update_option( $this->get_page_content_option_name(), false );
	}

	/**
	 * Change option to false.
	 */
	public function upgrade_index_process() {
		if ( ! current_user_can( 'manage_options' ) ) {
			exit;
		}

		/**
		 * Insert front page
		*/
		$index = get_block_template( wp_get_theme()->template . '//index' );
		$front = get_block_template( wp_get_theme()->template . '//front-page' );

		if ( 'custom' === $front->source ) {
			wp_send_json(
				array(
					'flag'    => false,
					'message' => esc_html__( 'Front Page exist. If you want migrate index, please clear Front Page customization.', 'financio' ),
				)
			);
			exit;
		}

		wp_insert_post(
			array(
				'post_name'    => 'front-page',
				'post_title'   => esc_html__( 'Front Page', 'financio' ),
				'post_type'    => 'wp_template',
				'post_status'  => 'publish',
				'post_content' => $index->content,
				'tax_input'    => array(
					'wp_theme' => array( wp_get_theme()->template ),
				),
			)
		);

		$this->upgrader_index_close();

		wp_send_json(
			array(
				'flag' => true,
			)
		);
		exit;
	}

	/**
	 * Get Option Name.
	 *
	 * @return string.
	 */
	public function get_index_option_name() {
		return wp_get_theme()->template . '_upgrader_index';
	}

	/**
	 * Get Option Name.
	 *
	 * @return string.
	 */
	public function get_page_option_name() {
		return wp_get_theme()->template . '_upgrader_page';
	}

	/**
	 * Get Option Name.
	 *
	 * @return string.
	 */
	public function get_page_content_option_name() {
		return wp_get_theme()->template . '_upgrader_page_content';
	}

	/**
	 * Check if user using old template. if yes, then insert the flag.
	 */
	public function determine_old_template() {
		if ( $this->assume_old_template() ) {
			$flag = get_option( $this->get_index_option_name() );

			if ( ! $flag ) {
				add_option( $this->get_index_option_name(), true );
			}
		}
	}

	/**
	 * Set content width upgrader option meta
	 */
	public function set_upgrader_page() {
		$flag = get_option( $this->get_page_option_name() );

		if ( ! $flag ) {
			add_option( $this->get_page_option_name(), true );
		}
	}

	/**
	 * Set content width upgrader option meta
	 */
	public function set_upgrader_page_content() {
		$flag = get_option( $this->get_page_content_option_name() );

		if ( ! $flag ) {
			add_option( $this->get_page_content_option_name(), true );
		}
	}

	/**
	 * Check if we can render the notice.
	 */
	public function assume_old_template() {
		$index = get_block_template( wp_get_theme()->template . '//index' );
		$front = get_block_template( wp_get_theme()->template . '//front-page' );

		return 'custom' === $index->source && 'theme' === $front->source;
	}

	/**
	 * Admin Notice.
	 */
	public function index_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$flag  = get_option( $this->get_index_option_name() );
		$front = get_block_template( wp_get_theme()->template . '//front-page' );

		if ( $flag ) {
			?>
			<div class="notice notice-important theme-upgrade-notice index-upgrade">
				<h2><?php esc_html_e( 'Important', 'financio' ); ?> <?php echo esc_html( wp_get_theme()->name ); ?> <?php esc_html_e( 'Upgrade Notice!', 'financio' ); ?></h2>
			<?php

			if ( 'custom' === $front->source ) {
				?>
					<p><?php esc_html_e( 'We notice you are upgrading from old theme and also have updated your Front Page. If previously you have issue with data lost on your home page, please proceed with upgrading process below.', 'financio' ); ?></p>
					<ol>
						<li><a href='<?php echo esc_url( admin_url( 'site-editor.php' ) ); ?>' target="_blank"><?php esc_html_e( 'Please visit site editor page.', 'financio' ); ?></a></li>
						<li><?php esc_html_e( 'Before resetting, please make sure you back up your changes of Front Page template first.', 'financio' ); ?></li>
						<li><?php esc_html_e( 'If you sure you are having this issue, you can', 'financio' ); ?>
							<a target="_blank" href="https://img001.prntscr.com/file/img001/h0PskNncSi2E9OnDRA36nQ.png"><?php esc_html_e( 'reset the Front Page template.', 'financio' ); ?></a>
							<?php esc_html_e( 'But don\'t reset the Index template.', 'financio' ); ?>
						</li>
						<li><?php esc_html_e( 'After resetting, please refresh this page.', 'financio' ); ?></li>
					</ol>
					<div class="themes-upgrade-action">
						<a class='button-primary close-notification' href="#"><?php esc_html_e( 'I don\'t have the issue', 'financio' ); ?></a>
					</div>
				<?php
			} else {
				?>
					<p><?php esc_html_e( 'We notice you are upgrading from old theme. If you have issue with data lost on your front page, please proceed with upgrading process below.', 'financio' ); ?></p>
					<ol>
						<li><a href='<?php echo esc_url( admin_url( 'site-editor.php' ) ); ?>' target="_blank"><?php esc_html_e( 'Please visit site editor page.', 'financio' ); ?></a></li>
						<li><?php esc_html_e( 'Check your front page (from top to bottom), if your front page looks different than before, please click "Fix Issue" button below.', 'financio' ); ?></li>
						<li><?php esc_html_e( 'This issue only happened for Front Page, any change you made elsewhere are still intact.', 'financio' ); ?></li>					
						<li><?php esc_html_e( 'This process will copy the content of your Index template to your Front Page template. You can always revert back to default front page by ', 'financio' ); ?>
							<a target="_blank" href="https://img001.prntscr.com/file/img001/h0PskNncSi2E9OnDRA36nQ.png"><?php esc_html_e( 'resetting the template.', 'financio' ); ?></a>
						</li>
					</ol>
					<div class="themes-upgrade-action">
						<a class='button-success upgrade-themes' href="#"><?php esc_html_e( 'Fix Issue', 'financio' ); ?></a>
						<a class='button-primary close-notification' href="#"><?php esc_html_e( 'I don\'t have the issue', 'financio' ); ?></a>
					</div>
				<?php
			}
			?>
			</div>
			<script>
				(function($) {
					$('.theme-upgrade-notice.index-upgrade .upgrade-themes').on('click', function() {
						var button = $(this);

						button.text('Loading...');
						var request = $.post( ajaxurl, {
							action: 'themes_upgrader_process'
						} );

						request.done(function(response) {
							if (response.flag) {
								alert('Migrating Home Page Success!');
								$('.theme-upgrade-notice.index-upgrade').fadeOut();
							} else {
								if (response.message) {
									alert(response.message);
								}

								button.text('<?php esc_html_e( 'Fix Issue', 'financio' ); ?>');
							}
						})
					});

					$('.theme-upgrade-notice.index-upgrade .close-notification').on('click', function() {
						$.post( ajaxurl, {
							action: 'themes_upgrader_index_close'
						} );

						$('.theme-upgrade-notice.index-upgrade').fadeOut();
					});
				})(jQuery);
			</script>
			<?php
		}
	}

	/**
	 * Admin Notice.
	 */
	public function page_content_notice() {
		global $pagenow;

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( defined( 'GUTENVERSE_VERSION' ) ) {
			if ( version_compare( GUTENVERSE_VERSION, '1.5.0', '<=' ) ) {
				if ( get_option( $this->get_page_content_option_name() ) ) {
					$this->enqueue_script();
					?>
					<div class="notice notice-warning has-logo theme-upgrade-notice page-content-upgrade">
						<div class="notice-logo">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10 0C4.47754 0 0 4.47915 0 10C0 15.5241 4.47754 20 10 20C15.5225 20 20 15.5241 20 10C20 4.47915 15.5225 0 10 0ZM10 4.43548C10.9353 4.43548 11.6935 5.19371 11.6935 6.12903C11.6935 7.06435 10.9353 7.82258 10 7.82258C9.06468 7.82258 8.30645 7.06435 8.30645 6.12903C8.30645 5.19371 9.06468 4.43548 10 4.43548ZM12.2581 14.6774C12.2581 14.9446 12.0414 15.1613 11.7742 15.1613H8.22581C7.95859 15.1613 7.74194 14.9446 7.74194 14.6774V13.7097C7.74194 13.4425 7.95859 13.2258 8.22581 13.2258H8.70968V10.6452H8.22581C7.95859 10.6452 7.74194 10.4285 7.74194 10.1613V9.19355C7.74194 8.92633 7.95859 8.70968 8.22581 8.70968H10.8065C11.0737 8.70968 11.2903 8.92633 11.2903 9.19355V13.2258H11.7742C12.0414 13.2258 12.2581 13.4425 12.2581 13.7097V14.6774Z" fill="#FFC908"/>
							</svg>
						</div>
						<div class="notice-content">
							<h2><?php echo sprintf( esc_html__( '%s Upgrade Notice!', 'financio' ), wp_get_theme()->name ); ?></h2>
							<p>
								<?php esc_html_e( 'There are some new exciting updates we want to announce. This update will required the latest version of Gutenverse plugin to work smoothly, so we recommend to update your Gutenverse plugin.', 'financio' ); ?>
							</p>
							<div class="themes-upgrade-action">
								<?php
								if ( 'plugins.php' !== $pagenow ) {
									?>
									<a class='button-primary upgrade-themes' href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>"><?php esc_html_e( 'Go to plugin page', 'financio' ); ?></a>
									<?php
								}
								?>
								<a class='close-notification' href="#"><?php esc_html_e( 'Close notification', 'financio' ); ?></a>
							</div>
						</div>
					</div>
					<script>
						(function($) {
							$('.theme-upgrade-notice.page-content-upgrade .close-notification').on('click', function() {
								$.post( ajaxurl, {
									action: 'themes_upgrader_page_content_close'
								} );

								$('.theme-upgrade-notice.page-content-upgrade').fadeOut();
							});
						})(jQuery);
					</script>
					<?php
				}
			} else {
				$this->set_upgrader_page();

				if ( get_option( $this->get_page_option_name() ) && ! ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'gutenverse-upgrade-notice' === $_GET['page'] ) ) {
					$this->enqueue_script();
					?>
					<div class="notice notice-warning has-logo theme-upgrade-notice page-upgrade">
						<div class="notice-logo">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10 0C4.47754 0 0 4.47915 0 10C0 15.5241 4.47754 20 10 20C15.5225 20 20 15.5241 20 10C20 4.47915 15.5225 0 10 0ZM10 4.43548C10.9353 4.43548 11.6935 5.19371 11.6935 6.12903C11.6935 7.06435 10.9353 7.82258 10 7.82258C9.06468 7.82258 8.30645 7.06435 8.30645 6.12903C8.30645 5.19371 9.06468 4.43548 10 4.43548ZM12.2581 14.6774C12.2581 14.9446 12.0414 15.1613 11.7742 15.1613H8.22581C7.95859 15.1613 7.74194 14.9446 7.74194 14.6774V13.7097C7.74194 13.4425 7.95859 13.2258 8.22581 13.2258H8.70968V10.6452H8.22581C7.95859 10.6452 7.74194 10.4285 7.74194 10.1613V9.19355C7.74194 8.92633 7.95859 8.70968 8.22581 8.70968H10.8065C11.0737 8.70968 11.2903 8.92633 11.2903 9.19355V13.2258H11.7742C12.0414 13.2258 12.2581 13.4425 12.2581 13.7097V14.6774Z" fill="#FFC908"/>
							</svg>
						</div>
						<div class="notice-content">
							<h2><?php echo sprintf( esc_html__( '%s Upgrade Notice!', 'financio' ), wp_get_theme()->name ); ?></h2>
							<p>
								<?php esc_html_e( 'We\'ve made some changes to page content layout and global style. Please check ', 'financio' ); ?>
								<a target="_blank" href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>"><?php esc_html_e( 'your pages', 'financio' ); ?></a>
								<?php esc_html_e( ' to see how the new layout looks on your site. You can always revert back by enable the inherit layout option on the ', 'financio' ); ?>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=gutenverse-settings' ) ); ?>"><?php esc_html_e( 'Gutenverse settings.', 'financio' ); ?></a>
								<?php esc_html_e( ' dashboard. Learn more about new layout on ', 'financio' ); ?>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=gutenverse-upgrade-notice&version=1.6.0' ) ); ?>"><?php esc_html_e( 'this page.', 'financio' ); ?></a>
							</p>
							<div class="themes-upgrade-action">
								<a class='close-notification' href="#"><?php esc_html_e( 'Close notification', 'financio' ); ?></a>
								<a class="get-support" href='https://wordpress.org/support/plugin/gutenverse/' target="_blank">
								<?php esc_html_e( 'Got Question?', 'financio' ); ?>
								</a>
							</div>
						</div>
					</div>
					<script>
						(function($) {
							$('.theme-upgrade-notice.page-upgrade .close-notification').on('click', function() {
								$.post( ajaxurl, {
									action: 'themes_upgrader_page_close'
								} );

								$('.theme-upgrade-notice.page-upgrade').fadeOut();
							});
						})(jQuery);
					</script>
					<?php
				}
			}
		}
	}
}

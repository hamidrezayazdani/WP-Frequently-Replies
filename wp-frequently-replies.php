<?php
/*
 * Plugin Name: WP Frequently Replies
 * Description: If you are tired of copying/pasting duplicate responses to your users' comments, this plugin is for you
 * Version: 1.0.0
 * Author: Hamid Reza Yazdani
 * Author URI: https://www.linkedin.com/in/hamid-reza-yazdani/
 * Text Domain: frequently-replies
 * Domain Path: /languages/
 * Requires PHP: 7.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WFR_VER' ) ) {
	define( 'WFR_VER', '1.0.0' );
}

if ( ! defined( 'WFR_URI' ) ) {
	define( 'WFR_URI', plugin_dir_url( __FILE__ ) );
}

if ( ! function_exists( 'wfr_load_translations' ) ) {

	/**
	 * Load plugin translations.
	 *
	 * @return void
	 */
	function wfr_load_translations() {
		load_plugin_textdomain( 'frequently-replies', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	add_action( 'init', 'wfr_load_translations' );
}

if ( ! function_exists( 'wfr_enqueue_assets' ) ) {

	/**
	 * Enqueue the scripts and styles.
	 *
	 * @return void
	 */
	function wfr_enqueue_assets( $hook ) {
		if ( 'comments_page_wfr-options' === $hook ) {
			return;
		}

		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'wfr-script', WFR_URI . 'assets/js/editor-script.js', array( 'jquery', 'quicktags', 'thickbox' ), WFR_VER, true );

		$replies_list = get_option( 'wfr_replies', array() );
		$replies      = array();

		foreach ( $replies_list as $reply ) {
			$replies[] = array(
				'slug'    => esc_attr( $reply['slug'] ),
				'title'   => esc_attr( $reply['title'] ),
				'content' => esc_html( $reply['content'] ),
			);
		}

		$wfr_localized = array(
			'i18n'    => array(
				'wfrBtn'       => esc_attr__( 'Frequently Replies', 'frequently-replies' ),
				'wfrTip'       => esc_attr__( 'Click and select a reply to reply easily', 'frequently-replies' ),
				'insert'       => esc_html__( 'Insert', 'frequently-replies' ),
				'cancel'       => esc_html__( 'Cancel', 'frequently-replies' ),
				'pleaseSelect' => esc_html__( 'Please select a reply to insert', 'frequently-replies' ),
				'pleaseAdd'    => esc_html__( 'Please add some replies to start from', 'frequently-replies' ),
				'here'         => esc_html__( 'here', 'frequently-replies' ),
				'noItem'       => esc_html__( 'No reply found!', 'frequently-replies' ),
				'optionsUrl'   => esc_url( add_query_arg( array( 'page' => 'wfr-options' ), get_admin_url() . 'edit-comments.php' ) ),
			),
			'replies' => $replies,
		);

		wp_localize_script( 'wfr-script', 'wfrReplies', $wfr_localized );
	}

	add_action( 'admin_enqueue_scripts', 'wfr_enqueue_assets' );
}

if ( ! function_exists( 'wfr_options_page' ) ) {

	/**
	 * Register the options page and render it.
	 *
	 * @return void
	 */
	function wfr_options_page() {
		$hook_suffix = add_submenu_page(
			'edit-comments.php',
			__( 'Frequently Replies', 'frequently-replies' ),
			__( 'Frequently Replies', 'frequently-replies' ),
			'moderate_comments',
			'wfr-options',
			'wfr_render_options_page',
		);

		add_action( 'admin_print_styles-' . $hook_suffix, 'wfr_enqueue_option_page_style' );
		add_action( 'admin_print_scripts-' . $hook_suffix, 'wfr_enqueue_option_page_script' );
	}

	add_action( 'admin_menu', 'wfr_options_page' );
}

if ( ! function_exists( 'wfr_enqueue_option_page_style' ) ) {

	/**
	 * Enqueues option page style.
	 *
	 * @return void
	 */
	function wfr_enqueue_option_page_style() {
		$suffix = wfr_script_debug_activated() ? '' : '.min';

		wp_enqueue_style( 'wfr-option-page-style', WFR_URI . 'assets/css/option-page-style' . $suffix . '.css', '', WFR_VER );
	}
}

if ( ! function_exists( 'wfr_enqueue_option_page_script' ) ) {

	/**
	 * Enqueues option page script.
	 *
	 * @return void
	 */
	function wfr_enqueue_option_page_script() {
		$suffix = wfr_script_debug_activated() ? '' : '.min';

		wp_enqueue_editor();
		wp_enqueue_script( 'wfr-option-page-script', WFR_URI . 'assets/js/option-page-script' . $suffix . '.js', array( 'jquery', 'quicktags', 'thickbox' ), WFR_VER, true );

		$option_page_localized = array(
			'i18n' => array(
				'title'        => esc_html__( 'Title', 'frequently-replies' ),
				'content'      => esc_html__( 'Content', 'frequently-replies' ),
				'remove'       => esc_html__( 'Remove', 'frequently-replies' ),
				'optionsSaved' => esc_html__( 'The reply list saved!', 'frequently-replies' ),
				'error'        => esc_html__( 'Error!', 'frequently-replies' ),
			),
		);

		wp_localize_script( 'wfr-option-page-script', 'wfrOptions', $option_page_localized );
	}
}

if ( ! function_exists( 'wfr_render_options_page' ) ) {

	/**
	 * Includes option page view.
	 *
	 * @return void
	 */
	function wfr_render_options_page() {
		include __DIR__ . '/views/option-page.php';
	}
}

if ( ! function_exists( 'wfr_script_debug_activated' ) ) {

	/**
	 * Checks WP SCRIPT_DEBUG is true or not
	 *
	 * @return bool
	 */
	function wfr_script_debug_activated() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	}
}

if ( ! function_exists( 'wfr_save_frequently_replies_ajax_callback' ) ) {

	/**
	 * Handles save replies list ajax call
	 *
	 * @return void
	 */
	function wfr_save_frequently_replies_ajax_callback() {
		check_ajax_referer( 'wfr-options-nonce', 'wfr_nonce' );

		if ( ! current_user_can( 'moderate_comments' ) ) {
			wp_send_json_error(
				array(
					'success'  => false,
					'response' => 403,
					'message'  => 'Access denied',
				),
				403,
			);
		}

		$replies           = $_POST['replies'];
		$sanitized_replies = array();
		$i                 = 0;

		if ( ! empty( $replies ) ) {
			foreach ( $replies as $reply ) {
				if ( empty( trim( $reply['title'] ) ) && empty( trim( $reply['content'] ) ) ) {
					continue;
				}

				$sanitized_replies[] = array(
					'slug'    => empty( $reply['title'] ) ? "reply-title-$i" : sanitize_title( $reply['title'] ),
					'title'   => empty( $reply['title'] ) ? "Reply #$i" : sanitize_text_field( $reply['title'] ),
					'content' => wfr_sanitize_reply( $reply['content'] ),
				);

				$i ++;
			}
		}

		$existing = get_option( 'wfr_replies' );

		if ( $existing === $sanitized_replies ) {
			wp_send_json_success(
				array(
					'success'  => true,
					'response' => 200,
					'message'  => esc_html__( 'The replies saved successfully without changes!', 'frequently-replies' ),
				),
			);
		}

		$saved = update_option( 'wfr_replies', $sanitized_replies, false );

		if ( $saved ) {
			wp_send_json_success(
				array(
					'success'  => true,
					'response' => 200,
					'message'  => esc_html__( 'The replies saved successfully!', 'frequently-replies' ),
				),
			);
		}

		wp_send_json_error(
			array(
				'success'  => false,
				'response' => 200,
				'message'  => esc_html__( 'Failed to save the replies!', 'frequently-replies' ),
			),
		);
	}

	add_action( 'wp_ajax_save_wfr_options', 'wfr_save_frequently_replies_ajax_callback' );
}

if ( ! function_exists( 'wfr_sanitize_reply' ) ) {

	/**
	 * Sanitization user frequently reply.
	 *
	 * @param string $reply_content The un-sanitized reply content.
	 *
	 * @return string Sanitized reply content with allowed tags.
	 */
	function wfr_sanitize_reply( $reply_content ) {
		// Define the allowed HTML tags (including wp_editor quick tags).
		$allowed_tags = apply_filters( 'wfr_allowed_tags', array(
			'a'          => array(
				'href'  => true,
				'title' => true,
			),
			'span'       => array(
				'class'          => true,
				'data-mce-bogus' => true,
				'data-mce-type'  => true,
			),
			'p'          => array(),
			'strong'     => array(),
			'em'         => array(),
			'ul'         => array(),
			'ol'         => array(),
			'li'         => array(),
			'br'         => array(),
			'blockquote' => array(),
			'code'       => array(),
			'pre'        => array(),
		) );

		return wp_kses( $reply_content, $allowed_tags );
	}
}

if ( ! function_exists( 'wfr_add_wc_hpos_compatibility' ) ) {

	/**
	 * Adds WooCommerce HPOS compatibility
	 *
	 * @return void
	 */
	function wfr_add_wc_hpos_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__ );
		}
	}

	add_action( 'before_woocommerce_init', 'wfr_add_wc_hpos_compatibility' );
}
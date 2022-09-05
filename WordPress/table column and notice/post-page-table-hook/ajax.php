<?php

namespace RT\ThePostGridPro\Controllers;

use RT\ThePostGridPro\Traits\ELTempleateBuilderTraits;
use RT\ThePostGrid\Helpers\Fns;
use RT\ThePostGrid\Helpers\Options;
use RT\ThePostGridPro\Helpers\Functions;
use WP_Query;

class AjaxController {

	/**
	 * Template builder related traits.
	 */
	use ELTempleateBuilderTraits;

	private $l4toggleLoadMore;
	private $order = "DESC";

	function __construct() {
		//Ajax callback for Elementor archive page builder
		add_action( 'wp_ajax_tpgp_el_templeate_builder', [ $this, 'tpgp_el_templeate_builder' ] );
		add_action( 'wp_ajax_tpgp_el_create_templeate', [ $this, 'tpgp_el_create_templeate' ] );
		add_action( 'wp_ajax_tpgp_el_default_template', [ $this, 'tpgp_el_default_template' ] );
	}


    /**
	 * Elementor template builder
	 *
	 * @return void
	 */
	public static function tpgp_el_templeate_builder() {
		$title = '<h2>' . esc_html__( 'Template Settings', 'the-post-grid-pro' ) . '</h2>';
		if ( ! Fns::verifyNonce() ) {
			$return = [
				'success' => false,
				'title'   => $title,
				'content' => esc_html__( 'Session Expired...', 'the-post-grid-pro' ),
			];
			wp_send_json( $return );
		}
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : null;

		$template_type    = null;
		$template_default = null;
		$url              = null;
		$tmp_title        = '';

		if ( $post_id ) {
			$tmp_title        = get_the_title( $post_id );
			$type             = get_post_meta( $post_id, self::template_type_meta_key() );
			$template_type    = isset( $type[0] ) ? $type[0] : '';
			$template_default = absint( self::builder_page_id( $template_type ) );
			$url              = add_query_arg(
				[
					'post'   => $post_id,
					'action' => 'elementor',
				],
				admin_url( 'post.php' )
			);
		}
		ob_start();

		?>
        <form action="<?php echo esc_url( admin_url( 'edit.php?post_type=tpgp_builder' ) ); ?>" autocomplete="off">
            <div class="tpgp-tb-modal-wrapper ">
                <div class="tpgp-template-name tpgp-tb-field-wraper">
                    <label for="tpgp_tb_template_name"> <?php esc_html_e( 'Template name', 'the-post-grid-pro' ); ?></label>
                    <input required class="tpgp-field" type="text" id="tpgp_tb_template_name" name="tpgp_tb_template_name"
                           placeholder="<?php esc_attr_e( 'Template name', 'the-post-grid-pro' ); ?>" value="<?php echo esc_attr( $tmp_title ); ?>" autocomplete="off">
                    <span class="message" style="display: none; color:red"><?php esc_html_e( 'This field is required', 'the-post-grid-pro' ); ?></span>
                </div>
                <div class="tpgp-template-type tpgp-tb-field-wraper">
                    <label for="tpgp_tb_template_type"><?php esc_html_e( 'Template Type', 'the-post-grid-pro' ); ?></label>
                    <select class="tpgp-field" id="tpgp_tb_template_type" name="tpgp_tb_template_type">
                        <option <?php echo 'single' === $template_type ? 'selected="selected"' : ''; ?> value="single">
							<?php esc_html_e( 'Single', 'the-post-grid-pro' ); ?>
                        </option>
                        <option <?php echo 'archive' === $template_type ? 'selected="selected"' : ''; ?> value="archive">
							<?php esc_html_e( 'Post Archive', 'the-post-grid-pro' ); ?>
                        </option>
                        <option <?php echo 'author-archive' === $template_type ? 'selected="selected"' : ''; ?> value="author-archive">
							<?php esc_html_e( 'Author Archive', 'the-post-grid-pro' ); ?>
                        </option>
                        <option <?php echo 'search-archive' === $template_type ? 'selected="selected"' : ''; ?> value="search-archive">
							<?php esc_html_e( 'Search Archive', 'the-post-grid-pro' ); ?>
                        </option>
                        <option <?php echo 'date-archive' === $template_type ? 'selected="selected"' : ''; ?> value="date-archive">
							<?php esc_html_e( 'Date Archive', 'the-post-grid-pro' ); ?>
                        </option>
                        <option <?php echo 'category-archive' === $template_type ? 'selected="selected"' : ''; ?> value="category-archive">
							<?php esc_html_e( 'Category Archive', 'the-post-grid-pro' ); ?>
                        </option>
                        <option <?php echo 'tags-archive' === $template_type ? 'selected="selected"' : ''; ?> value="tags-archive">
							<?php esc_html_e( 'Tags Archive', 'the-post-grid-pro' ); ?>
                        </option>
                    </select>
                </div>
                <div class="tpgp-template-setdefaults">
                    <input type="checkbox" id="default_template" class="tpgp-field" name="default_template" value="default_template" <?php echo ( $post_id
					                                                                                                                              && absint( $post_id )
					                                                                                                                                 === absint( $template_default ) )
						? 'checked' : ''; ?>>
                    <label for="default_template"> <?php esc_html_e( 'Set Default Template', 'the-post-grid-pro' ); ?></label><br>
                </div>
                <input type="hidden" id="page_id" name="page_id" value="<?php echo esc_attr( $post_id ); ?>">

                <div class="tpgp-template-footer">
                    <div class="tpgp-tb-button-wrapper save-button">
                        <button <?php echo $post_id ? esc_attr( 'disabled' ) : ''; ?> type="submit" id="tpgp_tb_button"> <?php esc_html_e( 'Save',
								'the-post-grid-pro' ); ?></button>
                    </div>
                    <div class="tpgp-tb-button-wrapper tpgp-tb-edit-button-wrapper">
                        <a href="<?php echo esc_url( $url ); ?>" class="btn"> <?php esc_html_e( 'Edit with elementor', 'the-post-grid-pro' ); ?> </a>
                    </div>
                </div>
            </div>
        </form>
		<?php
		$content = ob_get_clean();
		$return  = [
			'success' => true,
			'title'   => $title,
			'content' => $content,
		];
		wp_send_json( $return );
		wp_die();
	}


	/**
	 * Elementor Create Templeate
	 *
	 * @return void
	 */
	public static function tpgp_el_create_templeate() {
		$page_type        = isset( $_POST['page_type'] ) ? sanitize_text_field( wp_unslash( $_POST['page_type'] ) ) : null;
		$page_id          = isset( $_POST['page_id'] ) ? absint( wp_unslash( $_POST['page_id'] ) ) : null;
		$page_name        = isset( $_POST['page_name'] ) ? sanitize_text_field( wp_unslash( $_POST['page_name'] ) ) : null;
		$default_template = isset( $_POST['default_template'] ) ? sanitize_text_field( wp_unslash( $_POST['default_template'] ) ) : null;
		$url              = '#';

		if ( ! Fns::verifyNonce() || ! $page_type ) {
			$return = [
				'success' => false,
				'post_id' => $page_id,
			];
			wp_send_json( $return );
		}
		$option_name = self::option_name( $page_type );
		$post_data   = [
			'ID'         => $page_id,
			'post_title' => $page_name,
			'meta_input' => [
				self::template_type_meta_key() => $page_type,
			],
		];
		if ( $page_id ) {
			$page_id  = wp_update_post( $post_data );
			$new_page = false;
		} else {
			unset( $post_data['ID'] );
			$post_data['post_type']   = self::$post_type_tb;
			$post_data['post_status'] = 'publish';
			$page_id                  = wp_insert_post( $post_data );
			$new_page                 = true;
		}
		if ( $page_id ) {
			update_post_meta( $page_id, '_wp_page_template', 'elementor_header_footer' );
			if ( 'default_template' === $default_template ) {
				update_option( $option_name, $page_id );
			} else {
				update_option( $option_name, '' );
			}
			$url = add_query_arg(
				[
					'post'   => $page_id,
					'action' => 'elementor',
				],
				admin_url( 'post.php' )
			);
		}

		$return = [
			'success'       => true,
			'post_id'       => $page_id,
			'post_edit_url' => $url,
			'new_page'      => $new_page,
		];
		wp_send_json( $return );
		wp_die();
	}

	/**
	 * Elementor Create Templeate
	 *
	 * @return void
	 */
	public static function tpgp_el_default_template() {
		$page_type = isset( $_POST['template_type'] ) ? sanitize_text_field( wp_unslash( $_POST['template_type'] ) ) : null;
		$page_id   = isset( $_POST['page_id'] ) ? absint( wp_unslash( $_POST['page_id'] ) ) : null;

		if ( ! Fns::verifyNonce() || ! $page_type ) {
			$return = [
				'success'   => false,
				'post_id'   => $page_id,
				'page_type' => $page_type,
			];
			wp_send_json( $return );
		}
		$option_name = self::option_name( $page_type );
		update_option( $option_name, $page_id );

		$return = [
			'success'   => true,
			'post_id'   => $page_id,
			'page_type' => $page_type,
		];
		wp_send_json( $return );
		wp_die();
	}

}

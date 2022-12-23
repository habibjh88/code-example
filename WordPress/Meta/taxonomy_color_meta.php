<?php
/**
 * Action Hooks Class.
 *
 * @package RT_TPG_PRO
 */

/**
 * Action Hooks Class.
 */
class TaxonomyColorMeta {
	/**
	 * Class constructor
	 */

	public function __construct() {
		//Category color
		add_action( 'category_add_form_fields', [ $this, 'colorpicker_field_add_new_category' ] );
		add_action( 'category_edit_form_fields', [ $this, 'colorpicker_field_edit_category' ] );
		add_action( 'created_category', [ $this, 'save_termmeta' ] );
		add_action( 'edited_category', [ $this, 'save_termmeta' ] );
		add_filter( 'manage_edit-category_columns', [ $this, 'edit_term_columns' ], 10, 3 );
		add_filter( 'manage_category_custom_column', [ $this, 'manage_term_custom_column' ], 10, 3 );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_custom_wp_admin_style' ] );
        add_action( 'admin_footer', array ( $this, 'add_script' ) );
	}

	/**
	 * Category Color Added
	 *
	 * @param $taxonomy
	 *
	 * @return void
	 */
	public function colorpicker_field_add_new_category( $taxonomy ) {
		?>
        <div class="form-field term-colorpicker-wrap">
            <label for="term-colorpicker"><?php esc_html_e( 'Category Color - The Post Grid', 'the-post-grid' ); ?></label>
            <input name="<?php echo esc_attr( rtTpgPro()->category_meta_key ) ?>" value="" class="rt-color"
                   id="<?php echo esc_attr( rtTpgPro()->category_meta_key ) ?>"/>
            <p><?php esc_html_e( 'Please add category color for The Post Grid plugins\'s Layout', 'the-post-grid' ); ?></p>
        </div>
		<?php
	}

	/**
	 * Edit category table
	 *
	 * @param $term
	 *
	 * @return void
	 */
	public function colorpicker_field_edit_category( $term ) {
		$color = get_term_meta( $term->term_id, rtTpgPro()->category_meta_key, true );
		$color = ( ! empty( $color ) ) ? "#{$color}" : '';

		?>
        <tr class="form-field term-colorpicker-wrap">
            <th scope="row"><label
                        for="term-colorpicker"><?php esc_html_e( 'Category Color - The Post Grid', 'the-post-grid' ); ?></label>
            </th>
            <td>
                <input name="<?php echo esc_attr( rtTpgPro()->category_meta_key ) ?>"
                       value="<?php echo esc_attr( $color ); ?>" class="rt-color"
                       id="<?php echo esc_attr( rtTpgPro()->category_meta_key ) ?>"/>
                <p class="description"><?php esc_html_e( 'Please add category color for The Post Grid plugins\'s Layout', 'the-post-grid' ); ?></p>
            </td>
        </tr>

		<?php
	}

	/**
	 * Save Category
	 *
	 * @param $term_id
	 *
	 * @return void
	 */
	public function save_termmeta( $term_id ) {
		// Save term color if possible
		if ( isset( $_POST[ rtTpgPro()->category_meta_key ] ) && ! empty( $_POST[ rtTpgPro()->category_meta_key ] ) ) {
			update_term_meta( $term_id, rtTpgPro()->category_meta_key, sanitize_hex_color_no_hash( $_POST[ rtTpgPro()->category_meta_key ] ) );
		} else {
			delete_term_meta( $term_id, rtTpgPro()->category_meta_key );
		}
	}

	/**
	 * Add Category Column
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function edit_term_columns( $columns ) {
		$columns[ rtTpgPro()->category_meta_key ] = esc_html__( 'Color', 'the-post-grid' );

		return $columns;
	}

	/**
	 * @param $out
	 * @param $column
	 * @param $term_id
	 *
	 * @return mixed|string
	 */
	public function manage_term_custom_column( $out, $column, $term_id ) {
		if ( rtTpgPro()->category_meta_key === $column ) {
			$value = get_term_meta( $term_id, rtTpgPro()->category_meta_key, true );
			if ( ! $value ) {
				$value = '';
			}
			$out = sprintf( '<span title="' . esc_attr( 'The Post Grid Category Color' ) . '" class="term-meta-color-block" style="background:#%s;width:30px;height:30px;display: block;border-radius:100px;" ></span>', esc_attr( $value ) );
		}

		return $out;
	}

	/**
	 * Enqueue scripts in admin panel
	 * @return void
	 */
	public function load_custom_wp_admin_style() {
		$color_js_path = rtTpgPro()->get_assets_uri( 'js/rttpg-color-picker.js' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'rttpg-color-picker', $color_js_path, [
			'jquery',
			'wp-color-picker'
		], rtTPG()->options['version'], true );
		?>
        <!-- 
            rttpg-color-picker file code below
            <script>
                $(document).ready(function () {
                    if ($('.rt-color').length) {
                        $('.rt-color').wpColorPicker();
                    }
                });
            </script> 
        -->
		<?php
	}

}

new TaxonomyColorMeta();
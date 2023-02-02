<?php
/**
 * TemplateBuilder Class for Elementor builder
 *
 * The main TemplateBuilder Class.
 *
 * @package  Classifid-listing
 * @since    2.0.10
 */

namespace RT\ThePostGridPro\Controllers\Builder;

use RT\ThePostGridPro\Traits\ELTempleateBuilderTraits;

/**
 * RegisterPostType Class
 */
class TemplateBuilder {


    /**
	 * Elementor Templeate builder post type
	 *
	 * @var string
	 */
	public static $post_type_tb = 'tpg_builder';

   /**
	 * Elementor Templeate builder post type
	 *
	 * @var string
	 */
	public static $post_type_tb = 'tpg_builder';
	/**
	 * Elementor Templeate builder
	 *
	 * @var string
	 */
	public static $template_meta = 'tpg_tb_template';
	/**
	 * Elementor Templeate builder
	 *
	 * @var string
	 */
	public static $tpg_post_types = 'post';

	/**
	 * Listing page id
	 *
	 * @var string
	 */

	/**
	 * Initialize function.
	 *
	 * @return void
	 */
	public static function init() {
		if ( did_action( 'elementor/loaded' ) ) {
			add_action( 'init', [ __CLASS__, 'template_builder_post_type' ], 5 );

            //Add Menu on the top in All post page
			add_filter( 'views_edit-' . self::$post_type_tb, [ __CLASS__, 'template_builder_tabs' ] );
			add_filter( 'parse_query', [ __CLASS__, 'query_filter' ] );
			add_filter( 'post_row_actions', [ __CLASS__, 'filter_post_row_actions' ], 11, 1 );
			// Admin column.
			add_filter( 'manage_edit-' . self::$post_type_tb . '_columns', [ __CLASS__, 'add_new_columns' ] );
			add_action( 'manage_' . self::$post_type_tb . '_posts_custom_column', [ __CLASS__, 'tb_custom_columns' ], 10, 2 );
			add_filter( 'manage_edit-' . self::$post_type_tb . '_sortable_columns', [ __CLASS__, 'register_sortable_columns' ] );
			add_action( 'pre_get_posts', [ __CLASS__, 'sortable_columns_query' ] );
			// Admin column End.
		}

	}

	/**
	 * Register sortablecolumns
	 *
	 * @param [type] $columns column list.
	 *
	 * @return array
	 */
	public static function register_sortable_columns( $columns ) {
		$columns['type'] = 'type';

		return $columns;
	}

	/**
	 * Meta sortable function.
	 *
	 * @param  object  $query  query object.
	 *
	 * @return void
	 */
	public static function sortable_columns_query( $query ) {
		if ( ! is_admin() || ! self::is_current_screen() ) {
			return;
		}
		$orderby = $query->get( 'orderby' );
		if ( 'type' === $orderby ) {
			$query->set( 'meta_key', self::template_type_meta_key() );
			$query->set( 'orderby', 'meta_value' );
		}
	}

	/**
	 * Add new columns to the post table
	 *
	 * @param  Array  $columns  - Current columns on the list post.
	 */
	public static function add_new_columns( $columns ) {
		$column_meta = [
			'type'        => 'Type',
			'set_default' => 'Default',
		];
		$columns     = array_merge(
			array_splice( $columns, 0, 2 ),
			$column_meta,
			$columns
		);

		return $columns;
	}

	/**
	 * Display data in new columns
	 *
	 * @param  string  $column  table column.
	 *
	 * @return void
	 */
	public static function tb_custom_columns( $column, $post_id ) {
		$type = self::builder_type( $post_id );
		switch ( $column ) {
			case 'type':
				echo '<span style="font-weight: 600;">' . esc_html( ucfirst( $type ) ) . '</span>';
				break;
			case 'set_default':
				$is_default = absint( self::builder_page_id( $type ) );
				?>
                <span class="tpgp-switch-wrapper page-type-<?php echo esc_attr( $type ); ?>">
					<label class="switch">
						<input type="hidden" class="template_type" name="template_type" value="<?php echo esc_attr( $type ); ?>">
						<input value="<?php echo absint( $post_id ); ?>" class="set_default" name="set_default" type="checkbox" <?php echo esc_attr( $post_id === $is_default
							? 'checked' : '' ); ?> >
						<span class="slider round"></span>
					</label>
				</span>
				<?php
				break;
		}
	}

	/**
	 * Document edit url.
	 *
	 * Filters the document edit url.
	 */
	public static function get_edit_url( $post ) {
		$url = add_query_arg(
			[
				'post'   => $post->ID,
				'action' => 'elementor',
			],
			admin_url( 'post.php' )
		);

		return $url;
	}

	/**
	 * Post type function
	 *
	 * @return void
	 */
	public static function template_builder_post_type() {

		/**
		 * Elementor Template Builder start
		 */
		$tb_labels = [
			'name'                  => esc_html_x( 'Template Builder', 'Post Type General Name', 'the-post-grid-pro' ),
			'singular_name'         => esc_html_x( 'Template Builder', 'Post Type Singular Name', 'the-post-grid-pro' ),
			'menu_name'             => esc_html__( 'Template Builder', 'the-post-grid-pro' ),
			'name_admin_bar'        => esc_html__( 'Template Builder', 'the-post-grid-pro' ),
			'archives'              => esc_html__( 'Template Archives', 'the-post-grid-pro' ),
			'attributes'            => esc_html__( 'Template Attributes', 'the-post-grid-pro' ),
			'parent_item_colon'     => esc_html__( 'Parent Item:', 'the-post-grid-pro' ),
			'all_items'             => esc_html__( 'Template Builder', 'the-post-grid-pro' ),
			'add_new_item'          => esc_html__( 'Add New Template', 'the-post-grid-pro' ),
			'add_new'               => esc_html__( 'Add New', 'the-post-grid-pro' ),
			'new_item'              => esc_html__( 'New Template', 'the-post-grid-pro' ),
			'edit_item'             => esc_html__( 'Edit Template', 'the-post-grid-pro' ),
			'update_item'           => esc_html__( 'Update Template', 'the-post-grid-pro' ),
			'view_item'             => esc_html__( 'View Template', 'the-post-grid-pro' ),
			'view_items'            => esc_html__( 'View Templates', 'the-post-grid-pro' ),
			'search_items'          => esc_html__( 'Search Templates', 'the-post-grid-pro' ),
			'not_found'             => esc_html__( 'Not found', 'the-post-grid-pro' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'the-post-grid-pro' ),
			'featured_image'        => esc_html__( 'Featured Image', 'the-post-grid-pro' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'the-post-grid-pro' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'the-post-grid-pro' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'the-post-grid-pro' ),
			'insert_into_item'      => esc_html__( 'Insert into Template', 'the-post-grid-pro' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this Template', 'the-post-grid-pro' ),
			'items_list'            => esc_html__( 'Templates list', 'the-post-grid-pro' ),
			'items_list_navigation' => esc_html__( 'Templates list navigation', 'the-post-grid-pro' ),
			'filter_items_list'     => esc_html__( 'Filter from list', 'the-post-grid-pro' ),
		];

		$tb_args = [
			'label'              => esc_html__( 'Template Builder', 'the-post-grid-pro' ),
			'description'        => esc_html__( 'The Post Grid Template', 'the-post-grid-pro' ),
			'labels'             => $tb_labels,
			'supports'           => [ 'title', 'editor', 'elementor', 'author', 'permalink' ],
			'menu_icon'          => 'dashicons-grid-view',
			'menu_position'      => 6,
			'hierarchical'       => false,
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=' . 'rttpg',
			'show_in_admin_bar'  => false,
			'show_in_nav_menus'  => true,
			'can_export'         => true,
			'has_archive'        => false,
			'rewrite'            => [
				'slug'       => 'tpg-template',
				'pages'      => false,
				'with_front' => true,
				'feeds'      => false,
			],
			'query_var'          => true,
			'publicly_queryable' => true,
			'capability_type'    => 'page',
			'show_in_rest'       => true,
			'rest_base'          => self::$post_type_tb,
		];

		$tb_args = apply_filters( 'tpg_register_template_builder_args', $tb_args );

		register_post_type( self::$post_type_tb, $tb_args );
		/**
		 * Elementor Template Builder End
		 */

		$cpt_support = get_option( 'elementor_cpt_support' );

		if ( is_array( $cpt_support ) && ! in_array( self::$post_type_tb, $cpt_support ) ) {
			$cpt_support[] = self::$post_type_tb;
			update_option( 'elementor_cpt_support', $cpt_support );
		}

		if ( ! get_option( 'tpg_flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
			update_option( 'tpg_flush_rewrite_rules', 1 );
		}

	}

	/**
	 * Print the tab for Template builder.
	 *
	 * @return string
	 */
	public static function template_builder_tabs( $views ) {
		$template_type = isset( $_GET['template_type'] ) ? sanitize_key( wp_unslash( $_GET['template_type'] ) ) : '';
		?>
        <div id="tpgp-template-tabs-wrapper" class="nav-tab-wrapper" style="margin: 15px 0;">
            <a class="nav-tab <?php echo empty( $template_type ) ? 'nav-tab-active' : ''; ?>"
               href="edit.php?post_type=<?php echo esc_attr( self::$post_type_tb ); ?>"><?php esc_html_e( 'All', 'the-post-grid-pro' ); ?></a>
            <a class="nav-tab <?php echo 'single' === $template_type ? 'nav-tab-active' : ''; ?> "
               href="edit.php?post_type=<?php echo esc_attr( self::$post_type_tb ); ?>&amp;template_type=single"> <?php esc_html_e( 'Single', 'the-post-grid-pro' ); ?></a>
            <a class="nav-tab <?php echo 'archive' === $template_type ? 'nav-tab-active' : ''; ?>"
               href="edit.php?post_type=<?php echo esc_attr( self::$post_type_tb ); ?>&amp;template_type=archive"> <?php esc_html_e( 'Post Archive', 'the-post-grid-pro' ); ?></a>
            <a class="nav-tab <?php echo 'author-archive' === $template_type ? 'nav-tab-active' : ''; ?>"
               href="edit.php?post_type=<?php echo esc_attr( self::$post_type_tb ); ?>&amp;template_type=author-archive"> <?php esc_html_e( 'Author Archive',
					'the-post-grid-pro' ); ?></a>
            <a class="nav-tab <?php echo 'search-archive' === $template_type ? 'nav-tab-active' : ''; ?>"
               href="edit.php?post_type=<?php echo esc_attr( self::$post_type_tb ); ?>&amp;template_type=search-archive"> <?php esc_html_e( 'Search Archive',
					'the-post-grid-pro' ); ?></a>
            <a class="nav-tab <?php echo 'date-archive' === $template_type ? 'nav-tab-active' : ''; ?>"
               href="edit.php?post_type=<?php echo esc_attr( self::$post_type_tb ); ?>&amp;template_type=date-archive"> <?php esc_html_e( 'Date Archive',
					'the-post-grid-pro' ); ?></a>
            <a class="nav-tab <?php echo 'category-archive' === $template_type ? 'nav-tab-active' : ''; ?>"
               href="edit.php?post_type=<?php echo esc_attr( self::$post_type_tb ); ?>&amp;template_type=category-archive"> <?php esc_html_e( 'Category Archive',
					'the-post-grid-pro' ); ?></a>
            <a class="nav-tab <?php echo 'tags-archive' === $template_type ? 'nav-tab-active' : ''; ?>"
               href="edit.php?post_type=<?php echo esc_attr( self::$post_type_tb ); ?>&amp;template_type=tags-archive"> <?php esc_html_e( 'Tags Archive',
					'the-post-grid-pro' ); ?></a>

        </div>
		<?php
		return $views;
	}

	/**
	 * Manage Template filter by template type
	 *
	 * @param  \WP_Query  $query  WordPress main query.
	 *
	 * @return void
	 */
	public static function query_filter( \WP_Query $query ) {
		if ( ! is_admin() || ! self::is_current_screen() || ! empty( $query->query_vars['meta_key'] ) ) {
			return;
		}
		if ( isset( $_GET['template_type'] ) && ( '' !== $_GET['template_type'] || 'all' !== $_GET['template_type'] ) ) {
			$type                              = isset( $_GET['template_type'] ) ? sanitize_key( $_GET['template_type'] ) : '';
			$query->query_vars['meta_key']     = self::template_type_meta_key();
			$query->query_vars['meta_value']   = $type;
			$query->query_vars['meta_compare'] = '=';
		}
	}

	/**
	 * Add/Remove edit link in dashboard.
	 *
	 * Add or remove an edit link to the post/page action links on the post/pages list table.
	 *
	 * Fired by `post_row_actions` and `page_row_actions` filters.
	 *
	 * @access public
	 *
	 * @param  array  $actions  An array of row action links.
	 *
	 * @return array An updated array of row action links.
	 */
	public static function filter_post_row_actions( $actions ) {
		if ( self::is_current_screen() ) {
			global $post;
			unset( $actions['edit'] );
			$new_items['edit_with_elementor'] = sprintf(
				'<a href="%1$s" data-post-id="%3$s" >%2$s</a>',
				self::get_edit_url( $post ),
				__( 'Edit with Elementor', 'the-post-grid-pro' ),
				$post->ID
			);
			$actions                          = array_merge( $new_items, $actions );
		}

		return $actions;
	}

	/**
	 * Check template screen
	 *
	 * @return boolean
	 */
	public static function is_current_screen() {
		global $pagenow, $typenow;

		return 'edit.php' === $pagenow && self::$post_type_tb === $typenow;
	}


    public static function builder_page_id( $type ) {
		return get_option( self::option_name( $type ) );
	}

	/**
	 * Elementor Templeate builder
	 *
	 * @var string
	 */
	public static function option_name( $type ) {
		return self::$template_meta . '_default_' . $type;  //@id = tpg_tb_template_default_[archive | author_archive]
	}

	/**
	 * Elementor Templeate builder
	 *
	 * @var string
	 */
	public static function builder_type( $post_id ) {
		return get_post_meta( $post_id, self::template_type_meta_key(), true ); //Meta key = tpg_tb_template_type
	}

	/**
	 * Elementor check archive builder is active or not
	 *
	 * @var string
	 */
	public static function is_builder_page_archive() {

		if ( in_array( self::builder_type( get_the_ID() ), [ 'archive', 'author-archive', 'search-archive', 'date-archive', 'category-archive', 'tags-archive' ] )
		     || ( self::builder_page_id( self::builder_type( get_the_ID() ) ) && ( is_post_type_archive( self::$tpg_post_types ) ) )
		     || is_tax( get_object_taxonomies( self::$tpg_post_types ) )
		     || is_tag() || is_category() || is_author() || is_date() || is_search()
			 || (!is_front_page() && is_home())
		) {
			return true;
		}

		return false;
	}



	/**
	 * Elementor Templeate builder
	 *
	 * @var string
	 */
	public static function is_builder_page_single() {
		if ( 'single' == self::builder_type( get_the_ID() ) || ( self::builder_page_id( 'single' ) && is_singular( self::$tpg_post_types ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Elementor Templeate builder
	 *
	 * @var string
	 */
	public static function template_type_meta_key() {
		return self::$template_meta . '_type';
	}

	/**
	 * Get builder content function
	 *
	 * @param [type]  $template_id builder Template id.
	 * @param  boolean  $with_css  with css.
	 *
	 * @return mixed
	 */
	public static function get_builder_content( $template_id, $with_css = false ) {
		return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, $with_css );
	}

}

<?php
/**
 * denver functions and definitions
 *
 * @package denver
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$denver_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/jetpack.php',                         // Load Jetpack compatibility file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker.
	'/woocommerce.php',                     // Load WooCommerce functions.
	'/editor.php',                          // Load Editor functions.
);

foreach ( $denver_includes as $file ) {
	$filepath = locate_template( '/inc' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating /inc%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}

function denver_loop_shop_columns( $nc ) {
	return 3;
}

add_filter( 'loop_shop_columns', 'denver_loop_shop_columns' );

/*
function denver_woocommerce_product_query($wq){
	$wq->set('post__not_in',array(21,22));
	return $wq;
}
add_filter('woocommerce_product_query','denver_woocommerce_product_query');*/

function denver_woocommerce_product_query( $wq ) {
	$tax_query   = (array) $wq->get( 'tax_query' );
	$tax_query[] = array(
		'taxonomy' => 'product_cat',
		'field'    => 'slug',
		'terms'    => array( 'accessories' ),
		'operator' => 'NOT IN'
	);
	$wq->set( 'tax_query', $tax_query );

	return $wq;
}

add_filter( 'woocommerce_product_query', 'denver_woocommerce_product_query' );


function denver_woocommerce_before_shop_loop() {
	$term_id   = get_queried_object()->term_id;
	$parent_id = get_queried_object()->parent;

	if($term_id == $parent_id) {
		echo "<ul class='products columns-3'>";
		woocommerce_output_product_categories();
		echo "</ul>";
	}

	if ( $parent_id > 0 ) {
		$term_id = $parent_id;
	}
	if ( $term_id ) {
		echo "<ul class='products columns-3'>";
		woocommerce_output_product_categories( array(
			'parent_id' => $term_id
		) );
		echo "</ul>";
	}
	echo "<div class='clearfix'></div>";
}

function denver_woocommerce_before_shop_loop_jg(){
    if(isset($_GET['cg']) && $_GET['cg']==1) {
	    $cat_args = array(
		    'orderby'    => 'name',
		    'order'      => 'asc',
		    'hide_empty' => true,
	    );

	    $product_categories = get_terms( 'product_cat', $cat_args );
	    ?>
        <div id="justified_gallery" class="justified-category-list js-justifyGallery">
		    <?php foreach ( $product_categories as $product_category ) : ?>

			    <?php
			    $thumbnail_id = get_woocommerce_term_meta( $product_category->term_id, 'thumbnail_id', true );
			    $thumbnail    = wp_get_attachment_image_url( $thumbnail_id, 'large' );

			    if ( ! $thumbnail ) {
				    continue;
			    }
			    ?>

                <a href="<?php echo esc_url( get_term_link( $product_category, 'product_cat' ) ); ?>">
				    <?php if ( $thumbnail ) : ?>
                        <img alt="<?php echo esc_attr( $product_category->name ); ?>"
                             src="<?php echo esc_url( $thumbnail ); ?>">
				    <?php endif; ?>

                    <div class="contents">
					    <?php if ( $product_category->name ) : ?>
                            <h2><?php echo esc_html( $product_category->name ); ?></h2>
					    <?php endif; ?>
					    <?php if ( $product_category->description ) : ?>
                            <p><?php echo esc_html( $product_category->description ); ?></p>
					    <?php endif; ?>
                    </div>
                </a>

		    <?php endforeach; ?>
        </div>
	    <?php
    }
}
add_action( 'woocommerce_before_shop_loop', 'denver_woocommerce_before_shop_loop_jg', 8 );

function denver_woocommerce_before_main_content() {
	echo "Before Everything";
}

add_action( 'woocommerce_before_main_content', 'denver_woocommerce_before_main_content', 19 );

function denver_woocommerce_product_query2($wcq){
    if(isset($_GET['wcpagination']) && $_GET['wcpagination']==0){
        $wcq->set('posts_per_page',-1);
    }
    return $wcq;
}
add_action('woocommerce_product_query','denver_woocommerce_product_query2');


function denver_loop_shop_columns2($nc){

	if(isset($_GET['nc']) && $_GET['nc']>0){
		$nc =  sanitize_text_field($_GET['nc']);
	}
	return $nc;
}
add_action('loop_shop_columns','denver_loop_shop_columns2');

function devnver_woocommerce_checkout_fields($fields){

    /*echo "<pre>";
    print_r($fields);
    echo "</pre>";*/
	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_address_1']);
	unset($fields['billing']['billing_address_2']);
	unset($fields['billing']['billing_city']);
	unset($fields['billing']['billing_postcode']);
	unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_state']);

	unset($fields['shipping']['shipping_company']);
	unset($fields['shipping']['shipping_address_1']);
	unset($fields['shipping']['shipping_address_2']);
	unset($fields['shipping']['shipping_city']);
	unset($fields['shipping']['shipping_postcode']);
	unset($fields['shipping']['shipping_country']);
	unset($fields['shipping']['shipping_state']);
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'devnver_woocommerce_checkout_fields');
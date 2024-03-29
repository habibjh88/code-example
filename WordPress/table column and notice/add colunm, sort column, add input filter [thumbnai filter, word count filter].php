<?php
/*
Plugin Name: Column Demo
Plugin URI:
Description:
Version: 1.0
Author: Habib
Author URI: http://habibportfolio.com
License: GPLv2 or later
Text Domain: column-demo
Domain Path: /languages/
*/


//Add New Column
//=========================================

function coldemo_post_columns( $columns ) {
	unset( $columns['tags'] );
	unset( $columns['comments'] );
	/*unset($columns['author']);
	unset($columns['date']);
	$columns['author']="Author";
	$columns['date']="Date";*/
	$columns['id']        = __( 'Post ID', 'column-demo' );
	$columns['thumbnail'] = __( 'Thumbnail', 'column-demo' );
	$columns['wordcount'] = __( 'Word Count', 'column-demo' );

	return $columns;
}

add_filter( 'manage_posts_columns', 'coldemo_post_columns' );
add_filter( 'manage_pages_columns', 'coldemo_post_columns' );
//For CPT = tpg_section
add_filter( 'manage_edit-tpg_section_columns', 'coldemo_post_columns' );



//Insert Data in column
//=========================================

function coldemo_post_column_data( $column, $post_id ) {
	if ( 'id' == $column ) {
		echo $post_id;
	} elseif ( 'thumbnail' == $column ) {
		$thumbnail = get_the_post_thumbnail( $post_id, array( 100, 100 ) );
		echo $thumbnail;
	} elseif ( 'wordcount' == $column ) {
		/*$_post = get_post($post_id);
		$content = $_post->post_content;
		$wordn = str_word_count(strip_tags($content));*/
		$wordn = get_post_meta( $post_id, 'wordn', true );
		echo $wordn;
	}
}

add_action( 'manage_posts_custom_column', 'coldemo_post_column_data', 10, 2 );
add_action( 'manage_pages_custom_column', 'coldemo_post_column_data', 10, 2 );
//For CPT = tpg_section
add_action( 'manage_tpg_section_posts_custom_column', 'coldemo_post_column_data', 10, 2 );

//Sortable Column
//=========================================

function coldemo_sortable_column( $columns ) {
	$columns['wordcount'] = 'wordn';

	return $columns;
}

add_filter( 'manage_edit-post_sortable_columns', 'coldemo_sortable_column' );

/*function coldemo_set_word_count() {
	$_posts = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => 'post',
		'post_status'    => 'any'
	) );

	foreach ( $_posts as $p ) {
		$content = $p->post_content;
		$wordn   = str_word_count( strip_tags( $content ) );
		update_post_meta( $p->ID, 'wordn', $wordn );
	}
}

add_action( 'init', 'coldemo_set_word_count' );*/

function coldemo_sort_column_data( $wpquery ) {
	if ( ! is_admin() ) {
		return;
	}

	$orderby = $wpquery->get( 'orderby' );
	if ( 'wordn' == $orderby ) {
		$wpquery->set( 'meta_key', 'wordn' );
		$wpquery->set( 'orderby', 'meta_value_num' );
	}
}

add_action( 'pre_get_posts', 'coldemo_sort_column_data' );

function coldemo_update_wordcount_on_post_save( $post_id ) {
	$p       = get_post( $post_id );
	$content = $p->post_content;
	$wordn   = str_word_count( strip_tags( $content ) );
	update_post_meta( $p->ID, 'wordn', $wordn );
}

add_action( 'save_post', 'coldemo_update_wordcount_on_post_save' );


//Column Filter
//=========================================

//Pull some posts by select
function coldemo_filter() {
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'post' ) { //display only on posts page
		return;
	}
	$filter_value = isset( $_GET['DEMOFILTER'] ) ? $_GET['DEMOFILTER'] : '';
	$values       = array(
		'0' => __( 'Select Status', 'column_demo' ),
		'1' => __( 'Some Posts', 'column_demo' ),
		'2' => __( 'Some Posts++', 'column_demo' ),
	);
	?>
    <select name="DEMOFILTER">
		<?php
		foreach ( $values as $key => $value ) {
			printf( "<option value='%s' %s>%s</option>", $key,
				$key == $filter_value ? "selected = 'selected'" : '',
				$value
			);
		}
		?>
    </select>
	<?php
}


add_action( 'restrict_manage_posts', 'coldemo_filter' );

function coldemo_filter_data( $wpquery ) {
	if ( ! is_admin() ) {
		return;
	}

	$filter_value = isset( $_GET['DEMOFILTER'] ) ? $_GET['DEMOFILTER'] : '';
	if ( '1' == $filter_value ) {
		$wpquery->set( 'post__in', array( 18, 8, 1 ) );
	} else if ( '2' == $filter_value ) {
		$wpquery->set( 'post__in', array( 46, 52, 88 ) );
	}


}

add_action( 'pre_get_posts', 'coldemo_filter_data' );



//Thumbnail filter 
//=========================================

function coldemo_thumbnail_filter() {
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'post' ) { //display only on posts page
		return;
	}


	$filter_value = isset( $_GET['THFILTER'] ) ? $_GET['THFILTER'] : '';
	$values       = array(
		'0' => __( 'Thumbnail Status', 'column_demo' ),
		'1' => __( 'Has Thumbnail', 'column_demo' ),
		'2' => __( 'No Thumbnail', 'column_demo' ),
	);
	?>
    <select name="THFILTER">
		<?php
		foreach ( $values as $key => $value ) {
			printf( "<option value='%s' %s>%s</option>", $key,
				$key == $filter_value ? "selected = 'selected'" : '',
				$value
			);
		}
		?>
    </select>
	<?php
}

add_action( 'restrict_manage_posts', 'coldemo_thumbnail_filter' );

function coldemo_thumbnail_filter_data( $wpquery ) {
	if ( ! is_admin() ) {
		return;
	}


	$filter_value = isset( $_GET['THFILTER'] ) ? $_GET['THFILTER'] : '';

	if ( '1' == $filter_value ) {
		$wpquery->set( 'meta_query', array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS'
			)
		) );
	} else if ( '2' == $filter_value ) {
		$wpquery->set( 'meta_query', array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'NOT EXISTS'
			)
		) );
	}


}

add_action( 'pre_get_posts', 'coldemo_thumbnail_filter_data' );



//Word Count Filter
//=========================================

function coldemo_wc_filter() {
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'post' ) { //display only on posts page
		return;
	}


	$filter_value = isset( $_GET['WCFILTER'] ) ? $_GET['WCFILTER'] : '';
	$values       = array(
		'0' => __( 'Word Count', 'column_demo' ),
		'1' => __( 'Above 400', 'column_demo' ),
		'2' => __( '200 to 400', 'column_demo' ),
		'3' => __( 'Below 200', 'column_demo' ),
	);
	?>
    <select name="WCFILTER">
		<?php
		foreach ( $values as $key => $value ) {
			printf( "<option value='%s' %s>%s</option>", $key,
				$key == $filter_value ? "selected = 'selected'" : '',
				$value
			);
		}
		?>
    </select>
	<?php
}

add_action( 'restrict_manage_posts', 'coldemo_wc_filter' );

function coldemo_wc_filter_data( $wpquery ) {
	if ( ! is_admin() ) {
		return;
	}


	$filter_value = isset( $_GET['WCFILTER'] ) ? $_GET['WCFILTER'] : '';

	if ( '1' == $filter_value ) {
		$wpquery->set( 'meta_query', array(
			array(
				'key'     => 'wordn',
				'value'   => 400,
				'compare' => '>=',
				'type'    => 'NUMERIC'
			)
		) );
	} else if ( '2' == $filter_value ) {
		$wpquery->set( 'meta_query', array(
			array(
				'key'     => 'wordn',
				'value'   => array(200,400),
				'compare' => 'BETWEEN',
				'type'    => 'NUMERIC'
			)
		) );
	} else if ( '3' == $filter_value ) {
		$wpquery->set( 'meta_query', array(
			array(
				'key'     => 'wordn',
				'value'   => 200,
				'compare' => '<=',
				'type'    => 'NUMERIC'
			)
		) );
	}


}

add_action( 'pre_get_posts', 'coldemo_wc_filter_data' );

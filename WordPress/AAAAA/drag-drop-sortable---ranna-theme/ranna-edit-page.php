<?php
/*
 * Template Name: Recipe Edit Page
 * * Source from Ranna Theme
===============================
 */

global $post, $current_user;
$current_user = wp_get_current_user();


if ( ! ( is_user_logged_in() ) ) {
	wp_redirect( home_url() );
	exit;
}

if ( empty( $_GET["rec"] ) ) {
	wp_redirect( home_url() );
	exit;
} else {
	$get_recipe = htmlspecialchars( $_GET["rec"] );
}

$recipe_id = $_GET["rec"];


$publish_option = RannaTheme::$options['recipe_publish_option'];

//get the current user info
if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	if ( ! $current_user->exists() ) {
		return;
	}
}

if ( ! empty( $current_user->user_firstname ) ) {
	$name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
} else {
	$name = $current_user->display_name;
}

// Layout class
if ( RannaTheme::$layout == 'full-width' ) {
	$ranna_layout_class = 'col-sm-9 col-12 offset-sm-1';
} else {
	$ranna_layout_class = RannaTheme_Helper::has_active_widget();
}

$recipe_categorys = get_terms( array(
	'taxonomy'   => 'ranna_recipe_category',
	'hide_empty' => false,
	'orderby'    => 'count'
) );

$recipe_cuisines = get_terms( array(
	'taxonomy'   => 'ranna_recipe_cuisine',
	'hide_empty' => false,
	'orderby'    => 'count'
) );

$recipe_all_tags = get_terms( array(
	'taxonomy'   => 'rn_recipe_tag',
	'hide_empty' => false,
	'orderby'    => 'count'
) );

/*
* Form Process here
*/
if ( isset( $_POST['recipe_title'] ) ) {
	$recipe_title        = sanitize_text_field( $_POST['recipe_title'] );
	$preptime            = sanitize_text_field( $_POST['preptime'] );
	$cooktime            = sanitize_text_field( $_POST['cooktime'] );
	$readytime           = sanitize_text_field( $_POST['readytime'] );
	$servepeople         = sanitize_text_field( $_POST['servepeople'] );
	$servesize           = sanitize_text_field( $_POST['servesize'] );
	$direction_short_des = sanitize_text_field( $_POST['direction_short_des'] );

	$rn_recipe_direction_conclusion = sanitize_text_field( $_POST['rn_recipe_direction_conclusion'] );

	$content = esc_textarea( $_POST['frontend_recipe_content'] );


	// ADD THE FORM INPUT TO $new_post ARRAY
	$new_post = array(
		'ID'           => $get_recipe,
		'post_title'   => $recipe_title,
		'post_status'  => $publish_option,           // Choose: publish, preview, future, draft, etc.
		'post_type'    => 'ranna_recipe',
		'post_content' => $content,
	);

	//SAVE THE POST
	$updated_item = wp_update_post( $new_post );


	/*category field sanitization ( recipe category )*/

	// if (!empty($recipe_categorys)) {
	$valid_cat_values = array();

	if ( ! empty( $recipe_categorys ) && ! is_wp_error( $recipe_categorys ) ) {
		foreach ( $recipe_categorys as $recipe_category ) {
			$valid_cat_values[] = $recipe_category->term_id;
		}
	}


	$given_cat_value = isset( $_POST['recipe_category_post'] ) ? $_POST['recipe_category_post'] : '';


	if ( ! empty( $given_cat_value ) ) {


		$recipe_remove_terms = array();

		$terms = get_the_terms( $recipe_id, 'ranna_recipe_category' );

		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$recipe_remove_terms[] = absint( $term->term_id );
			}
		}

		wp_remove_object_terms( $recipe_id, $recipe_remove_terms, 'ranna_recipe_category' );

		wp_set_post_terms( $recipe_id, $given_cat_value, 'ranna_recipe_category' );
	}


	/*Cuisine field sanitization ( recipe cuisine )*/
	if ( ! empty( $recipe_cuisines ) ) {
		$valid_cuisine_values = array();

		if ( ! empty( $recipe_cuisines ) && ! is_wp_error( $recipe_cuisines ) ) {
			foreach ( $recipe_cuisines as $recipe_cuisine ) {
				$valid_cuisine_values[] = $recipe_cuisine->term_id;
			}
		}

		$given_cuisine_value = sanitize_text_field( $_POST['recipe_cuisine'] );
		if ( ! empty( $given_cuisine_value ) ) {
			if ( in_array( $given_cuisine_value, $valid_cuisine_values ) ) {
				wp_set_post_terms( $updated_item, array( $given_cuisine_value ), 'ranna_recipe_cuisine' );
			}
		}
	}

	/*TAG field sanitization ( recipe Tag )*/

	$given_tag_value = isset( $_POST['recipe_post_tag'] ) ? $_POST['recipe_post_tag'] : '';

	if ( ! empty( $given_tag_value ) ) {

		$recipe_remove_terms_tag = array();

		$recipe_tag_terms = get_the_terms( $recipe_id, 'rn_recipe_tag' );

		if ( $recipe_tag_terms && ! is_wp_error( $recipe_tag_terms ) ) {
			foreach ( $recipe_tag_terms as $term_tag ) {
				$recipe_remove_terms_tag[] = absint( $term_tag->term_id );
			}
		}

		wp_remove_object_terms( $recipe_id, $recipe_remove_terms_tag, 'rn_recipe_tag' );

		$given_tag_value_final = array_map( 'absint', $given_tag_value );

		wp_set_post_terms( $recipe_id, $given_tag_value_final, 'rn_recipe_tag' );
	}

	/*category field sanitization ( difficulty )*/
	$valid_diff_values = array( 'easy', 'medium', 'hard' );

	$given_diff_value = sanitize_text_field( $_POST['recipe_difficulty'] );

	if ( in_array( $given_diff_value, $valid_diff_values ) ) {
		update_post_meta( $updated_item, 'rn_recipe_difficulty', $given_diff_value );
	}

	// save the post meta here
	update_post_meta( $updated_item, 'rn_recipe_prep_time', $preptime );
	update_post_meta( $updated_item, 'rn_recipe_cook_time', $cooktime );
	update_post_meta( $updated_item, 'rn_recipe_ready_in', $readytime );
	update_post_meta( $updated_item, 'rn_recipe_serving_people', $servepeople );
	update_post_meta( $updated_item, 'rn_recipe_serving_size', $servesize );

	/*Insert ingrdeint*/
	if ( ! empty( $_POST['rn_recipes'] ) ) {
		update_post_meta( $updated_item, '_rn_recipes', $_POST['rn_recipes'] );
	}
	if ( ! empty( $_POST['ingredient_item'] ) ) {
		for ( $i = 0; $i < count( $_POST['ingredient_item'] ); $i ++ ) {

			$ingredients[ $i ]['ingredient_quantity'] = ( ! empty( $_POST['ingredient_quantity'][ $i ] ) ) ? $_POST['ingredient_quantity'][ $i ] : '';
			$ingredients[ $i ]['ingredient_item']     = ( ! empty( $_POST['ingredient_item'][ $i ] ) ) ? $_POST['ingredient_item'][ $i ] : '';
			$ingredients[ $i ]['ingredient_unit']     = ( ! empty( $_POST['ingredient_unit'][ $i ] ) ) ? $_POST['ingredient_unit'][ $i ] : '';

			update_post_meta( $updated_item, 'rn_recipe_ingredient_list', $ingredients );
		}
	}

	/*Insert nutrition*/
	if ( ! empty( $_POST['nutrition_item'] ) ) {
		//print_r($_POST['nutrition_item']);
		//die();
		for ( $i = 0; $i < count( $_POST['nutrition_item'] ); $i ++ ) {
			if ( ! empty( $_POST['nutrition_item'][ $i ] ) ) {
				$nutrition[ $i ]['nutritions_item']            = ( ! empty( $_POST['nutrition_item'][ $i ] ) ) ? $_POST['nutrition_item'][ $i ] : '';
				$nutrition[ $i ]['nutritions_item_percentage'] = ( ! empty( $_POST['nutrition_daily_value'][ $i ] ) ) ? $_POST['nutrition_daily_value'][ $i ] : '';

				update_post_meta( $updated_item, 'rn_recipe_nutritions_list', $nutrition );
			}
		}
	}

	/*Step Short description*/
	update_post_meta( $updated_item, 'rn_recipe_direction_text', $direction_short_des );

	if ( isset( $_FILES['recipe_feature_image']['name'] ) && ! empty( $_FILES['recipe_feature_image']['name'] ) ) {

		/*Insert Featured Image*/
		$theFile  = $_FILES['recipe_feature_image'];
		$fileName = $_FILES["recipe_feature_image"]["name"];
		$tempFile = $_FILES["recipe_feature_image"]["tmp_name"];

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$upload = wp_handle_upload( $theFile, array( 'test_form' => false ) );

		$wp_filetype = wp_check_filetype( basename( $upload['file'] ), null );
		$attachment  = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $fileName ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id   = wp_insert_attachment( $attachment, $upload['file'], $updated_item );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		set_post_thumbnail( $updated_item, $attach_id );
	}

	/*TODO: multi upload by the DIRECTION*/


	$recipe_directions = get_post_meta( $updated_item, 'rn_recipe_direction_list', true );

	$recipe_directions = is_array( $recipe_directions ) ? $recipe_directions : [];


	if ( ! empty( $_POST['recipe_direction'] ) ) {
		$new_recipe_directions = [];
		$raw_rds               = map_deep( $_POST['recipe_direction'], 'sanitize_text_field' );
		$raw_rd_images         = $_FILES['recipe_direction'];
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
		}
		$wp_upload_dir = wp_upload_dir();

		foreach ( $raw_rds as $key => $raw_rd ) {

			if ( isset( $raw_rd['text'] ) && ! empty( $raw_rd['text'] ) ) {
				$new_recipe_directions[ $key ]['direction_text'] = $raw_rd['text'];
			}

			if ( isset( $raw_rd['video_type'] ) && ! empty( $raw_rd['video_type'] ) ) {
				$new_recipe_directions[ $key ]['video_type'] = $raw_rd['video_type'];
			}

			if ( isset( $raw_rd['url'] ) && ! empty( $raw_rd['url'] ) ) {
				$new_recipe_directions[ $key ]['url'] = $raw_rd['url'];
			}

			$temp_file = isset( $raw_rd_images['name'][ $key ]['img'] ) && ! empty( $raw_rd_images['name'][ $key ]['img'] ) ? [
				'name'     => $raw_rd_images['name'][ $key ]['img'],
				'type'     => $raw_rd_images['type'][ $key ]['img'],
				'tmp_name' => $raw_rd_images['tmp_name'][ $key ]['img'],
				'error'    => $raw_rd_images['error'][ $key ]['img'],
				'size'     => $raw_rd_images['size'][ $key ]['img']
			] : '';

			if ( ! empty( $temp_file ) ) {
				$status = wp_handle_upload( $temp_file, array( 'test_form' => false ) );
				if ( $status && ! isset( $status['error'] ) ) {

					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
					require_once( ABSPATH . 'wp-admin/includes/media.php' );

					$fileName   = $status['file'];
					$fileType   = wp_check_filetype( basename( $fileName ), null );
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $fileName ),
						'post_mime_type' => $fileType['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $fileName ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);
					$attach_id  = wp_insert_attachment( $attachment, $fileName, $updated_item );
					wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $fileName ) );
					$new_recipe_directions[ $key ]['direction_img'] = $attach_id;
				}
			} elseif ( isset( $recipe_directions[ $key ]['direction_img'] ) ) {
				$new_recipe_directions[ $key ]['direction_img'] = $recipe_directions[ $key ]['direction_img'];
			}


			$temp_video = isset( $raw_rd_images['name'][ $key ]['self_video'] ) && ! empty( $raw_rd_images['name'][ $key ]['self_video'] ) ? [
				'name'     => $raw_rd_images['name'][ $key ]['self_video'],
				'type'     => $raw_rd_images['type'][ $key ]['self_video'],
				'tmp_name' => $raw_rd_images['tmp_name'][ $key ]['self_video'],
				'error'    => $raw_rd_images['error'][ $key ]['self_video'],
				'size'     => $raw_rd_images['size'][ $key ]['self_video']
			] : '';


			if ( ! empty( $temp_video ) ) {
				$status = wp_handle_upload( $temp_video, array( 'test_form' => false ) );

				if ( $status && ! isset( $status['error'] ) ) {

					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
					require_once( ABSPATH . 'wp-admin/includes/media.php' );

					$fileName   = $status['file'];
					$fileType   = wp_check_filetype( basename( $fileName ), null );
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $fileName ),
						'post_mime_type' => $fileType['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $fileName ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);
					$attach_id  = wp_insert_attachment( $attachment, $fileName, $updated_item );
					wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $fileName ) );
					$new_recipe_directions[ $key ]['direction_self_video'] = $attach_id;
				}
			} elseif ( isset( $recipe_directions[ $key ]['direction_self_video'] ) ) {
				$new_recipe_directions[ $key ]['direction_self_video'] = $recipe_directions[ $key ]['direction_self_video'];
			}


		}
		update_post_meta( $updated_item, 'rn_recipe_direction_list', $new_recipe_directions );
	}

	update_post_meta( $updated_item, 'rn_recipe_direction_conclusion', $rn_recipe_direction_conclusion );

	//REDIRECT TO THE NEW POST ON SAVE
	$link = site_url() . '/my-recipe-page'; // Reditect to the published recipe page
	//$link = get_post_permalink( $updated_item, false, false );
//    wp_redirect($link);
}

?>
<?php get_header(); ?>

    <div id="primary" class="content-area">
        <div class="container">
            <div class="row gutters-60">
				<?php if ( RannaTheme::$layout == 'left-sidebar' ) {
					get_sidebar();
				} ?>
                <div class="<?php echo esc_attr( $ranna_layout_class ); ?>">
                    <main id="main" class="site-main">

                        <form id="front-end-form" class="submit-recipe-form" enctype="multipart/form-data" method="post"
                              action="">
							<?php
							$args = array(
								'post_type'      => 'ranna_recipe',
								'p'              => $get_recipe,
								'posts_per_page' => 1,
								'post_status'    => array( 'publish', 'draft' ),
							);

							$query = new WP_Query( $args );

							if ( $query->have_posts() ) {
							while ( $query->have_posts() ) {
							$query->the_post();
							?>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Recipe Title:', 'ranna-core' ); ?></label>
                                <div class="col-sm-9">
                                    <input type="text" value="<?php the_title(); ?>" placeholder="Recipe Name"
                                           class="form-control" name="recipe_title" required/>
                                </div>
                            </div>

							<?php
							$recipe_cat_id = array();
							$terms         = get_the_terms( $recipe_id, 'ranna_recipe_category' );
							if ( $terms && ! is_wp_error( $terms ) ) {
								foreach ( $terms as $term ) {
									$recipe_cat_id[] = $term->term_id;
								}
							}
							?>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Choose Category:', 'ranna-core' ); ?></label>
                                <div class="col-sm-9">
                                    <select class="select2 ranna-select-2" name="recipe_category_post[]" multiple>
										<?php
										$i = 0;
										if ( ! empty( $recipe_categorys ) && ! is_wp_error( $recipe_categorys ) ) {
											foreach ( $recipe_categorys as $recipe_category ) {

												?>
                                                <option
													<?php if ( in_array( $recipe_category->term_id, $recipe_cat_id ) ) { ?>selected<?php } ?>
                                                    value="<?php echo esc_attr( $recipe_category->term_id ); ?>"><?php echo esc_html( $recipe_category->name ); ?></option>
												<?php $i ++;
											}
										}
										?>
                                    </select>
                                </div>
                            </div>

							<?php
							$recipe_cuisine_id    = 0;
							$recipe_cuisine_terms = get_the_terms( $recipe_id, 'ranna_recipe_cuisine' );
							if ( $recipe_cuisine_terms && ! is_wp_error( $recipe_cuisine_terms ) ) {
								foreach ( $recipe_cuisine_terms as $recipe_cuisine_term ) {
									$recipe_cuisine_id = $recipe_cuisine_term->term_id;
								}
							}
							?>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Choose Cuisine:', 'ranna-core' ); ?></label>
                                <div class="col-sm-9">
                                    <select class="select2" name="recipe_cuisine">
                                        <option value=""><?php esc_html_e( 'Select Choose Cuisine', 'ranna-core' ); ?></option>
										<?php
										if ( ! empty( $recipe_cuisines ) && ! is_wp_error( $recipe_cuisines ) ) {
											foreach ( $recipe_cuisines as $recipe_cuisine ) {
												?>
                                                <option
													<?php if ( $recipe_cuisine_id == $recipe_cuisine->term_id ) { ?>selected<?php } ?>
                                                    value="<?php echo esc_attr( $recipe_cuisine->term_id ); ?>"><?php echo esc_html( $recipe_cuisine->name ); ?></option>
											<?php }
										}
										?>

                                    </select>
                                </div>
                            </div>

							<?php
							$recipe_tag_id        = array();
							$recipe_tag_terms_all = get_the_terms( $recipe_id, 'rn_recipe_tag' );
							if ( $recipe_tag_terms_all && ! is_wp_error( $recipe_tag_terms_all ) ) {
								foreach ( $recipe_tag_terms_all as $recipe_tag_term_all ) {
									$recipe_tag_id[] = $recipe_tag_term_all->term_id;
								}
							}
							?>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Choose Tag:', 'ranna-core' ); ?></label>
                                <div class="col-sm-9">
                                    <select class="select2 ranna-select-2" name="recipe_post_tag[]" multiple>
                                        <option value=""><?php esc_html_e( '- Select Choose Tag -', 'ranna-core' ); ?></option>
										<?php
										if ( ! empty( $recipe_all_tags ) && ! is_wp_error( $recipe_all_tags ) ) {
											foreach ( $recipe_all_tags as $recipe_all_tag ) {
												?>
                                                <option
													<?php if ( in_array( $recipe_all_tag->term_id, $recipe_tag_id ) ) { ?>selected<?php } ?>
                                                    value="<?php echo esc_attr( $recipe_all_tag->term_id ); ?>"><?php echo esc_html( $recipe_all_tag->name ); ?></option>
											<?php }
										}
										?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Description:', 'ranna-core' ); ?></label>
                                <div class="col-sm-9">
									<?php
									$content   = get_the_content();
									$editor_id = 'recipecontent';
									$settings  = array(
										'textarea_rows' => get_option( 'default_post_edit_rows', 10 ),
										'textarea_name' => 'frontend_recipe_content',
										'media_buttons' => false,
									);
									wp_editor( $content, $editor_id, $settings );
									?>
                                </div>
                            </div>

                            <div class="additional-input-wrap">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Upload your Main photo:', 'ranna-core' ); ?></label>
                                    <div class="col-sm-9 image-box">
                                        <div class="rec-feature-image">
											<?php the_post_thumbnail(); ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="file" name="recipe_feature_image" id="recipe_feature_image"
                                                   class="form-control">
                                        </div>
										<?php if ( has_post_thumbnail() ) { ?>
                                            <div class="remove-image" id="<?php echo $recipe_id; ?>">x</div>
										<?php } ?>
                                    </div>
                                </div>

								<?php /*<div class="additional-input-wrap">
							<label><?php esc_html_e( 'Upload your Video', 'ranna-core'); ?></label>
							<div class="form-group">
								<input type="file" name="recipe_feature_image" id="recipe_feature_image" class="form-control"  >
							</div>
						</div> */ ?>

                                <div class="additional-input-wrap">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Additional Informations', 'ranna-core' ); ?>
                                            :</label>
                                        <div class="col-sm-9">
                                            <div class="row gutters-5">
                                                <div class="col-lg-6">
                                                    <div class="form-group additional-input-box icon-left"><?php esc_html_e( 'Preparation Time', 'ranna-core' ); ?>
                                                        <input type="text"
                                                               value="<?php $preptime = get_post_meta( $recipe_id, 'rn_recipe_prep_time', true );
														       if ( ! empty( $preptime ) ) {
															       echo esc_html( $preptime );
														       } else {
															       echo 'N/A';
														       } ?>" class="form-control"
                                                               name="preptime"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group additional-input-box icon-left">
														<?php esc_html_e( 'Cooking Time', 'ranna-core' ); ?><input
                                                                type="text"
                                                                value="<?php $cook_time = get_post_meta( $recipe_id, 'rn_recipe_cook_time', true );
																if ( ! empty( $cook_time ) ) {
																	echo esc_html( $cook_time );
																} else {
																	echo 'N/A';
																} ?>" class="form-control"
                                                                name="cooktime"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group additional-input-box icon-left">
														<?php esc_html_e( 'Ready in Time', 'ranna-core' ); ?><input
                                                                value="<?php $ready_time = get_post_meta( $recipe_id, 'rn_recipe_ready_in', true );
																if ( ! empty( $ready_time ) ) {
																	echo esc_html( $ready_time );
																} else {
																	echo 'N/A';
																} ?>" type="text" class="form-control"
                                                                name="readytime"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group additional-input-box icon-left">
														<?php esc_html_e( 'Serving People', 'ranna-core' ); ?><input
                                                                value="<?php $serving_people = get_post_meta( $recipe_id, 'rn_recipe_serving_people', true );
																if ( ! empty( $serving_people ) ) {
																	echo esc_html( $serving_people );
																} else {
																	echo 'N/A';
																} ?>" type="text" class="form-control"
                                                                name="servepeople"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group additional-input-box icon-left">
														<?php esc_html_e( 'Serve in dish?', 'ranna-core' ); ?><input
                                                                value="<?php $serving_size = get_post_meta( $recipe_id, 'rn_recipe_serving_size', true );
																if ( ! empty( $serving_size ) ) {
																	echo esc_html( $serving_size );
																} else {
																	echo 'N/A';
																} ?>" type="text"
                                                                class="form-control"
                                                                name="servesize">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Recipe Difficulty:', 'ranna-core' ); ?></label>
                                    <div class="col-sm-9">
                                        <select class="select2" name="recipe_difficulty">
											<?php $difficulty = get_post_meta( $recipe_id, 'rn_recipe_difficulty', true ); ?>
                                            <option <?php if ( $difficulty == 'easy' ){ ?>selected<?php } ?>
                                                    value="easy"><?php esc_html_e( 'Easy', 'ranna-core' ); ?></option>
                                            <option <?php if ( $difficulty == 'medium' ){ ?>selected<?php } ?>
                                                    value="medium"><?php esc_html_e( 'Medium', 'ranna-core' ); ?></option>
                                            <option <?php if ( $difficulty == 'hard' ){ ?>selected<?php } ?>
                                                    value="hard"><?php esc_html_e( 'Hard', 'ranna-core' ); ?></option>
                                        </select>
                                    </div>
                                </div>
								<?php
								$counter = get_option( 'admin_notice_migration' );

								$recipe_new_ingredients   = get_post_meta( get_the_ID(), '_rn_recipes', true );
								$recipe_new_ingredientsii = get_post_meta( get_the_ID(), '_rn_recipes', true );

								$recipe_ingredients_old = get_post_meta( get_the_ID(), 'rn_recipe_ingredient_list', true );

								if ( empty( $recipe_new_ingredients ) && ! empty( $recipe_ingredients_old )
								) { ?>
                                    <div class="additional-input-wrap">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Ingredients aaa', 'ranna-core' ); ?>
                                                :</label>
                                            <div class="col-sm-9">
                                                <div class="row no-gutters">
                                                    <ul class="ingredient-sorting">
														<?php
														$rn_recipe_ingredients = get_post_meta( $recipe_id, 'rn_recipe_ingredient_list', true );
														$i                     = 1;
														if ( ! empty( $rn_recipe_ingredients ) ) {

															foreach ( $rn_recipe_ingredients as $rec ) {
																?>
                                                                <li class="ingredient-item">
                                                                    <div class="item-sort">
                                                                        <i class="fa fa-arrows-alt"></i>
                                                                    </div>
                                                                    <div class="ingredient-field">
                                                                        <input type="text"
                                                                               value="<?php if ( ! empty( $rec['ingredient_item'] ) ) {
																			       echo esc_html( $rec['ingredient_item'] );
																		       } ?>"
                                                                               class="form-control"
                                                                               name="ingredient_item[]">
                                                                        <input type="text"
                                                                               value="<?php if ( ! empty( $rec['ingredient_quantity'] ) ) {
																			       echo esc_html( $rec['ingredient_quantity'] );
																		       } ?>"
                                                                               class="form-control"
                                                                               name="ingredient_quantity[]">
                                                                        <input type="text"
                                                                               value="<?php if ( ! empty( $rec['ingredient_unit'] ) ) {
																			       echo esc_html( $rec['ingredient_unit'] );
																		       } ?>" class="form-control"
                                                                               name="ingredient_unit[]">
                                                                    </div>

                                                                    <div class="item-sort remove-section"><?php if ( $i != 1 ) { ?>
                                                                            <i class="fa fa-times"
                                                                               aria-hidden="true"></i><?php } ?>
                                                                    </div>

                                                                </li>
																<?php $i ++;
															}
														} ?>
                                                    </ul>
                                                </div>
                                                <div class="btn-upload  add-ingredient"><i class="fa fa-plus"
                                                                                           aria-hidden="true"></i><?php esc_html_e( 'ADD NEW INGREDIENT', 'ranna-core' ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								<?php } else { ?>
                                    <div class="additional-input-wrap">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Ingredients', 'ranna-core' ); ?>
                                                :</label>
                                            <div class="col-sm-9">
                                                <div class="rn-recipe-wrapper">
                                                    <div class="rn-recipe-wrap">
														<?php
														$recipes = get_post_meta( $recipe_id, '_rn_recipes', true );
														if ( ! empty( $recipes ) ) {
															foreach ( $recipes as $key => $recipe ) {
																?>
                                                                <div class="rn-recipe-item">
                                                                <span class="rn-remove"><i class="fa fa-times"
                                                                                           aria-hidden="true"></i></span>
                                                                    <div class="rn-recipe-title">
                                                                        <input type="text"
                                                                               name="rn_recipes[<?php echo absint( $key ); ?>][title]"
                                                                               class="form-control"
                                                                               value="<?php echo isset( $recipe['title'] ) ? esc_attr( $recipe['title'] ) : '' ?>"
                                                                               placeholder="Recipe Title">
                                                                    </div>
                                                                    <div class="rn-ingredient-wrap">
																		<?php if ( ! empty( $recipe['ingredient'] ) ) {
																			foreach ( $recipe['ingredient'] as $ikey => $ingredient ) {
																				?>
                                                                                <div class="rn-ingredient-item">
                                                                                <span class="item-sort"><i
                                                                                            class="fa fa-arrows-alt"></i></span>
                                                                                    <div class="rn-ingredient-fields">
                                                                                        <input type="text"
                                                                                               placeholder="Ingredient Name"
                                                                                               class="form-control"
                                                                                               value="<?php echo isset( $ingredient['title'] ) ? esc_attr( $ingredient['title'] ) : '' ?>"
                                                                                               name="rn_recipes[<?php echo absint( $key ); ?>][ingredient][<?php echo absint( $ikey ); ?>][title]">
                                                                                        <input type="text"
                                                                                               placeholder="Quantity"
                                                                                               class="form-control"
                                                                                               value="<?php echo isset( $ingredient['quantity'] ) ? esc_attr( $ingredient['quantity'] ) : '' ?>"
                                                                                               name="rn_recipes[<?php echo absint( $key ); ?>][ingredient][<?php echo absint( $ikey ); ?>][quantity]">
                                                                                        <input type="text"
                                                                                               placeholder="Unit"
                                                                                               class="form-control"
                                                                                               value="<?php echo isset( $ingredient['unit'] ) ? esc_attr( $ingredient['unit'] ) : '' ?>"
                                                                                               name="rn_recipes[<?php echo absint( $key ); ?>][ingredient][<?php echo absint( $ikey ); ?>][unit]">
                                                                                    </div>
                                                                                    <span class="rn-remove"><i
                                                                                                class="fa fa-times"
                                                                                                aria-hidden="true"></i></span>
                                                                                </div>
																				<?php
																			}
																		} ?>
                                                                    </div>
                                                                    <div class="rn-ingredient-actions">
                                                                        <a href="javascript:void()"
                                                                           class="btn-upload add-ingredient btn-sm btn-primary"><?php esc_html_e( 'Add New Ingredient', 'ranna-core' ); ?></a>
                                                                    </div>
                                                                </div>
																<?php
															}
														}
														?>
                                                    </div>
                                                    <div class="rn-recipe-actions">
                                                        <a href="javascript:void()"
                                                           class="btn-upload add-recipe btn-sm btn-primary"><?php esc_html_e( 'Add New Recipe', 'ranna-core' ); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								<?php } ?>
                                <div class="additional-input-wrap">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Nutrition Facts', 'ranna-core' ); ?>
                                            :</label>
                                        <div class="col-sm-9">
                                            <div class="row gutters-5">
                                                <ul class="nutrition-sorting">
													<?php
													$rn_recipe_nutritions = get_post_meta( $recipe_id, 'rn_recipe_nutritions_list', true );
													$i                    = 1;
													if ( ! empty( $rn_recipe_nutritions ) ) {
														foreach ( $rn_recipe_nutritions as $rec ) {
															?>
                                                            <li class="ui-state-default nutrition-item">
                                                                <div class="item-sort">
                                                                    <i class="fa fa-arrows-alt"></i>
                                                                </div>
                                                                <div class="nutrition-field">
                                                                    <input type="text"
                                                                           value="<?php if ( ! empty( $rec['nutritions_item'] ) ) {
																		       echo esc_html( $rec['nutritions_item'] );
																	       } ?>"
                                                                           class="form-control" name="nutrition_item[]">
                                                                    <input type="text"
                                                                           value="<?php if ( ! empty( $rec['nutritions_item_percentage'] ) ) {
																		       echo esc_html( $rec['nutritions_item_percentage'] );
																	       } ?>"
                                                                           class="form-control"
                                                                           name="nutrition_daily_value[]">
                                                                </div>

                                                                <div class="item-sort remove-section">
																	<?php if ( $i != 1 ) { ?>
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
																	<?php } ?>
                                                                </div>

                                                            </li>
															<?php $i ++;
														}
													} else { ?>

                                                        <li class="ui-state-default nutrition-item">
                                                            <div class="item-sort"><i class="fa fa-arrows-alt"></i>
                                                            </div>
                                                            <div class="nutrition-field">
                                                                <input type="text"
                                                                       placeholder="<?php esc_html_e( 'Nutrition Item', 'ranna-core' ); ?>"
                                                                       class="form-control" name="nutrition_item[]">
                                                                <input type="text"
                                                                       placeholder="<?php esc_html_e( '% of Daily Value', 'ranna-core' ); ?>"
                                                                       class="form-control"
                                                                       name="nutrition_daily_value[]">
                                                            </div>
                                                            <div class="item-sort remove-section">
                                                                <i class="fa fa-times"></i>
                                                            </div>
                                                        </li>
													<?php } ?>
                                                </ul>

                                            </div>

                                            <div type="submit" class="add-nutrition-btn add-nutrition btn-upload"><i
                                                        class="fa fa-cloud-upload"
                                                        aria-hidden="true"></i><?php esc_html_e( 'ADD NEW INGREDIENT', 'ranna-core' ); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Write Short Description of Direction', 'ranna-core' ); ?>
                                        :</label>
                                    <div class="col-sm-9">
										<?php $rn_recipe_direction_text = get_post_meta( get_the_ID(), 'rn_recipe_direction_text', true ); ?>
                                        <div class="summernote"><textarea name="direction_short_des"
                                                                          class="form-control"><?php if ( ! empty( $rn_recipe_direction_text ) ) {
													echo wp_kses_post( $rn_recipe_direction_text );
												} ?></textarea></div>
                                    </div>
                                </div>

                                <div class="additional-input-wrap row">

                                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Directions', 'ranna-core' ); ?>
                                        :</label>
                                    <div class="col-sm-9">
                                        <div class="row gutters-5">

                                            <div class="direction-wrapper direction-sorting">
                                                <div class="blank-filler" id="<?php echo get_the_ID(); ?>"></div>
												<?php
												$recipe_directions = get_post_meta( get_the_ID(), 'rn_recipe_direction_list', true );


												if ( ! empty( $recipe_directions ) ) {
													$i = 1;
													foreach ( $recipe_directions as $key => $direction ) {
														?>

                                                        <div class="ui-state-default direction-item ui-sortable-handle">
                                                            <div class="item-sort">
                                                                <i class="fa fa-arrows-alt"></i>
                                                            </div>
                                                            <div class="direction-field">
                                                                <div class="dir-image">

																	<?php if ( ! empty( $direction['direction_img'] ) ) {
																		$feat_image_url = wp_get_attachment_url( $direction['direction_img'] ); ?>
                                                                        <img style="display: block; margin: 15px;max-width: 150px;height:auto;" src="<?php echo esc_url( $feat_image_url ); ?>"/>
																	<?php } ?>
                                                                </div>

                                                                <div class="ranna-dir-image-upload">
                                                                    <input type="file" id="recipe_direction_image"
                                                                           class="form-control"
                                                                           name="recipe_direction[<?php echo $key; ?>][img]">
                                                                    <span>Upload image in jpg,png or gif </span>
                                                                </div>
                                                                <textarea
                                                                        name="recipe_direction[<?php echo $key; ?>][text]"
                                                                        class="form-control"><?php echo( ! empty( $direction['direction_text'] ) ? wp_kses_post( $direction['direction_text'] ) : '' ); ?></textarea>

                                                                <label class="rtrs-input-label">Video Source</label>

                                                                <select name="recipe_direction[<?php echo $key; ?>][video_type]" class="rtrs-video-source">
                                                                    <option value="external" <?php echo ! empty( $direction['video_type'] ) && $direction['video_type'] == 'external' ? 'selected' : '' ?>>External Video</option>
                                                                    <option value="self" <?php echo ! empty( $direction['video_type'] ) && $direction['video_type'] == 'self' ? 'selected' : '' ?>>Hosted Video</option>
                                                                </select>

                                                                <div class="rtrs-multimedia-upload">
                                                                    <div class="rtrs-upload-box">
                                                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                                                        <span>Upload a Video</span>

                                                                    </div>


																	<?php
																	if ( ! empty( $direction['direction_self_video'] ) ) {
																		$video_url = wp_get_attachment_url( $direction['direction_self_video'] );
																		?>
                                                                        <a style=" border: 1px solid;padding: 5px 10px;border-radius: 30px; margin-top: 5px; display: block;text-align: center;"
                                                                           href="<?php echo esc_url( $video_url ) ?>" target="_blank">
                                                                            <i class="fa fa-play"></i>
																			<?php echo esc_html__( " Video", 'ranna-core' ) ?>
                                                                        </a>
																		<?php
																	}
																	?>
                                                                    <input type="file" class="recipe_direction_self_video form-control" accept="video/*" name="recipe_direction[<?php echo $key; ?>][self_video]"></div>
                                                                <input type="text" placeholder="https://www.youtube.com/watch?v=668nUCeBHyY" class="form-control video" name="recipe_direction[<?php echo $key; ?>][url]"
                                                                       value="<?php echo( ! empty( $direction['url'] ) ? esc_html( $direction['url'] ) : '' ); ?>">

                                                            </div>


                                                            <div data-id="<?php echo absint( $key ); ?>"
                                                                 class="item-sort remove-section">
                                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
													<?php }

												} ?>
                                            </div>

                                        </div>
                                        <div type="submit" class="add-direction btn-upload"><i
                                                    class="fa fa-cloud-upload"
                                                    aria-hidden="true"></i><?php esc_html_e( 'ADD NEW DIRECTION', 'ranna-core' ); ?>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Conclusion of Direction', 'ranna-core' ); ?>
                                        :</label>
                                    <div class="col-sm-9">
                                        <div class="summernote"><textarea name="rn_recipe_direction_conclusion"
                                                                          class="form-control"><?php
												$rn_recipe_direction_conclusion = get_post_meta( get_the_ID(), 'rn_recipe_direction_conclusion', true );
												if ( ! empty( $rn_recipe_direction_conclusion ) ) {
													echo wp_kses_post( $rn_recipe_direction_conclusion );
												} ?></textarea></div>
                                    </div>
                                </div>

								<?php }
								} ?>

                                <input type="submit" class="btn-submit"
                                       value="<?php esc_html_e( 'UPDATE RECIPE', 'ranna-core' ); ?>"/>

                                <input type="hidden" name="rt_post_type" id="post_type" value="ranna_recipe"/>
								<?php wp_nonce_field( 'rt_nonce_action', 'rt_nonce_field' ); ?>
                        </form>

                    </main>
                </div>
				<?php
				if ( RannaTheme::$layout == 'right-sidebar' ) {
					get_sidebar();
				}
				?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>
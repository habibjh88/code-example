<?php 
// 1. This function for embed the markup on post meta
function ranna_like() {
    $already_liked = false;
    if ( is_user_logged_in() ) {
        global $current_user;
        $current_user = wp_get_current_user();
        $liked_posts = get_user_meta( $current_user->ID, 'liked_recipe', true );
        $liked_posts = is_array( $liked_posts ) ? $liked_posts : array();
        $already_liked = in_array( get_the_ID(), $liked_posts );
    }
    /*HTML Markup*/
    if ( is_user_logged_in() ) {
        $heart_icon = "fa-heart";
        if ( $already_liked == false ) {
            $heart_icon = "fa-heart-o";
        }
        ?>
        <li class="single-meta">
            <a href="#" class="like-recipe" data-recipeid="<?php echo get_the_ID(); ?>" data-author="<?php echo esc_html( $current_user->ID ); ?>" data-total="<?php rt_like_count( get_the_ID() ); ?>">
                <i class="fa <?php echo esc_attr($heart_icon) ?>"></i>
                <span><?php rt_like_count( get_the_ID() ); ?></span> <?php esc_html_e( ' Like', 'ranna-core' ); ?>
            </a>
        </li> <?php
    } else { ?>
        <li class="single-meta"><a href="#" data-toggle="modal" data-author="0" data-target="#rt-like-check<?php echo get_the_ID(); ?>" class="like-recipe not-looged-in"><i class="fa fa-heart-o"></i><span><?php rt_like_count( get_the_ID() ); ?></span> <?php esc_html_e( ' Like', 'ranna-core' ); ?></a></li>
    <?php }
}


// 2. Script Part 
?>
<script>
   $('.single-meta').on('click', '.like-recipe', function (event) {
        event.preventDefault();
        var _this = $(this),
            recipeID = _this.attr("data-recipeid"),
            authorID = _this.attr("data-author");

            //Debounce ajax call / prevent multiple ajax call
        if (_this.data('requestRunning')) {
            return;
        }
        
        _this.data('requestRunning', true);

        if (authorID != 0) {
            $.ajax({
                url: ThemeObj.ajaxURL,
                data: {authorID: authorID, recipeID: recipeID, action: 'ranna_like_action'},
                type: 'POST',
                success: function (resp) {
                    _this.parent('.single-meta').find('> .like-recipe span').text(resp.finalcount);

                    if (resp.success) {
                        // liked
                        _this.parent('.single-meta').find('> .like-recipe i').removeClass('fa fa-heart-o').addClass('fa fa-heart');

                    } else {
                        // not liked
                        _this.parent('.single-meta').find('> .like-recipe i').removeClass('fa fa-heart').addClass('fa fa-heart-o');
                    }
                },
                complete: function () {
                    _this.data('requestRunning', false);
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }

    });

    $('.single-meta').on('click', '.like-recipe.not-looged-in', function (e) {
        alert ( 'You must logged in to like the recipe' );
        e.stopPropagation()
    });
</script>
<?php

// 3. Ajax Part 

/*Like action */

add_action('wp_ajax_ranna_like_action', 'rn_ranna_like_action');
add_action('wp_ajax_nopriv_ranna_like_action', 'rn_ranna_like_action');

function rn_ranna_like_action() {
	if (isset($_POST['authorID'])){ $authorID = esc_html($_POST['authorID']); }
    if (isset($_POST['authorID'])){ $recipeID = esc_html($_POST['recipeID']); }

    if (is_user_logged_in()) {

        $current_user = wp_get_current_user();
        $liked_posts = get_user_meta($current_user->ID, 'liked_recipe', true);
        $like_list = get_user_meta($authorID, 'liked_recipe', true);

        $success = false;
        $massage = null;

        if (is_array($liked_posts) && in_array($recipeID, $liked_posts)) {
            // duplicate clicked found
            $success = false;
            $massage = esc_html('liked', 'ranna');
            $previous_like = get_post_meta($recipeID, 'rt_like_num', true);
            $new_like = $previous_like - 1;
            update_post_meta($recipeID, 'rt_like_num', $new_like);
            $liked = array_diff( $like_list, [$recipeID] );
            update_user_meta($authorID, 'liked_recipe', $liked);
        } else {
            // Like option
            $previous_like = get_post_meta($recipeID, 'rt_like_num', true);
            $new_like = $previous_like + 1;
            update_post_meta($recipeID, 'rt_like_num', $new_like);

            if (empty($like_list)) {
                update_user_meta($authorID, 'liked_recipe', array($recipeID));
            } else {
                $author_arr = (is_array($like_list)) ? $like_list : array();

                $author_arr[] = $recipeID;
                update_user_meta($authorID, 'liked_recipe', $author_arr);
            }

            $success = true;
            $massage = esc_html('like', 'ranna');
        }

        $final_count_update = get_post_meta($recipeID, 'rt_like_num', true);

    } else {
        // user not logged in
        $success = false;
        $massage = esc_html('User is not logged in', 'ranna');
        $final_count_update = '';
    }

    wp_send_json(array('success' => $success, 'msg' => $massage, 'finalcount' => $final_count_update));
}

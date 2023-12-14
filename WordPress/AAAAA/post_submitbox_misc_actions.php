<?php 

//IF any problem will come in front you shoud check it from Classified-listing plugin

// Step One: Add meta box to submitbox
add_action('post_submitbox_misc_actions', 'post_submitbox_misc_actions');

function post_submitbox_misc_actions() {

    global $post, $post_type;

    if (rtcl()->post_type == $post_type) {
        $never_expires = !empty(get_post_meta($post->ID, 'never_expires', true)) ? 1 : 0;
        $expiry_date = get_post_meta($post->ID, 'expiry_date', true);
        $expiry_date = $expiry_date ?: Functions::dummy_expiry_date();
        $promotions = Options::get_listing_promotions();
        $_views = get_post_meta($post->ID, '_views', true);
        wp_nonce_field(rtcl()->nonceText, rtcl()->nonceId);
        ?>
        <div
            class="misc-pub-section misc-pub-rtcl-expiration-time"<?php echo $never_expires ? ' style="display: none;"' : '' ?>>
            <?php Functions::touch_time('expiry_date', $expiry_date); ?>
        </div>
        <div class="misc-pub-section misc-pub-rtcl-overwrite">
            <label>
                <input type="checkbox" id="rtcl-overwrite" name="overwrite" value="1">
                <strong><?php _e("Overwrite Default", 'classified-listing'); ?></strong>
            </label>
        </div>
        <div class="misc-pub-section rtcl-overwrite-item" data-id="never-expires">
            <label>
                <input disabled type="checkbox" name="never_expires"
                       value="1" <?php if (isset($never_expires)) {
                    checked($never_expires, 1);
                } ?>>
                <strong><?php esc_html_e("Never Expires", 'classified-listing'); ?></strong>
            </label>
        </div>
        <?php
        if (!empty($promotions)) {
            foreach ($promotions as $promo_id => $promotion) {
                $promo_value = get_post_meta($post->ID, $promo_id, true);
                ?>
                <div class="misc-pub-section rtcl-overwrite-item"  data-id="<?php echo esc_attr($promo_id) ?>">
                    <label>
                        <input disabled type="checkbox" name="<?php echo esc_attr($promo_id) ?>" value="1" <?php checked($promo_value, 1) ?>>
                        <?php esc_html_e("Mark as", 'classified-listing'); ?>
                        <strong><?php echo esc_html($promotion); ?></strong>
                    </label>
                </div>
                <?php
                do_action('rtcl_listing_submit_box_misc_actions_'.$promo_id, "99", $post);
            }
        }
        ?>
        <div class="misc-pub-section">
            <label for="rtcl-views">
                <strong><?php _e("View", 'classified-listing'); ?></strong>
                <input type="number" id="rtcl-views" name="_views" value="<?php echo absint($_views); ?>">
            </label>
        </div>

        <div class="misc-pub-section misc-pub-rtcl-action rtcl">
            <div class="form-group row">
                <label for="rtcl-listing-status"
                       class="col-sm-2 col-form-label"><?php _e("Status", "classified-listing") ?></label>
                <div class="col-sm-10">
                    <select name="post_status" id="rtcl-listing-status" class="form-control">
                        <?php
                        $status_list = Options::get_status_list();
                        $c_status = get_post_status($post->ID);
                        foreach ($status_list as $status_id => $status) {
                            printf("<option value='%s'%s>%s</option>",
                                $status_id,
                                $status_id === $c_status ? " selected" : null,
                                $status
                            );
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <?php
    }

}


// Step 2
add_action( 'save_post', 'save_listing_meta_data' );

function save_listing_meta_data( $post_id, $post ) {
    if ( ! isset( $_POST['post_type'] ) ) {
        return $post_id;
    }

    if ( rtcl()->post_type != $post->post_type ) {
        return $post_id;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // Check the logged in user has permission to edit this post
    if ( ! current_user_can( 'edit_' . rtcl()->post_type, $post_id ) ) {
        return $post_id;
    }

    if ( ! Functions::verify_nonce() ) {
        return $post_id;
    }

    $edit_expired_date = false;

    if ( isset( $_POST['overwrite'] ) ) {
        if ( isset( $_POST['never_expires'] ) ) {
            update_post_meta( $post_id, 'never_expires', 1 );
            delete_post_meta( $post_id, 'expiry_date' );
            $syncData['update']['never_expires'] = 1;
            $syncData['delete']                  = [ 'expiry_date' ];
        } else {
            delete_post_meta( $post_id, 'never_expires' );
            $syncData['delete'] = [ 'never_expires' ];
        }

        // Feature
        if ( isset( $_POST['featured'] ) ) {
            update_post_meta( $post_id, 'featured', 1 );
            $syncData['update']['featured'] = 1;
        } else {
            delete_post_meta( $post_id, 'featured' );
            delete_post_meta( $post_id, 'feature_expiry_date' );
            $syncData['delete'][] = 'featured';
            $syncData['delete'][] = 'feature_expiry_date';
        }
        do_action( "rtcl_listing_overwrite_change", $post_id, $_POST );
    }
   
    // Update view
    if ( isset( $_POST['_views'] ) ) {
        update_post_meta( $post_id, '_views', absint( $_POST['_views'] ) );
    }

    
    // Ad type
    if ( isset( $_POST['ad_type'] ) ) {
        $ad_type = sanitize_text_field( $_POST['ad_type'] );
        update_post_meta( $post_id, 'ad_type', $ad_type );
    }

    do_action( "rtcl_listing_update_metas_at_admin", $post_id, $_POST );
}
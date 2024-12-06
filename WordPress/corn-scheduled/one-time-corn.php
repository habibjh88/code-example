<?php

// 1. Start the Corn:
if ( ! empty( $migration['active'] ) ) {
    wp_clear_scheduled_hook( 'rtcl_form_cf_data_migration' );
    wp_schedule_single_event( time(), 'rtcl_form_cf_data_migration' );
}

// 2. Add action under the hook of schedule
add_action( 'rtcl_form_cf_data_migration', [ $this, 'form_cf_data_migration' ] );

public function form_cf_data_migration() {
    $migration = get_option( 'rtcl_fb_migration_data', [] );
    if ( empty( $migration['active'] ) || empty( $migration['formId'] ) || empty( $migration['fields'] ) ) {
        return;
    }

    $formId = absint( $migration['formId'] );
    $form   = Form::query()->find( $formId );

    if ( empty( $formId ) || empty( $form ) ) {
        return;
    }

    $args = [
        'post_type'      => rtcl()->post_type,
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'post_status'    => 'any',
        'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
            [
                'key'     => '_rtcl_form_id',
                'compare' => 'NOT EXISTS'
            ]
        ]
    ];

    $query = new WP_Query( $args );

    if ( ! empty( $query->posts ) ) {
        global $wpdb;
        foreach ( $query->posts as $postId ) {
            // if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
            // $type = apply_filters( 'wpml_element_type', get_post_type( $postId ) );
            // $trid = apply_filters( 'wpml_element_trid', false, $postId, $type );
            // $translations = apply_filters( 'wpml_get_element_translations', [], $trid, $type );
            // $translatedIds = [];
            // foreach ( $translations as $lang => $translation ) {
            // if ( $translation->element_id !== $postId ) {
            // $translatedIds[] = $translation->element_id;
            // }
            // }
            // }

            update_post_meta( $postId, '_rtcl_form_id', $formId );
            $rawBsh         = get_post_meta( $postId, '_rtcl_bhs', true );
            $business_hours = ! empty( $rawBsh ) && is_array( $rawBsh ) ? $rawBsh : [];
            if ( ! empty( $business_hours ) ) {
                $bhs           = [
                    'active' => true,
                    'type'   => 'selective',
                    'days'   => $business_hours
                ];
                $rawSpecialBhs = get_post_meta( $postId, '_rtcl_special_bhs', true );
                if ( ! empty( $rawSpecialBhs ) && is_array( $rawSpecialBhs ) ) {
                    $timeFormat   = 'H:i';
                    $tempDateList = [];
                    $newSBhs      = [];

                    foreach ( $rawSpecialBhs as $sbh ) {

                        if ( ! empty( $sbh['date'] ) && ! isset( $tempDateList[ $sbh['date'] ] ) && $dateObj = Utility::sanitizedDateObj( $sbh['date'] ) ) {
                            $date                  = $dateObj->format( 'Y-m-d' );
                            $tempDateList[ $date ] = $date;
                            $newSbh                = [
                                'date'  => $date,
                                'occur' => 'repeat'
                            ];
                            if ( ! empty( $sbh['open'] ) ) {
                                $newSbh['open'] = true;
                                if ( is_array( $sbh['times'] ) && ! empty( $sbh['times'] ) ) {
                                    $newTimes = [];
                                    foreach ( $sbh['times'] as $time ) {
                                        if ( ! empty( $time['start'] ) && ! empty( $time['end'] ) ) {
                                            $start = Utility::formatTime( $time['start'], 'H:i', $timeFormat );
                                            $end   = Utility::formatTime( $time['end'], 'H:i', $timeFormat );
                                            if ( $start && $end ) {
                                                $newTimes[] = [
                                                    'start' => $start,
                                                    'end' => $end
                                                ];
                                            }
                                        }
                                    }
                                    if ( ! empty( $newTimes ) ) {
                                        $newSbh['times'] = $newTimes;
                                    }
                                }
                            } else {
                                $newSbh['open'] = false;
                            }
                            $newSBhs[] = $newSbh;
                        }
                    }
                    $bhs['special'] = ! empty( $newSBhs ) ? $newSBhs : '';
                }
                update_post_meta( $postId, '_rtcl_bhs', $bhs );
            }
            foreach ( $migration['fields'] as $fieldUuid => $_field ) {
                if ( empty( $form->fields[ $fieldUuid ] ) ) {
                    continue;
                }
                $field     = $form->fields[ $fieldUuid ];
                $fieldName = ! empty( $field['name'] ) ? $field['name'] : '';
                if ( empty( $fieldName ) || empty( $_field['id'] ) ) {
                    return;
                }
                $fieldId = (int) $_field['id'];
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $metaData = $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE post_id = $postId AND meta_key LIKE '_field_$fieldId%'" );
                if ( empty( $metaData ) ) {
                    continue;
                }

                foreach ( $metaData as $meta ) {
                    update_post_meta( $meta->post_id, $fieldName, $meta->meta_value );
                }
            }
        }
    }

    if ( $query->max_num_pages > 1 ) {
        // add next scheduler event
        wp_schedule_single_event( time(), 'rtcl_form_cf_data_migration' );
    }
}

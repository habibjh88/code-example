<?php 

//Show Admin Notice
add_action( 'admin_init', 'rttpg_notice' );

function rttpg_notice() {
    add_action(
        'admin_notices',
        function () {
            $screen   = get_current_screen();
                if ( in_array( $screen->id, [ 'edit-rttpg','rttpg' ], true ) ) { ?>
                    <div class="notice notice-for-warning">
                        <p>
                            <?php
                            echo sprintf(
                                '%1$s<a style="color: #fff;" href="%2$s">%3$s</a>',
                                esc_html__( 'You have selected only Elementor method. To use Shortcode Generator please enable shortcode or default from ', 'the-post-grid' ),
                                esc_url( admin_url( 'edit.php?post_type=rttpg&page=rttpg_settings' ) ),
                                esc_html__( 'Settings => Common Settings => Resource Load Type', 'the-post-grid' )
                            );
                            ?>
                        </p>
                    </div>
                    <?php
                }
        }
    );
}
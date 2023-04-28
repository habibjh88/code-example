<?php
//Pest the below code at last on the wp-config.php file

add_action( 'admin_footer', function () {
	global $pagenow;
	if ( 'plugin-install.php' == $pagenow ) {
		?>
        <script>
            jQuery('.upload-view-toggle').hide()
        </script>
		<?php
	}
} );
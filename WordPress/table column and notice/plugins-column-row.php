<?php 


/**
 * Hook: after_plugin_row
 * Add new row after plugin info
 */

$plugin_path = "the-post-grid/the-post-grid.php";
add_action( 'after_plugin_row_'.$plugin_path, 'show_update_notification', 10, 2 );
function show_update_notification() {
		?>
    <tr class="plugin-update-tr">
        <td colspan="3" class="plugin-update colspanchange">
            <div class="update-message notice inline notice-warning notice-alt">
                <p>
                    <strong>Please enter valid license key for automatic updates.</strong> <a
                            href="http://postgrid-gb.test/">Click here</a>
                </p>
            </div>
        </td>
    </tr>
    <?php
}
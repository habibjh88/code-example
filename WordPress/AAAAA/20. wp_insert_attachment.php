
<form action="" method="post" enctype="multipart/form-data">
    <input type="text" id="fname" name="fname"><br><br>
    <input type="file" id="custom_image" name="custom_image"><br><br>
    <input type="submit" name="submit" value="Submit">
</form>

<?php
if ( isset($_FILES['custom_image']) && !empty($_FILES['custom_image']) ) {

	/*Insert Featured Image*/
	$theFile = $_FILES['custom_image'];
	$fileName = $_FILES["custom_image"]["name"];
	$tempFile = $_FILES["custom_image"]["tmp_name"];

	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	if ( !empty($fileName) ) {

		$upload = wp_handle_upload($theFile, array('test_form' => false));

		$wp_filetype = wp_check_filetype(basename($upload['file']), null);
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name($fileName),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id = wp_insert_attachment($attachment, $upload['file']);

        var_dump($attach_id);

	}
}
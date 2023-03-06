<?php
// disable gutenberg editor
add_filter('use_block_editor_for_post_type', 'disable_gutenberg_editor', 10, 2);
function disable_gutenberg_editor($use_block_editor, $post_type) {
	if ($post_type === 'post' || $post_type === 'page') {
		return false;
	}
	return $use_block_editor;
}
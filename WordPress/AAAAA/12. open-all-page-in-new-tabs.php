<?php 
// Open all page in one time 
//===============================
$args  = [
	'post_type'      => 'page',
	'post_status'    => 'publish',
	'posts_per_page' => - 1
];
$cquery = new WP_Query( $args );

while ( $cquery->have_posts() ) {
	$cquery->the_post();
    $url = get_the_permalink();
    ?>
    <script>
        window.open("<?php echo $url ?>", '_blank');
    </script>
<?php
}
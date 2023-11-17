<?php 
// convert array to ul li list
// Recursive Function example

$arr = [
	'one'   => '1',
	'two'   => '2',
	'three' => [
		'three1' => '3.1',
		'three2' => '3.2',
		'four2'  => [
			'four3' => '4.1',
			'four4' => '4.2',
		]
	],
	'five'  => '5'
];

function myPrint( $arr ) {
	$markup = '<ul>';
	foreach ( $arr as $item ) {
		$markup .= "<li>";
		if ( is_array( $item ) ) {
			$markup .= "<ul>";
			$markup .= myPrint( $item );
			$markup .= "</ul>";
		} else {
			$markup .= "{$item}";
		}
		$markup .= "</li>";
	}
	$markup .= "</ul>";
    
	return $markup;
}


echo myPrint( $arr );
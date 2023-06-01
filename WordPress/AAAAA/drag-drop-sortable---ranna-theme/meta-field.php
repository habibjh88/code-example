<?php
'rn_recipe_direction_list' => array(
			'type'   => 'repeater',
			'button' => __( 'Add New Direction', 'ranna-core' ),
			'value'  => array(					
					'direction_img' => array(
						'label' => __( 'Direction Step Image', 'ranna-core' ),
						'type'  => 'gallery',
						'desc'  => __( 'If not selected, It will be blank', 'ranna-core' ),
					),
					'direction_text' => array(
						'label' => __( 'Direction Text', 'ranna-core' ),
						'type'  => 'textarea_html',
						'desc'  => __( 'Enter direction text eg. - Preheat oven to 350°F for <b>[step_time sec="300"]5 Minutes[/step_time]</b>. Butter three 9-inch-diameter cake pans with 1 1/2-inch-high sides. Line bottom of pans with parchment paper. Combine 1/3 cup flour and next 3 ingredients in processor. Process until nuts are finely chopped. Whisk remaining 2 cups flour, cinnamon, baking powder, salt, and baking soda in medium bowl to blend.', 'ranna-core' ),
					),

					'direction_video_type' => array(
						'label' => esc_html__( 'Video type', 'ranna-core' ),
						'type'  => 'select',
						'options' => array(				
							'self'    => esc_html__( 'Hosted Video','ranna-core' ),
							'external'   => esc_html__( 'External Video','ranna-core' ),
							),
						'default'  => 'self',
					),

					'direction_self_video' => array(
						'label' => esc_html__( 'Video', 'ranna-core' ),
						'type'  => 'file',
						'desc'  => esc_html__( 'Please upload the video file here(optional)', 'ranna-core' ),
					),

					'direction_url' => array(
						'label' => __( 'Video url', 'ranna-core' ),
						'type'  => 'text',
						'desc'  => __( 'Please put video url here selfhosted & youtube', 'ranna-core' ),
					),					
				)
			),	
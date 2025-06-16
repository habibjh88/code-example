<?php

class DiviHelper {

	public function get_fields() {
		$fields = [

			// 🔤 Text Input
			'text_field'     => [
				'label'       => esc_html__( 'Text Field', 'the-post-grid' ),
				'type'        => 'text',
				'default'     => '',
				'description' => 'Enter some text.',
				'toggle_slug' => 'main_content',
			],
			'content'        => [
				'label'           => esc_html__( 'Content', 'simp-simple-extension' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear below the heading text.', 'simp-simple-extension' ),
				'toggle_slug'     => 'main_content',
			],

			// 📝 Textarea
			'textarea_field' => [
				'label'       => esc_html__( 'Textarea Field', 'the-post-grid' ),
				'type'        => 'textarea',
				'default'     => '',
				'description' => 'Enter multi-line content.',
				'toggle_slug' => 'main_content',
			],

			'rtcl_listing_per_page' => [
				'label'       => esc_html__( 'Number', 'the-post-grid' ),
				'type'        => 'number',
				'default'     => '10',
				'toggle_slug' => 'main_content',
				'description' => esc_html__( 'Number of listing to display', 'the-post-grid' ),
			],

			// 🔘 Select / Dropdown
			'dropdown_field'        => [
				'label'       => esc_html__( 'Dropdown Field', 'the-post-grid' ),
				'type'        => 'select',
				'options'     => [
					'option1' => 'Option 1',
					'option2' => 'Option 2',
				],
				'default'     => 'option1',
				'toggle_slug' => 'main_content',
			],

			'rtcl_listing_categories' => [
				'label'       => esc_html__( 'Categories', 'the-post-grid' ),
				'options'     => [
					'option1' => 'Option 1',
					'option2' => 'Option 2',
					'option3' => 'Option 3',
				],
				'type'        => 'multiple_checkboxes',
				'toggle_slug' => 'main_content',
			],
			// 🔘

			'rtcl_listing_pagination' => [
				'label'       => esc_html__( 'Listing Pagination', 'the-post-grid' ),
				'type'        => 'yes_no_button',
				'options'     => [
					'on'  => esc_html__( 'Yes', 'the-post-grid' ),
					'off' => esc_html__( 'No', 'the-post-grid' ),
				],
				'default'     => 'off',
				'toggle_slug' => 'main_content',
			],

			// TODO: description tpg_directional_text is mendatory
			'tpg_directional_text_section_info' => [
				'label'        => esc_html__( 'Please enable the ‘Section Title’ from the Field Selection panel first.', 'the-post-grid' ),
				'type'         => 'text',
				'show_if'    => [
					'show_section_title' => 'off',
				],
				'tab_slug'    => 'general',
				'toggle_slug' => 'tpg_section_title',
			],

			'tpg_heading_text_section_heading' => [
				'label'        => esc_html__( 'Please enable the ‘Section Title’ from the Field Selection panel first.', 'the-post-grid' ),
				'type'         => 'text',
				'show_if'    => [
					'show_section_title' => 'off',
				],
				'tab_slug'    => 'general',
				'toggle_slug' => 'tpg_section_title',
			],

			// 🔳 Icon Picker
			'icon_picker'             => [
				'label'       => esc_html__( 'Choose an Icon', 'the-post-grid' ),
				'type'        => 'select_icon',
				'default'     => 'ETMODULE',
				'toggle_slug' => 'main_content',
			],

			// 🎨 Color Picker
			'color_picker'            => [
				'label'       => esc_html__( 'Pick a Color', 'the-post-grid' ),
				'type'        => 'color-alpha',
				'default'     => '#ffffff',
				'toggle_slug' => 'main_content',
			],

			// 🔠 Font Selector
			'font_selector'           => [
				'label'       => esc_html__( 'Font Selector', 'the-post-grid' ),
				'type'        => 'font',
				'default'     => 'default',
				'toggle_slug' => 'main_content',
			],

			// 🖼 Image Upload
			'image_upload'            => [
				'label'              => esc_html__( 'Upload Image', 'the-post-grid' ),
				'type'               => 'upload',
				'upload_button_text' => esc_html__( 'Upload', 'the-post-grid' ),
				'default'            => '',
				'toggle_slug'        => 'main_content',
			],

			// 🔗 URL Field
			'url_field'               => [
				'label'       => esc_html__( 'Enter URL', 'the-post-grid' ),
				'type'        => 'text',
				'default'     => 'https://',
				'toggle_slug' => 'main_content',
			],

			// 📐 Range Slider
			'thumbnail_content_width'            => [
				'label'          => esc_html__( 'Range Slider', 'the-post-grid' ),
				'type'           => 'range',
				'default'        => '10px',
				'range_settings' => [
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				],
				
				'toggle_slug'    => 'main_content',
			],

			// 🔤 Font Size / Line Height etc. with Range
			'font_size'               => [
				'label'          => esc_html__( 'Font Size', 'the-post-grid' ),
				'type'           => 'range',
				'default'        => '16px',
				'range_settings' => [
					'min'  => '10',
					'max'  => '100',
					'step' => '1',
				],
				
				'toggle_slug'    => 'main_content',
			],

			// 🧱 Border
			'border_settings'         => [
				'label'       => esc_html__( 'Border', 'the-post-grid' ),
				'type'        => 'border',
				'default'     => '',
				'toggle_slug' => 'main_content',
			],

			// 🧾 Box Shadow
			'box_shadow'              => [
				'label'       => esc_html__( 'Box Shadow', 'the-post-grid' ),
				'type'        => 'box_shadow',
				'default'     => '',
				'toggle_slug' => 'main_content',
			],

			// 🎯 Alignment (left, center, right)
			'alignment'               => [
				'label'       => esc_html__( 'Text Alignment', 'the-post-grid' ),
				'type'        => 'text_align',
				'default'     => 'left',
				'options'     => et_builder_get_text_orientation_options(),
				'toggle_slug' => 'main_content',
			],

			// 🌍 HTML Support
			'html_support'            => [
				'label'       => esc_html__( 'Custom HTML', 'the-post-grid' ),
				'type'        => 'code',
				'default'     => '',
				'toggle_slug' => 'main_content',
			],

		];
	}

	function get_advanced_fields_config() {
		return [
			'borders' => [
				'test_title' => [
					'label_prefix' => esc_html__( 'Test Title', 'the-post-grid' ),
					'css'          => [
						'main' => [
							'border_styles' => "{$this->main_css_element} .test-title",
						],
					],
					'defaults'     => [
						'border_radii' => 'on|5px|5px|5px|5px',
					],
					'tab_slug'     => 'general',
					'toggle_slug'  => 'layout',
				],
			],

			//border and border-radius
			'border_styles' => [
				'fields'       => [
					'name'         => 'fields',
					'css'          => [
						'main'      => [
							'border_radii'  => '{{WRAPPER}} .tpg-el-main-wrapper .tpg-el-image-wrap, {{WRAPPER}} .tpg-el-main-wrapper .tpg-el-image-wrap img, {{WRAPPER}} .rt-grid-hover-item .grid-hover-content',
							'border_styles' => '%%order_class%% input, %%order_class%% .quantity input.qty',
							'defaults'      => [
								'border_radii'  => 'on|3px|3px|3px|3px',
								'border_styles' => [
									'width' => '0px',
									'style' => 'none',
								],
							],
						],
						'important' => 'all',
					],
					'label_prefix' => esc_html__( 'Fields', 'et_builder' ),
				],
				'fields_focus' => [
					'name'         => 'fields_focus',
					'css'          => [
						'main'      => [
							'border_radii'  => '%%order_class%% input:focus, %%order_class%% .quantity input.qty:focus',
							'border_styles' => '%%order_class%% input:focus, %%order_class%% .quantity input.qty:focus',
						],
						'important' => 'all',
					],
					'label_prefix' => esc_html__( 'Border Focus', 'et_builder' ),
				],
			],

			'box_shadow' => [
				'test_title' => [
					'label_prefix' => esc_html__( 'Test Title', 'the-post-grid' ),
					'css'          => [
						'main' => "{$this->main_css_element} .test-title",
					],
					'tab_slug'     => 'general',
					'toggle_slug'  => 'layout',
				],
			],

			'button' => [
				'primary_button' => [
					'label'         => esc_html__( 'Primary Button', 'the-post-grid' ),
					'use_alignment' => true,
					'css'           => [
						'plugin_main' => "{$this->main_css_element} .simp-button--primary",
						'alignment'   => "{$this->main_css_element} .simp-button-wrapper",
					],
					'box_shadow'    => [
						'css' => [
							'main' => "{$this->main_css_element} .simp-button--primary",
						],
					],
					'tab_slug'      => 'general',
					'toggle_slug'   => 'layout',
				],
			],

			'fonts' => [
				'body'   => [
					'label'       => esc_html__( 'Body', 'the-post-grid' ),
					'css'         => [
						'line_height' => "{$this->main_css_element} p",
						'plugin_main' => "{$this->main_css_element} p",
						'text_shadow' => "{$this->main_css_element} p",
					],
					'tab_slug'    => 'general',
					'toggle_slug' => 'layout',
				],
				'header' => [
					'label'        => esc_html__( 'Title', 'the-post-grid' ),
					'css'          => [
						'main'      => "{$this->main_css_element} h2, {$this->main_css_element} h1, {$this->main_css_element} h3, {$this->main_css_element} h4, {$this->main_css_element} h5, {$this->main_css_element} h6",
						'important' => 'all',
					],
					'header_level' => [
						'default' => 'h2',
					],
					'tab_slug'     => 'general',
					'toggle_slug'  => 'layout',
				],
			],

			'filters' => [
				'child_filters_target' => [
					'tab_slug'    => 'general',
					'toggle_slug' => 'layout',
				],
				'css'                  => [
					'main' => '%%order_class%%',
				],
			],

			'margin_padding' => [
				'default' => [
					'css'         => [
						'important' => 'all',
						'main'      => "{$this->main_css_element}", // You can scope this to a more specific element if needed
					],
					'tab_slug'    => 'general',
					'toggle_slug' => 'layout',
				],
			],

			'max_width' => [
				'css'         => [
					'module_alignment' => "{$this->main_css_element}.simp-custom-class",
				],
				'tab_slug'    => 'general',
				'toggle_slug' => 'layout',
			],
		];
	}

}
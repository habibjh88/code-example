<?php 

// All customize control example

// Test of Text Control
$wp_customize->add_setting( 'sample_default_text',
	array(
		'default' => $this->defaults['sample_default_text'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_text_sanitization'
	)
);
$wp_customize->add_control( 'sample_default_text',
	array(
		'label' => __( 'Default Text Control', 'textdomain' ),
		'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'text',
		'input_attrs' => array(
			'class' => 'my-custom-class',
			'style' => 'border: 1px solid rebeccapurple',
			'placeholder' => __( 'Enter name...', 'textdomain' ),
		),
	)
);

// Test of Email Control
$wp_customize->add_setting( 'sample_email_text',
	array(
		'default' => $this->defaults['sample_email_text'],
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_email'
	)
);
$wp_customize->add_control( 'sample_email_text',
	array(
		'label' => __( 'Default Email Control', 'textdomain' ),
		'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'email'
	)
);

// Test of URL Control
$wp_customize->add_setting( 'sample_url_text',
	array(
		'default' => $this->defaults['sample_url_text'],
		'transport' => 'refresh',
		'sanitize_callback' => 'esc_url_raw'
	)
);
$wp_customize->add_control( 'sample_url_text',
	array(
		'label' => __( 'Default URL Control', 'textdomain' ),
		'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'url'
	)
);

// Test of Number Control
$wp_customize->add_setting( 'sample_number_text',
	array(
		'default' => $this->defaults['sample_number_text'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_sanitize_integer'
	)
);
$wp_customize->add_control( 'sample_number_text',
	array(
		'label' => __( 'Default Number Control', 'textdomain' ),
		'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'number'
	)
);

// Test of Hidden Control
$wp_customize->add_setting( 'sample_hidden_text',
	array(
		'default' => $this->defaults['sample_hidden_text'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_text_sanitization'
	)
);
$wp_customize->add_control( 'sample_hidden_text',
	array(
		'label' => __( 'Default Hidden Control', 'textdomain' ),
		'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'hidden'
	)
);

// Test of Date Control
$wp_customize->add_setting( 'sample_date_text',
	array(
		'default' => $this->defaults['sample_date_text'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_text_sanitization'
	)
);
$wp_customize->add_control( 'sample_date_text',
	array(
		'label' => __( 'Default Date Control', 'textdomain' ),
		'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'text'
	)
);

 // Test of Standard Checkbox Control
$wp_customize->add_setting( 'sample_default_checkbox',
	array(
		'default' => $this->defaults['sample_default_checkbox'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_switch_sanitization'
	)
);
$wp_customize->add_control( 'sample_default_checkbox',
	array(
		'label' => __( 'Default Checkbox Control', 'textdomain' ),
		'description' => esc_html__( 'Sample Checkbox description', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'checkbox'
	)
);

	// Test of Standard Select Control
$wp_customize->add_setting( 'sample_default_select',
	array(
		'default' => $this->defaults['sample_default_select'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_radio_sanitization'
	)
);
$wp_customize->add_control( 'sample_default_select',
	array(
		'label' => __( 'Standard Select Control', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'select',
		'choices' => array(
			'wordpress' => __( 'WordPress', 'textdomain' ),
			'hamsters' => __( 'Hamsters', 'textdomain' ),
			'jet-fuel' => __( 'Jet Fuel', 'textdomain' ),
			'nuclear-energy' => __( 'Nuclear Energy', 'textdomain' )
		)
	)
);

// Test of Standard Radio Control
$wp_customize->add_setting( 'sample_default_radio',
	array(
		'default' => $this->defaults['sample_default_radio'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_radio_sanitization'
	)
);
$wp_customize->add_control( 'sample_default_radio',
	array(
		'label' => __( 'Standard Radio Control', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'radio',
		'choices' => array(
			'captain-america' => __( 'Captain America', 'textdomain' ),
			'iron-man' => __( 'Iron Man', 'textdomain' ),
			'spider-man' => __( 'Spider-Man', 'textdomain' ),
			'thor' => __( 'Thor', 'textdomain' )
		)
	)
);

// Test of Dropdown Pages Control
$wp_customize->add_setting( 'sample_default_dropdownpages',
	array(
		'default' => $this->defaults['sample_default_dropdownpages'],
		'transport' => 'refresh',
		'sanitize_callback' => 'absint'
	)
);
$wp_customize->add_control( 'sample_default_dropdownpages',
	array(
		'label' => __( 'Default Dropdown Pages Control', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'dropdown-pages'
	)
);

// Test of Textarea Control
$wp_customize->add_setting( 'sample_default_textarea',
	array(
		'default' => $this->defaults['sample_default_textarea'],
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	)
);
$wp_customize->add_control( 'sample_default_textarea',
	array(
		'label' => __( 'Default Textarea Control', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'textarea',
		'input_attrs' => array(
			'class' => 'my-custom-class',
			'style' => 'border: 1px solid #999',
			'placeholder' => __( 'Enter message...', 'textdomain' ),
		),
	)
);

// Test of Color Control
$wp_customize->add_setting( 'sample_default_color',
	array(
		'default' => $this->defaults['sample_default_color'],
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color'
	)
);
$wp_customize->add_control( 'sample_default_color',
	array(
		'label' => __( 'Default Color Control', 'textdomain' ),
		'section' => 'default_controls_section',
		'type' => 'color'
	)
);

// Test of Media Control
$wp_customize->add_setting( 'sample_default_media',
	array(
		'default' => $this->defaults['sample_default_media'],
		'transport' => 'refresh',
		'sanitize_callback' => 'absint'
	)
);
$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sample_default_media',
	array(
		'label' => __( 'Default Media Control', 'textdomain' ),
		'description' => esc_html__( 'This is the description for the Media Control', 'textdomain' ),
		'section' => 'default_controls_section',
		'mime_type' => 'image',
		'button_labels' => array(
			'select' => __( 'Select File', 'textdomain' ),
			'change' => __( 'Change File', 'textdomain' ),
			'default' => __( 'Default', 'textdomain' ),
			'remove' => __( 'Remove', 'textdomain' ),
			'placeholder' => __( 'No file selected', 'textdomain' ),
			'frame_title' => __( 'Select File', 'textdomain' ),
			'frame_button' => __( 'Choose File', 'textdomain' ),
		)
	)
) );

// Test of Image Control
$wp_customize->add_setting( 'sample_default_image',
	array(
		'default' => $this->defaults['sample_default_image'],
		'transport' => 'refresh',
		'sanitize_callback' => 'esc_url_raw'
	)
);
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'sample_default_image',
	array(
		'label' => __( 'Default Image Control', 'textdomain' ),
		'description' => esc_html__( 'This is the description for the Image Control', 'textdomain' ),
		'section' => 'default_controls_section',
		'button_labels' => array(
			'select' => __( 'Select Image', 'textdomain' ),
			'change' => __( 'Change Image', 'textdomain' ),
			'remove' => __( 'Remove', 'textdomain' ),
			'default' => __( 'Default', 'textdomain' ),
			'placeholder' => __( 'No image selected', 'textdomain' ),
			'frame_title' => __( 'Select Image', 'textdomain' ),
			'frame_button' => __( 'Choose Image', 'textdomain' ),
		)
	)
) );

// Test of Cropped Image Control
$wp_customize->add_setting( 'sample_default_cropped_image',
	array(
		'default' => $this->defaults['sample_default_cropped_image'],
		'transport' => 'refresh',
		'sanitize_callback' => 'absint'
	)
);
$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'sample_default_cropped_image',
	array(
		'label' => __( 'Default Cropped Image Control', 'textdomain' ),
		'description' => esc_html__( 'This is the description for the Cropped Image Control', 'textdomain' ),
		'section' => 'default_controls_section',
		'flex_width' => false,
		'flex_height' => false,
		'width' => 800,
		'height' => 400
	)
) );

// Test of Date/Time Control
$wp_customize->add_setting( 'sample_date_only',
	array(
		'default' => $this->defaults['sample_date_only'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_date_time_sanitization',
	)
);
$wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, 'sample_date_only',
	array(
		'label' => __( 'Default Date Control', 'textdomain' ),
		'description' => esc_html__( 'This is the Date Time Control but is only displaying a date field. It also has Max and Min years set.', 'textdomain' ),
		'section' => 'default_controls_section',
		'include_time' => false,
		'allow_past_date' => true,
		'twelve_hour_format' => true,
		'min_year' => '2016',
		'max_year' => '2025',
	)
) );

// Test of Date/Time Control
$wp_customize->add_setting( 'sample_date_time',
	array(
		'default' => $this->defaults['sample_date_time'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_date_time_sanitization',
	)
);
$wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, 'sample_date_time',
	array(
		'label' => __( 'Default Date Control', 'textdomain' ),
		'description' => esc_html__( 'This is the Date Time Control. It also has Max and Min years set.', 'textdomain' ),
		'section' => 'default_controls_section',
		'include_time' => true,
		'allow_past_date' => true,
		'twelve_hour_format' => true,
		'min_year' => '2010',
		'max_year' => '2020',
	)
) );

// Test of Date/Time Control
$wp_customize->add_setting( 'sample_date_time_no_past_date',
	array(
		'default' => $this->defaults['sample_date_time_no_past_date'],
		'transport' => 'refresh',
		'sanitize_callback' => 'textdomain_date_time_sanitization',
	)
);
$wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, 'sample_date_time_no_past_date',
	array(
		'label' => __( 'Default Date Control', 'textdomain' ),
		'description' => esc_html__( "This is the Date Time Control but is only displaying a date field. Past dates are not allowed.", 'textdomain' ),
		'section' => 'default_controls_section',
		'include_time' => false,
		'allow_past_date' => false,
		'twelve_hour_format' => true,
		'min_year' => '2016',
		'max_year' => '2099',
	)
) );
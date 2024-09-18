<?php

require_once('inc/acf.php');

function link_parent_theme_style()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'link_parent_theme_style');




/** version */
define('stowe_OUTREACH_ENGAGEMENT', '1.0.0');


function sandc_titlebar_register( $wp_customize ){
    
	
	// Add color options
	$wp_customize->add_setting( 'themeColor', //Give it a SERIALIZED name (so all theme settings can live under one db record)
		array(
			'default' => 'bs-color-husky-blue', //Default setting/value to save
			'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
			'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
			'transport' => 'refresh'
			)
		);
	$wp_customize->add_control('themeColor', array(
        'type' => 'radio',
        'label' => 'Accent Color',
        'section' => 'colors',
        'choices' => array(
			'bs-color-husky-blue' => 'Husky Blue',
			//'bs-color-royal-blue' => 'Royal Blue',
			'bs-color-imperial-purple' => 'Imperial Purple',
			'bs-color-pumpkin-orange' => 'Pumpkin Orange',
            'bs-color-emerald-green' => 'Emerald Green',
            'bs-color-ruby-red' => 'Ruby Red'
            
        	)
    	)
	);
	
	
	 // Add font options
	$wp_customize->add_section( 'fontStyle', array(
	  'title' => __( 'Font Style' ),
	  'description' => __( 'Select from pre-defined font combinations' ),
	  'panel' => '', // Not typically needed.
	  'priority' => 41,
	  'capability' => 'edit_theme_options',
	  'theme_supports' => '', // Rarely needed.
	) );
	
	$wp_customize->add_setting( 'fontSelect', //Give it a SERIALIZED name (so all theme settings can live under one db record)
		array(
			'default' => 'bs-font-sans', //Default setting/value to save
			'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
			'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
			'transport' => 'refresh'
			)
		);
	$wp_customize->add_control('fontSelect', array(
        'type' => 'radio',
        'label' => '',
        'section' => 'fontStyle',
        'choices' => array(
			'bs-font-sans' => 'UConn Brand Standard',
            'bs-font-plex' => 'Plex', 
			'bs-font-serif' => 'Newsworthy',
            'bs-font-book' => 'Book',
            'bs-font-compressed' => 'Compressed' 
        	)
    	)
	);
}
add_action( 'customize_register', 'sandc_titlebar_register' , 34);


function beecher_stowe_scripts() {
	wp_enqueue_script( 'bs-js', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ));	
	$stylesheet = get_theme_mod('themeColor');
	if( $stylesheet ){
		wp_enqueue_style( $stylesheet, get_stylesheet_directory_uri() . '/css/'.$stylesheet.'.css', array('cs-style') );
	} else {
		wp_enqueue_style( 'bs-husky-blue', get_stylesheet_directory_uri() . '/css/bs-color-husky-blue.css', array('cs-style') );
	}
	
	$stylesheet = get_theme_mod('fontSelect');
	if( $stylesheet ){
		wp_enqueue_style( $stylesheet, get_stylesheet_directory_uri() . '/css/'.$stylesheet.'.css', array('cs-style') );
	} else {
		wp_enqueue_style( 'bs-sans', get_stylesheet_directory_uri() . '/css/bs-font-sans.css', array('cs-style') );
	}
}

add_action( 'wp_enqueue_scripts', 'beecher_stowe_scripts', 50);

// enqueue scripts htmx cdn
add_action('wp_enqueue_scripts', 'stowe_child_outreach_engagement_enqueue_scripts');

function stowe_child_outreach_engagement_enqueue_scripts()
{
    wp_enqueue_script('htmx', 'https://unpkg.com/htmx.org/dist/htmx.min.js', array(), stowe_CHILD_STRATEGIC_PLAN_VERSION, true);
}







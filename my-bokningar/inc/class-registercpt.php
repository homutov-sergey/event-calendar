<?php

add_action( 'init', 'register_cpts_glasgaranti' );
function register_cpts_glasgaranti() {
	$labels = [
		"name" 			=> esc_html__( "Planbokningar", "bokningar" ),
		"singular_name" => esc_html__( "Planbokningar", "bokningar" ),
		'add_new'       => __( 'Add New Planbokningar', 'bokningar' ),
	];

	$args = [
		"label" => esc_html__( "My Planbokningar", "bokningar" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		'menu_icon' => 'dashicons-calendar-alt',
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"can_export" => false,
		"rewrite" => [ "slug" => "bokningar", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title" ],
		"show_in_graphql" => false,
	];

	register_post_type( "bokningar", $args );
}
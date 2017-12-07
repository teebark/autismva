<?php
/**
 * Functions - Child theme custom functions
 */


/*****************************************************************************************************************
************************** Caution: do not remove or edit anything within this section **************************/

/**
 * Loads the Divi parent stylesheet.
 * Do not remove this or your child theme will not work unless you include a @import rule in your child stylesheet.
 */
function dce_load_divi_stylesheet() {
    wp_enqueue_style( 'divi-parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'dce_load_divi_stylesheet' );

/**
 * Makes the Divi Children Engine available for the child theme.
 * Do not remove this or you will lose all the customization capabilities created by Divi Children Engine.
 */
require_once('divi-children-engine/divi_children_engine.php');

/****************************************************************************************************************/
/* Form handler for resource search used in page resource-finder */
/* The '-1' test is for a return value from wp_dropdown_category where no value is selected */
/* It normally returns the category ID of the category selected. */
/* The call to get_term_by can retrieve the slug or name, depending on the first parm sent. */
function prefix_resource_search_cat() {
	/* post variables available */
	$s = $_POST['s'];
	/*
	print_r ("cat = " . $_POST['$cat_id'] . ", len = " . strlen($_POST['$cat_id']));
	print_r ("age = " . $_POST['$age_id']);
	print_r ("region = " . $_POST['$region_id']);
	print_r ("tag = " . $_POST['tag_name'] . ", type = " . gettype($_POST['$tag_name']) . ", len = " . strlen($_POST['$tag_name']));
	*/
	$terms = get_term_by('name',$_POST['tag_name'],'post_tag');
	$tag_slug = $terms->slug;
	/* print_r ( "tag slug = " . $tag_slug); */
	/* The breaks force the category returned to be the first one the user selected */
	switch (false) {
	case ($_POST['$cat_id'] == "-1"):
		$terms = get_term_by('id',$_POST['$cat_id'],'resource_cat');
        /* print_r ( "cat slug = " . $terms->slug); */
	    $url='http://autismva.teebark.com/?post_type=resource_db&resource_cat=' . $terms->slug . '&s=' . $s . '&tag=' . $tag_slug;
		break;
	case ($_POST['$age_id'] == "-1"):
		$terms = get_term_by('id',$_POST['$age_id'],'resource_age');
        /* print_r ( "age slug = " . $terms->slug); */
	    $url='http://autismva.teebark.com/?post_type=resource_db&resource_age=' . $terms->slug . '&s=' . $s . '&tag=' . $tag_slug;
		break;
	case ($_POST['$region_id'] == "-1"):
		$terms = get_term_by('id',$_POST['$region_id'],'resource_region');
        /* print_r ( "region slug = " . $terms->slug); */
	    $url='http://autismva.teebark.com/?post_type=resource_db&resource_region=' . $terms->slug . '&s=' . $s . '&tag=' . $tag_slug;
		break;
	default:
	    $url='http://autismva.teebark.com/?post_type=resource_db' . '&s=' . $s . '&tag=' . $tag_slug;
		break;
	}
	/* print_r ("url = " . $url); */
	wp_redirect($url);
	exit; 
}
add_action ('admin_post_nopriv_resourece_search_cat', 'prefix_resource_search_cat');
add_action ('admin_post_resource_search_cat', 'prefix_resource_search_cat');

?>
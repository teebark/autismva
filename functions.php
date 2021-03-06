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
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'dce_load_divi_stylesheet' );

/**
 * Makes the Divi Children Engine available for the child theme.
 * Do not remove this or you will lose all the customization capabilities created by Divi Children Engine.
 */
require_once('divi-children-engine/divi_children_engine.php');

/* shortcode for iframe */
function ag_iframe($atts, $content) {
 if (!$atts['width']) { $atts['width'] = 630; }
 if (!$atts['height']) { $atts['height'] = 1500; }

 return '<iframe border="0" class="shortcode_iframe" src="' . $atts['src'] . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '"></iframe>';
}
add_shortcode('iframe', 'ag_iframe');

/****************************************************************************************************************/
/* Form handler for resource search used in page resource-finder */
/* The '-1' test is for a return value from wp_dropdown_category where no value is selected */
/* It normally returns the category ID of the category selected. */
/* The call to get_term_by can retrieve the slug or name, depending on the first parm sent. */
/****************************************************************************************************************/
function prefix_resource_search_cat() {
	/* Can't use 's' for a name here, as wp intercepts a '?s' */
	/* when the results page is posted */
	$s_term = $_POST['s'];
	/*
	print_r ("cat = " . $_POST['$cat_id'] . ", len = " . strlen($_POST['$cat_id']));
	print_r ("age = " . $_POST['$age_id']);
	print_r ("region = " . $_POST['$region_id']);
	print_r ("tag = " . $_POST['tag_name'] . ", type = " . gettype($_POST['$tag_name']) . ", len = " . strlen($_POST['$tag_name']));
	*/
	$terms = get_term_by('name',$_POST['tag_name'],'post_tag');
	$tag_slug = $terms->slug;
	$tag_name = $terms->name;
	/* print_r ( "tag slug = " . $tag_slug); */
	switch (false) {
	case ($_POST['$cat_id'] == "-1"):
		$terms = get_term_by('id',$_POST['$cat_id'],'resource_cat');
		$cat_slug = $terms->slug;
		$cat_name = $terms->name;
        /* print_r ( "cat slug = " . $terms->slug); */
	    /* $url='http://autismva.teebark.com/?post_type=resource_db&resource_cat=' . $terms->slug . '&s=' . $s . '&tag=' . $tag_slug; */
	case ($_POST['$age_id'] == "-1"):
		$terms = get_term_by('id',$_POST['$age_id'],'resource_age');
		$age_slug = $terms->slug;
		$age_name = $terms->name;
        /* print_r ( "age slug = " . $terms->slug); */
	    /* $url='http://autismva.teebark.com/?post_type=resource_db&resource_age=' . $terms->slug . '&s=' . $s . '&tag=' . $tag_slug; */
	case ($_POST['$region_id'] == "-1"):
		$terms = get_term_by('id',$_POST['$region_id'],'resource_region');
		$region_slug = $terms->slug;
		$region_name = $terms->name;
        /* print_r ( "region slug = " . $terms->slug); */
	    /* $url='http://autismva.teebark.com/?post_type=resource_db&resource_region=' . $terms->slug . '&s=' . $s . '&tag=' . $tag_slug; */
		break;
	}
	$url='http://autismva.it4causeshosting.org/?post_type=resource_db&resource_cat=' . $cat_slug . '&resource_age=' . $age_slug;
	$url=$url . '&resource_region=' . $region_slug . "&s=" . $s . '&tag=' . $tag_slug;
	/* print_r ("url = " . $url); */
	$args = array( 
		'cat_id'	  => $cat_id,
		'age_id'      => $age_id,
		'region_id'   => $region_id,
		'cat_slug'    => $cat_slug,
		'age_slug'    => $age_slug,
		'region_slug' => $region_slug,
		's_term'      => $s_term,
		'tag_slug'    => $tag_slug,
		'cat_name'    => $cat_name,
		'age_name'    => $age_name,
		'region_name' => $region_name,
		'tag_name'    => $tag_name
		);
	$url = add_query_arg($args,'http://autismva.it4causeshosting.org/resource-result-cat/');
	wp_safe_redirect($url);
	exit; 
}
add_action ('admin_post_nopriv_resource_search_cat', 'prefix_resource_search_cat');
add_action ('admin_post_resource_search_cat', 'prefix_resource_search_cat');

/* Set up for page parms */
function custom_query_vars_filter($vars) {
  $vars[] = 's_term';
  $vars[] = 'cat_id';
  $vars[] = 'age_id';
  $vars[] = 'region_id';
  $vars[] = 'cat_slug';
  $vars[] = 'age_slug';
  $vars[] = 'region_slug';
  $vars[] = 'tag_slug';
  $vars[] = 'cat_name';
  $vars[] = 'age_name';
  $vars[] = 'region_name';
  $vars[] = 'tag_name';
  return $vars;
}
add_filter( 'query_vars', 'custom_query_vars_filter' );

function resource_search_form( $form ) {
	$form = '<form id="category-select" class="category-select"  method="post" action="' . esc_url(admin_url('admin-post.php')) . '" >' .
		wp_dropdown_categories( 'show_count=1&hierarchical=1&depth=2&show_option_none=Select by category&name=$cat_id&taxonomy=resource_cat&echo=0' ) . '<br>' .
		wp_dropdown_categories( 'show_count=0&hierarchical=1&depth=1&show_option_none=Select by age&name=$age_id&taxonomy=resource_age&echo=0' ) . '<br>' .
		wp_dropdown_categories( 'show_count=0&exclude=98&show_option_none=Select by region&name=$region_id&taxonomy=resource_region&echo=0' ) . '<br>' .
		'<input type="text" name="s" placeholder="Search by keyword" /><br>
		<input type="text" name="tag_name" placeholder="Search by tag" />
		<input type="hidden" name="action" value="resource_search_cat" />
		<button type="submit" > Search </button>
	</form>';
return $form;
}
 
add_shortcode('resource_search', 'resource_search_form');

/* Called by openrefine to add link to website field */
function make_link ($website) {
	if ($website) {
		$website = '<a href="' . $website . '">' . $website . '</a>';
	return $website;
	}
}

/* Make text for website into link  */
function add_link_resource ($atts,$content) {
	$website = '<a href="' . $atts['website'] . '">' . $atts['website'] . '</a>';
	return $website;
}
add_shortcode( 'add-link', 'add_link_resource' );

/* Allows Divi to handle these custom post types */
/*
function my_et_builder_post_types( $post_types) {
	$post_types[] = 'resource_db';
	return $post_types;
}
add_filter( 'et_builder_post_types', 'my_et_builder_post_types' );
*/

?>
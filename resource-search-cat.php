<?php
/* Template Name: resource-search-cat */
get_header();
?>

<div id="main-content">

<?php /* if (isset($_POST['post_type'])) {
	$terms = get_term_by('id',$_REQUEST[$cat_id],'resource_cat');
    print_r ( "get = " . $terms->slug); 
} */ ?>

<li id="categories">
	<h2><?php _e( 'Resource Finder' ); ?></h2>
	<form id="category-select" class="category-select"  method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" >
		<?php wp_dropdown_categories( 'show_count=0&hierarchical=1&depth=1&show_option_none=Select by category&name=$cat_id&taxonomy=resource_cat' ); ?><br>
		<?php wp_dropdown_categories( 'show_count=0&hierarchical=1&depth=1&show_option_none=Select by age&name=$age_id&taxonomy=resource_age' ); ?><br>
		<?php wp_dropdown_categories( 'show_count=0&hierarchical=1&depth=1&show_option_none=Select by region&name=$region_id&taxonomy=resource_region' ); ?><br>
		<input type="text" name="s" placeholder="Search by keyword" /><br>
		<input type="text" name="tag_name" placeholder="Search by tag" />
		<input type="hidden" name="action" value="resource_search_cat" />
		<button type="submit" > Search </button>
	</form>
</li>
<br>
<br>

</div> <!-- #main-content -->

<?php get_footer(); ?>
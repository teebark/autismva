
<?php
/* Template Name: resource-search */
get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">

<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
		<input type="search" class="search-field" placeholder="Search..." value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ); ?>" />
	</label>
	<?php
		// output all of our Categories
		// for more information see http://codex.wordpress.org/Function_Reference/wp_dropdown_categories
		$swp_cat_dropdown_args = array(
				'show_option_all'  => __( 'Any Category' ),
				'name'             => 'resource_cat',
				'taxonomy'         => 'resource_cat',
				'value'            => 'slug',
				'show_option_all'  =>  __("Show All {$current_taxonomy->label}"),
				'selected'         =>  $wp_query->query['term'],
			);
		wp_dropdown_categories( $swp_cat_dropdown_args );
	?>
	<input type="submit" class="search-submit" value="Search" />
</form>

</div> <!-- #main-content -->

<?php get_footer(); ?>
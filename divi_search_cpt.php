/* Add search for custom post types */
function my_remove_default_et_pb_custom_search() {
	remove_action( 'pre_get_posts', 'et_pb_custom_search' );
	add_action( 'pre_get_posts', 'my_et_pb_custom_search' );
}
add_action( 'wp_loaded', 'my_remove_default_et_pb_custom_search' );
function my_et_pb_custom_search( $query = false ) {
	if ( is_admin() || ! is_a( $query, 'WP_Query' ) || ! $query->is_search ) {
		return;
	}
	if ( isset( $_GET['et_pb_searchform_submit'] ) ) {
		$postTypes = array();
		if ( ! isset($_GET['et_pb_include_posts'] ) && ! isset( $_GET['et_pb_include_pages'] ) ) $postTypes = array( 'post' );
		if ( isset( $_GET['et_pb_include_pages'] ) ) $postTypes = array( 'page' );
		if ( isset( $_GET['et_pb_include_posts'] ) ) $postTypes[] = 'post';
		
		/* BEGIN Add custom post types */
		$postTypes[] = 'resource_db';
		/* END Add custom post types */
		
		$query->set( 'post_type', $postTypes );
		if ( ! empty( $_GET['et_pb_search_cat'] ) ) {
			$categories_array = explode( ',', $_GET['et_pb_search_cat'] );
			$query->set( 'category__not_in', $categories_array );
		}
		if ( isset( $_GET['et-posts-count'] ) ) {
			$query->set( 'posts_per_page', (int) $_GET['et-posts-count'] );
		}
	}
}
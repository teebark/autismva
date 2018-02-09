<?php 
/* Template name: resource show results */
get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
		<?php
			$s_term = get_query_var('s_term',FALSE);
			$tag_id = get_query_var('tag_id',FALSE);
			$cat_id = get_query_var('cat_id',FALSE);
			$age_id = get_query_var('age_id',FALSE);
			$region_id = get_query_var('region_id',FALSE);
			$tag_slug = get_query_var('tag_slug',FALSE);
			$cat_slug = get_query_var('cat_slug',FALSE);
			$age_slug = get_query_var('age_slug',FALSE);
			$region_slug = get_query_var('region_slug',FALSE);
			$tag_name = get_query_var('tag_name',FALSE);
			$cat_name = get_query_var('cat_name',FALSE); 
			$region_virtual_slug = 'no-location';
			/* print_r ("cat = " . $cat_slug . ", type = " . gettype($cat_slug) . ", len = " . strlen($cat_slug)); */
			?>
			<strong>You searched on: </strong>
			<strong> Category: </strong><?php print_r ($cat_name); ?>
			<strong> Age: </strong><?php print_r ($age_name); ?>
			<strong> Region: </strong><?php print_r ($region_name); ?>
			<strong> Keyword: </strong><?php print_r ($s_term); ?>
			<strong> Tag: </strong><?php print_r ($tag_name); ?>
			<?php
			$args=array(
						array(
							'taxonomy'  => 'resource_cat',
							'field'     => 'slug',
							'terms'     => $cat_slug,
						),
						array(
							'taxonomy'  => 'resource_age',
							'field'     => 'slug',
							'terms'     => $age_slug,
						),
						array(
							'taxonomy'  => 'resource_region',
							'field'     => 'slug',
							'terms'     => array($region_slug,$region_virtual_slug)
						),
						array(
							'taxonomy'  => 'post_tag',
							'field'     => 'slug',
							'terms'     => $tag_slug,
						));
			/* Set the first item in array */
			$filter = array("relation' => 'AND'");

			/* Only add to filter array if a value is passed */
			if ($cat_slug != FALSE) {
				$filter[] = $args[0];
			}	
			if ($age_slug != FALSE) {
				$filter[] = $args[1];
			}	
			if ($region_slug != FALSE) {
				$filter[] = $args[2];
			}	
			if ($tag_slug != FALSE) {
				$filter[] = $args[3];
			}	
			/* var_dump ($filter); */
				
			$temp_post = $post; // Storing the object temp
			
			$query = new WP_Query(
				array(
					'post_type'  => 'resource_db',
					'showposts'  => 10,
					's'          => $s_term, 
				 	'tax_query'  => array ($filter)
				));
			?>
			<strong> Count: </strong><?php $total_count = $query->max_num_pages * $query->post_count; print_r($total_count); ?><br>
			<?php 
			if ( $query->have_posts() ) :
				while ( $query->have_posts() ) : $query->the_post();
					$post_format = et_pb_post_format(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_pb_post_main_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					et_divi_post_format_content();

					if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
						if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) :
							printf(
								'<div class="et_main_video_container">
									%1$s
								</div>',
								$first_video
							);
						elseif ( ! in_array( $post_format, array( 'gallery' ) ) && 'on' === et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb ) : ?>
							<a href="<?php the_permalink(); ?>">
								<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
							</a>
					<?php
						elseif ( 'gallery' === $post_format ) :
							et_pb_gallery_images();
						endif;
					} ?>

				<?php if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) : ?>
					<?php if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) : ?>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>

					<?php
						et_divi_post_meta();

						if ( 'on' !== et_get_option( 'divi_blog_style', 'false' ) || ( is_search() && ( 'on' === get_post_meta( get_the_ID(), '_et_pb_use_builder', true ) ) ) ) {
							truncate_post( 270 );
						} else {
							the_content();
						}
					?>
				<?php endif; ?>

					</article> <!-- .et_pb_post -->
			<?php
					endwhile;?>
					<div class="pagination">
    <?php 
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $query->max_num_pages,
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Posts', 'text-domain' ) ),
            'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Posts', 'text-domain' ) ),
            'add_args'     => false,
            'add_fragment' => '',
        ) );
    ?>
		</div>
			<?php
					$post = $temp_post; // Restore the value of $post to the original
					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
			?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
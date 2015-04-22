<?php  get_header();  ?>
<?php 

// set up or arguments for our custom query
$loop = new WP_Query(array('post_type' => 'video_post_type', 'posts_per_page' => 10, 'paged' => get_query_var('paged') ? get_query_var('paged') : 1 )); 
      
				
							
?>
<div class="ni-wrapper">
<?php
if ( have_posts() ) : 
	while ( $loop->have_posts() ) : $loop->the_post();
	/*Get Image URL */
	$video_url = 	get_post_meta( get_the_ID(), 'video_url', true );
	$video_url = str_replace("https://www.youtube.com/watch?v=","",$video_url);
	$categories = get_the_terms($post->ID, "video_taxonomy");
	$category_name ="";
	//print_r($categories );
	foreach ( $categories as $cat){
		if (strlen($category_name)>0)
			$category_name  .=  ", " .$cat->name;
		else
			$category_name  .=  $cat->name;
		
	}
	
	?>
    <div class="ni-box-1"><img src="http://img.youtube.com/vi/<?php echo $video_url; ?>/default.jpg" /> </div>
    <div class="ni-box-2">
    	<div class="ni-video-title">Title: <?php the_title(); ?></div>
        <div class="ni-video-category">Category: <?php echo $category_name; ?></div>
        <div class="ni-video-author">Author: <?php  the_author();?></div>
        <div class="ni-video-view"><a href='<?php the_permalink(); ?>'><span>view</span> </a></div>
    </div>
    <div style="clear:both"></div>
 	<?php endwhile; ?>
    
   <?php 
   	$big = 999999999; // need an unlikely integer
	 echo paginate_links( array(
		'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $loop->max_num_pages
	) );
	
	
   ?>
    
	<?php	else: ?>
	 <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php endif;	?>
</div>
<?php get_footer();?>


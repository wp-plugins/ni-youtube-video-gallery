<?php  get_header();  ?>
<?php
	/*if(have_posts()):
		while(have_posts()):the_post();
		the_title();
			$id = get_the_id();
			$meta_values = get_post_meta( $id );
				print_r($meta_values);
			if(!empty($post->post_content)){ 
				echo "<div class=\"page_content\" style=\" border:1px solid red; margin-bottom:15px;\">";
				the_title();
				echo "</div>";
			}					
		endwhile;
	endif;*/
	
	if(have_posts()):
		while(have_posts()):the_post();
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
        <div class="ni-single-wrap">
        	<div class="single-video">
            	<iframe width="420" height="315"
					src="http://www.youtube.com/embed/<?php echo $video_url; ?>?autoplay=1&rel=0">
				</iframe>
            </div>
            <div class="single-video-text">
            	<div class="single-video-date">
            		Date:   <span>  <?php echo the_date(); ?></span>
            	</div>
                <div class="single-video-title">
            		Title: <span> <?php the_title(); ?> </span>
            	</div>
                
                <div class="single-video-author">
            		Author:  <span> <?php  the_author();?></span>
            	</div>
                <div class="single-video-category">
            		Category:  <span> <?php echo $category_name; ?></span>
            	</div>
                <div class="single-video-description">
            		  <?php  echo  the_content();?> 
            	</div>
            </div>
        </div>
      	<?php
		endwhile;
	else:
	endif;
?>
<?php get_footer();?>

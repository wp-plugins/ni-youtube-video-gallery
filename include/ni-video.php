<?php
class ni_video{
	function __construct() {
		add_action( 'init',  array( &$this, 'init' ));
		add_action( 'admin_init', array( &$this, 'admin_init') );
		
		/*Save Custom Post Type*/
		add_action( 'save_post', array( &$this, 'save_post'), 10, 2 );
		/*Create Columns for Video Gallery*/
		add_filter( 'manage_edit-video_post_type_columns',  array( &$this,'video_columns' ));
		/*Dispaly Columns Data*/
		add_action( 'manage_posts_custom_column', array( &$this,'video_columns_data' ), 10, 2 );
	
		// create shortcode to list all clothes which come in blue
		add_shortcode( 'list-video-post', array( &$this,'video_post_type_shortcode') );
	
		include_once("ni-page-templater.php");
		add_action( 'plugins_loaded', array( 'ni_page_templater', 'get_instance' ) );
		
		/*Single Page Template*/
		add_filter('single_template',array( &$this,'single_template'));
		
		/*Add CSS*/
		add_action( 'wp_enqueue_scripts', array( &$this,'ni_scripts_basic' ));
		
		// Register style sheet.
		add_action( 'wp_enqueue_scripts', array( &$this,'register_plugin_styles' ));

		
	}
	/**
 	* 		Register style sheet.
 	*/
	function register_plugin_styles() {
		wp_register_style( 'ni-style', plugins_url('../css/ni-style.css', __FILE__) );
		wp_enqueue_style( 'ni-style' );
	}
	function ni_scripts_basic()
	{
		// Register the script like this for a plugin:
		wp_register_script( 'custom-script', plugins_url( '/js/custom-script.js', __FILE__ ) );
		// or
		// Register the script like this for a theme:
		wp_register_script( 'custom-script', get_template_directory_uri() . '/js/custom-script.js' );
	 
		// For either a plugin or a theme, you can then enqueue the script:
		wp_enqueue_script( 'custom-script' );
	}
	//route single- template
	function single_template($single_template){
	  global $post;
	  if($post->post_type == 'video_post_type')
	  {
		  $single_template = dirname(__FILE__).'/single-video_post_type.php';
		 
	  }
	  return $single_template;
	}
	
	
	function init(){
	/*Registered Post Type*/
		register_post_type( 'video_post_type', /*Name of Custome Post Type */
			array(
				'labels' => array(
					'name' => 'Video',
					'singular_name' => 'Video',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Video',
					'edit' => 'Edit',
					'edit_item' => 'Edit Video',
					'new_item' => 'New Video',
					'view' => 'View',
					'view_item' => 'View Video',
					'search_items' => 'Search Video',
					'not_found' => 'No Video found',
					'not_found_in_trash' => 'No Video found in Trash',
					'parent' => 'Parent Video'
				),
	 
				'public' => true,
				'menu_position' => 15,
				//'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
				'supports' => array( 'title', 'editor'),
				'taxonomies' => array( '' ),
				'menu_icon' => plugins_url( '../images/video.png', __FILE__ ),
				'has_archive' => true
			)
		);
	   /*Register Video Category */
	   register_taxonomy(
			'video_taxonomy',
			'video_post_type',
			array(
				'labels' => array(
					'name' => 'Video Category',
					'add_new_item' => 'Add New Video Category', /*Button Name*/
					'new_item_name' => "New Video Type Genre"
				),
				'show_ui' => true,
				'show_tagcloud' => false,
				'hierarchical' => true
			)
		);
	}
	function admin_init(){
	/*Register Video  Custom Meta Box*/
		add_meta_box( 'video_meta_box', 
			'Video Details',
			array( &$this, 'display_video_meta_box'), /*Name of Call Back or Display Meta Box Function*/
			'video_post_type', /*Custom Post Type Name*/
			'normal', 
			'high'
		);
	}
	/*Display Video Meta Box*/
	function display_video_meta_box($video)
	{
		
		$video_url = esc_html( get_post_meta( $video->ID, 'video_url', true ) );
		$order_by = esc_html( get_post_meta( $video->ID, 'order_by', true ) );
		?>
        <table>
        	<tr>
            	<td  style="width: 100%">Video Url</td>
                <td><input type="text" size="80"  name="video_url" value="<?php echo $video_url; ?>" /></td>
            </tr>
            <tr>
            	<td style="width: 100%" >Order By</td>
                <td><input type="text"  name="order_by" value="<?php echo $order_by; ?>" /></td>
            </tr>
        </table>
        <?php
	}
	/*save post type*/
	function save_post($video_id, $video ){
		
		if ( $video->post_type == 'video_post_type' ) {
			
			if ( isset( $_POST['video_url'] ) && $_POST['video_url'] != '' ) {
				update_post_meta( $video_id, 'video_url', $_POST['video_url'] );
			}
			if ( isset( $_POST['order_by'] ) && $_POST['order_by'] != '' ) {
				update_post_meta( $video_id, 'order_by', $_POST['order_by'] );
			}
		}
	}
	/*video_columns*/
	function video_columns()
	{//Author
		//Categories
		$columns['cb'] = '<input type="checkbox" />';	
		$columns['date'] = 'Date';
		$columns['title'] = 'Title';
		$columns['author'] = 'Author';
		$columns['cat'] = 'Categories';
		$columns['video_url'] = 'Video Url';
		$columns['order_by'] = 'Order By ';
		return $columns;	
	}
	/*video columns data*/
	function video_columns_data($column,$post_id )
	{
		 if ( 'video_url' == $column ) {
				$video_url = esc_html( get_post_meta( get_the_ID(), 'video_url', true ) );
				echo $video_url;
			}
			elseif ( 'order_by' == $column ) {
				$order_by = get_post_meta( get_the_ID(), 'order_by', true );
				echo $order_by ;
			}
			elseif ( 'title' == $column ) {
				$title = get_post_meta( get_the_ID(), 'title', true );
				echo $title ;
			}
			elseif ( 'date' == $column ) {
				$date = get_post_meta( get_the_ID(), 'date', true );
				echo $date ;
			}
			elseif ( 'cat' == $column ) {
				$terms = get_the_term_list( $post_id , 'video_taxonomy' , '' , ',' , '' );
            		if ( is_string( $terms ) )
						echo $terms;
					else
						echo "-";
			}
			elseif ( 'conversation' == $column ) {
				echo "dsad";
				$url = admin_url().'edit.php?post_type=movie_reviews&page=wnm_fund_set&ID='.$post_id;
				?>
                <a href="<?php echo $url;?>">Visit W3Schools</a>
                <?php
                
			}
	}
	function video_post_type_shortcode($atts)
	{	ob_start();
		$query = new WP_Query( array(
			'post_type' => 'video_post_type',
			'color' => 'blue',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'orderby' => 'title',
		) );
		if ( $query->have_posts() ) { ?>
			<ul class="clothes-listing">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>
				<?php endwhile;
				wp_reset_postdata(); ?>
			</ul>
		<?php $myvariable = ob_get_clean();
		return $myvariable;
		}
	}
}
?>
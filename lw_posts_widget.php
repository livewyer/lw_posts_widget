<?php
/*
Plugin Name: LW Posts Widget
Plugin URI: https://github.com/livewyer/lw_posts_widget
Description: Create a widget with a list of posts in WordPress.  Right now only for a selected category.
Author: Livewyer
Version: 0.1
*/

class lw_CustomPost_Widget extends WP_Widget {
  /** constructor */
	function __construct() {
		parent::WP_Widget( 'lw_post_widget', 'lw_Posts_Widget', array( 'description' => 'Creates a list of posts within a specified category.' ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		$cat_options = array(
		    'category' => $instance['list_cat'],
		    'numberposts' => $instance['numberposts'],
		    'orderby' => 'post_date',
		    'order' => 'DESC'
		);
		if ( $title )
		    echo $before_title . $title . $after_title; ?>
		    <div style="padding-left: 0.5em;">
		      <ul>
			<?php
			    echo lw_posts_widget($cat_options);
			?>
			<li style="font-size: 11px; padding-top: 20px;"><span class="feed_more_link"><a href="/index.php?cat=<?php echo $cat_options['category']; ?>">&raquo;All Posts in this category</a></span></li>
		      </ul>
		    </div>
		<?php echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 	 = strip_tags($new_instance['title']);
		$instance['list_cat'] 	 = strip_tags($new_instance['list_cat']);
		$instance['numberposts'] = strip_tags($new_instance['numberposts']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
			$list_cat = esc_attr( $instance['list_cat'] );
			$numberposts = esc_attr( $instance['numberposts'] );
		}
		else {
			$title = __( 'New title', 'text_domain' );
			$list_cat = __( 'Category ID' );
			$numberposts = __( 'Number of posts' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

		<label for="<?php echo $this->get_field_id('list_cat'); ?>"><?php _e('Category ID:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('list_cat'); ?>" name="<?php echo $this->get_field_name('list_cat'); ?>" type="text" value="<?php echo $list_cat; ?>" />

		<label for="<?php echo $this->get_field_id('numberposts'); ?>"><?php _e('Number of posts:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>" type="text" value="<?php echo $numberposts; ?>" />
		</p>
		<?php 
	}


	function lw_posts_widget($options) {
	    $posts_list = get_posts( $options );
	    $items = '';

	    foreach($posts_list as $catpost) : setup_postdata($catpost);
		$formatted_date = date('n-j-Y', strtotime(substr($catpost->post_date, 0, 10)));
		$items .= '<li><a href="' . get_permalink($catpost->ID) . '" rel="bookmark" title="Permanent link to ' . $catpost->post_title . '">' . $catpost->post_title . '</a> (' . $formatted_date . ')</li>';
	    endforeach;
	    
	    return $items;
	}
} // class Foo_Widget

// Register widget
add_action( 'widgets_init', create_function( '', 'register_widget("lw_CustomPost_Widget");' ) );

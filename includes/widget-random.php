<?php
/*
Plugin Name: Show Random Products
Description: Shows random products
Author: Alan Cesarini
Version: 1.0
*/
 

class srp_widget_random extends WP_Widget {

	function srp_widget_random() {

		$widget_ops = array( 'classname' => 'srp_widget_random', 'description' => __( 'Shows the random products widget', 'srp' ) );
		$this->WP_Widget( 'srp_widget_random', __( 'Show Random Products', 'srp' ), $widget_ops );
	
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'n' => 1, 'cols' => 1, 'cats' => '', 'tags' => '' ) );
		$n = $instance[ 'n' ];
		$cols = $instance[ 'cols' ];
		$selected_cats = $instance[ 'cats' ];
		$selected_tags = $instance[ 'tags' ];

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'n' ); ?>">
				<?php _e( 'Number of products to show:', 'srp' ); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'n' ); ?>" name="<?php echo $this->get_field_name( 'n' ); ?>" type="text" value="<?php echo attribute_escape( $n ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'cols' ); ?>">
				<?php _e( 'Number of cols:', 'srp' ); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'col' ); ?>" name="<?php echo $this->get_field_name( 'cols' ); ?>" type="text" value="<?php echo attribute_escape( $cols ); ?>" />
			</label>
		</p>	

		<p>
			<?php _e( 'Filter by product category:', 'srp' ); ?><br/> 
			<label for="<?php echo $this->get_field_id( 'cats' ); ?>">
				<?php 
					$categories = get_terms( array( 'product_cat' ) );
					echo '<ul style="-webkit-column-count: 2; -moz-column-count: 2; column-count: 2;">';

					foreach( $categories as $category ) {
						echo '<li><input type="checkbox" name="' . $this->get_field_name( 'cats' ) . '[]" value="' . $category->term_id . '"';
						if( in_array( $category->term_id, $selected_cats ) ) {
							echo 'checked';
						}
						echo ' />' . $category->name . '</li>';
					}
					echo '</ul>';
				?>
			</label>
		</p>

		<p>
			<?php _e( 'Filter by product tags:', 'srp' ); ?><br/> 
			<label for="<?php echo $this->get_field_id( 'tags' ); ?>">
				<?php 
					$tags = get_terms( array( 'product_tag' ) );
					echo '<ul style="-webkit-column-count: 2; -moz-column-count: 2; column-count: 2;">';

					foreach( $tags as $tag ) {
						echo '<li><input type="checkbox" name="' . $this->get_field_name( 'tags' ) . '[]" value="' . $tag->term_id . '"';
						if( in_array( $tag->term_id, $selected_tags ) ) {
							echo 'checked';
						}
						echo ' />' . $tag->name . '</li>';
					}
					echo '</ul>';
				?>
			</label>
		</p>

		<?php
	
	}
 
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		$instance[ 'n' ] = $new_instance[ 'n' ];
		$instance[ 'cols' ] = $new_instance[ 'cols' ];
		$instance[ 'cats' ] = $new_instance[ 'cats' ];
		$instance[ 'tags' ] = $new_instance[ 'tags' ];
		return $instance;
	
	}
 
	function widget( $args, $instance ) {

		echo $before_widget;
		
		$n = empty( $instance[ 'n' ] ) ? 1 : $instance[ 'n' ];
 		$cols = empty( $instance[ 'cols' ] ) ? 1 : $instance[ 'cols' ];
 		$cats = empty( $instance[ 'cats' ] ) ? '' : $instance[ 'cats' ];

 		if( is_array( $cats ) ) {
 			$cats = implode( ',', $cats );
 		}
 		$tags = empty( $instance[ 'tags' ] ) ? '' : $instance[ 'tags' ];
 		if( is_array( $tags ) ) {
 			$tags = implode( ',', $tags );
 		}	
	
		$args = array(
			'n' => $n,
			'cols' => $cols,
			'cats' => $cats,
			'tags' => $tags
		);

		$random = new SRP_Random();
		$random->render( $args );
 
		echo $after_widget;

	}
 
}

add_action( 'widgets_init', create_function( '', 'return register_widget( "srp_widget_random" );' ) );

?>
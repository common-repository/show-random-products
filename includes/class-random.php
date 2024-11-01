<?php

if( !class_exists( 'SRP_Random' ) ) {
	
	class SRP_Random {

		function render( $params ) {

			//die(var_dump($params[ 'cats' ]));

		    $args = array(
		    	'post_type' => 'product',
		    	'posts_per_page' => $params[ 'n' ]
		    );

		    if( $params[ 'cats' ] != '' || $params[ 'tags' ] != '' ) {
		    	if( $params[ 'cats' ] != '' && $params[ 'tags' ] != '' ) {
		    		$args[ 'tax_query' ] = 	array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => explode( ',', $params[ 'cats' ] ),
							'operator' => 'IN'
						),
						array(
							'taxonomy' => 'product_tag',
							'field'    => 'term_id',
							'terms'    => explode( ',', $params[ 'tags' ] ),
							'operator' => 'IN'
						)
					);
		    	} else {
		    		if( $params[ 'cats' ] != '' ) {
				    	$args[ 'tax_query' ] = array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => explode( ',', $params[ 'cats' ] ),
								'operator' => 'IN'
							)
						);
		    		} else {
				    	$args[ 'tax_query' ] = array(
							array(
								'taxonomy' => 'product_tag',
								'field'    => 'term_id',
								'terms'    => explode( ',', $params[ 'tags' ] ),
								'operator' => 'IN'
							)
						);
		    		}
		    	}
		    }

		    //die(var_dump($args['tax_query']));

		    $products = new WP_Query( $args );

		    if( $products->have_posts() ) {
		    	switch( $params[ 'cols' ] ) {
		    		case 1;
		    			$style_li = 'width: 100%;';
		    			break;
		    		case 2:
		    			$style_li = 'width: 46%; margin: 1em 2%';
		    			break;
		    		case 3:
		    	   		$style_li = 'width: 30.3%; margin: 1em 1.5%';
		    	   		break;
		    	   	case 4:
		    			$style_li = 'width: 20%; margin: 1em 2.5%';
		    	}	

		    	echo '<div class="woocommerce srp-products"><ul class="products">';

		    	while( $products->have_posts() ) {
		    		$products->the_post();
		    		$product = new WC_Product( get_the_ID() );
					?>
					<li style="<?php echo $style_li; ?>">
						
						<a href="<?php the_permalink(); ?>">

							<?php 
							if ( has_post_thumbnail( get_the_ID() ) ) {

								$image = get_the_post_thumbnail( get_the_ID(), 'thumbnail', array(
									'title'	=> get_the_title(),
									'alt'	=> get_the_title(),
									'class' => 'srp-product-image'
									) );
								
								echo $image;

							} else {

								echo '<img src="' . wc_placeholder_img_src() . '" />';

							}
							?>

							<h3><?php the_title(); ?></h3>

							<?php if ( $price_html = $product->get_price_html() ) : ?>
								<span class="price"><?php echo $price_html; ?></span>
							<?php endif; ?>	

						</a>
					
					</li>
					<?php
				}
				echo '</ul></div>';
			}			

			wp_reset_query();
			wp_reset_postdata();
					
		}
		
	}
	
}


<div class="featuredproduct-module">
<div class="module-title">
	<h2><?php echo $title; ?></h2>
</div>
<div>
<div class="featured-product-slider quickview-added">
  <?php 
	$count = 0;
	$rows = $config_slide['f_rows']; 
	if(!$rows) { $rows=1; }
  ?>
  <?php if($products): ?>
    <?php foreach ($products as $product) { ?>
    <?php if($count++ % $rows == 0 ) { echo '<div class="row_items'; echo ($rows>=2) ? ' multi-row': ' one-row'; echo  '">'; }  ?>
    <div class="product-layout product-grid ">
							  <div class="product-thumb layout1">
								  <div class="image">
								  <?php if($config_slide['f_show_label']): ?>
											  <?php if($product['special']) { ?>
										<div class="label-product <?php if($product['is_new']){ echo " f-label "; } ?>">
											<span><?php echo $text_sale; ?></span>
										</div>
									<?php }?>
									<?php if($product['is_new']){ ?>
									<div class="label-product l-new">
										<span><?php echo $text_new; ?></span>
									</div>
									<?php } ?>
                                  <?php endif;?>
								  <a href="<?php echo $product['href']; ?>">
								  <?php if ($product['thumb']) { ?>
									<img  src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive lazy" />
									<?php if($product['rotator_image']){ ?><img class="img-r lazy"  src="<?php echo $product['rotator_image']; ?>" alt="<?php echo $product['name']; ?>" /><?php } ?>
								  <?php } else { ?>
									<img src="image/cache/no_image-100x100.png" alt="<?php echo $product['name']; ?>" />
								  <?php } ?>
								  </a>
								  <div class="actions-link">
										<a class="btn-wishlist" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-favorite-outline"></i></a>
										<a class="btn-compare" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-refresh-alt"></i></a>
									</div>
								</div>
	
								<div class="product-inner">
								  <div class="product-caption">
								 
								  <h2 class="product-name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h2>
								  <?php if($config_slide['f_show_des']) { ?>
									<p class="product-des"><?php echo $product['description']; ?></p>
									<?php } ?>
									<?php if (isset($product['rating'])) { ?>
									  <div class="ratings">
										  <div class="rating-box">
											  <?php for ($i = 0; $i <= 5; $i++) { ?>
												  <?php if ($product['rating'] == $i) {
													  $class_r= "rating".$i;
													  echo '<div class="'.$class_r.'">rating</div>';
												  } 
											  }  ?>
										  </div>
									  </div>
								  <?php } ?>
								  <?php if($config_slide['f_show_price']) { ?>
								  <?php if ($product['price']) { ?>
									<p class="price">
									  <?php if (!$product['special']) { ?>
									  <?php echo $product['price']; ?>
									  <?php } else { ?>
									  <span class="price-new"><?php echo $product['special']; ?></span>
									  <span class="price-old"><?php echo $product['price']; ?></span>
									  <?php } ?>
									</p>
									<?php } ?>
									<?php } ?>
								  
								  <div class="product-intro">
										<div class="actions-link2">
											<?php if($config_slide['f_show_addtocart']) { ?>
												<a class="btn-cart" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>');"><span class="button"><i class="fa fa-shopping-cart"></i><?php echo $button_cart; ?></span></a>
											<?php } ?>											
										</div>
									</div>
								  </div>
								  
								</div>
							  </div>
							</div>
     <?php if($count % $rows == 0 || $count == count($products)) { echo '</div>'; }  ?>
    <?php } ?>
  <?php else: ?>
    <p><?php echo $text_empty; ?></p>
  <?php endif; ?>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() { 
  $(".featured-product-slider").owlCarousel({
    autoPlay: <?php if($config_slide['autoplay']) { echo 'true' ;} else { echo 'false'; } ?>,
    items : <?php if($config_slide['items']) { echo $config_slide['items'] ;} else { echo 3; } ?>,
    slideSpeed : <?php if($config_slide['f_speed']) { echo $config_slide['f_speed']; } else { echo 200;} ?>,
    navigation : <?php if($config_slide['f_show_nextback']) { echo 'true' ;} else { echo 'false'; } ?>,
    paginationNumbers : true,
    pagination : <?php if($config_slide['f_show_ctr']) { echo 'true' ;} else { echo 'false';} ?>,
    stopOnHover : false,
	navigationText : ['<i class="zmdi zmdi-chevron-left"></i>','<i class="zmdi zmdi-chevron-right"></i>'],
	itemsDesktop : [1199,3],
	itemsDesktopSmall : [991,2],
	itemsTablet: [640,2],
	itemsMobile : [480,1],
	afterInit : function(){
	//remove class active
		this.$owlItems.removeClass('first')
		this.$owlItems.removeClass('last')

		//add class first and last
		this.$owlItems .eq(this.currentItem).addClass('first');
		this.$owlItems .eq(this.currentItem + (this.owl.visibleItems.length - 1)).addClass('last');
	},
	afterAction: function(el){
		//remove class active
		this.$owlItems.removeClass('first')
		this.$owlItems.removeClass('last')
		//add class first and last
		this.$owlItems .eq(this.currentItem).addClass('first');
		this.$owlItems .eq(this.currentItem + (this.owl.visibleItems.length - 1)).addClass('last');
	},
  });
});
</script></div>
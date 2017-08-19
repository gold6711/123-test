<div class="clearfix"></div>
<?php $tab_effect = 'wiggle'; ?>
<script type="text/javascript">
$(document).ready(function() {

	$(".tab_content").hide();
	$(".tab_content:first").show(); 

	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$(".tab_content").hide();
		$(".tab_content").removeClass("animate1 <?php echo $tab_effect;?>");
		var activeTab = $(this).attr("rel"); 
		$("#"+activeTab) .addClass("animate1 <?php echo $tab_effect;?>");
		$("#"+activeTab).fadeIn(); 
	});
});
</script>
<div class="product-tabs-container-slider quickview-added qv-text">
<div class="row">	
	<?php if(isset($config_slide['image'])){ ?>
	<div class="title-image col-lg-4 col-sm-12 col-md-12 col-xs-12 hidden-md hidden-sm hidden-xs">
		<img src="<?php echo $config_slide['image'] ?>" alt="#" >
	</div>
	<?php } ?>
	<div class="<?php if(isset($config_slide['image'])){ ?>col-lg-8 col-sm-12 col-md-12 col-xs-12<?php } ?>">
	<div class="title-product-tabs">
		<div class="module-title">
			<h2><?php echo $title; ?></h2>
		</div>
		<ul class="tabs"> 
		<?php $count=0; ?>
		<?php foreach($productTabslider as $productTab ){ ?>
			<li rel="tab_<?php echo $productTab['id']; ?>">
				<span class="tab_<?php echo $productTab['id']; ?>"><?php echo $productTab['name']; ?></span>
			</li>
				<?php $count= $count+1; ?>
		<?php } ?>	
		</ul>
	</div>	
	<div class="tab_container owl-style2">
		<?php foreach($productTabslider as $productTab){ ?>
			<div id="tab_<?php echo $productTab['id']; ?>" class="tab_content">
				<div>
				<div class="owl-demo-tabproduct">
                <?php if($productTab['productInfo']): ?>
                    <?php
                        $count = 0;
                        $rows = $config_slide['f_rows'];
                        if(!$rows) { $rows=1; }
                    ?>
                    <?php foreach ($productTab['productInfo'] as $product) { ?>
                        <?php  if($count % $rows == 0 ) { echo '<div class="row_items">'; } $count++; ?>
                            <div class="product-layout product-grid">
					<div class="product-thumb layout1">
						<div class="image">
							<a class="product-image" href="<?php echo $product['href']; ?>">
								<?php if($product['rotator_image']): ?>
								<img class="img-r lazy" src="<?php echo $product['rotator_image']; ?>" alt="<?php echo $product['name']; ?>" />
								<?php endif; ?>
								<img class="lazy" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
							</a>
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
							<?php endif; ?>
							<div class="actions-link">
							<a class="btn-wishlist" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-favorite-outline"></i></a>
							<a class="btn-compare" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-refresh-alt"></i></a>
						</div>
							</div><!-- image -->
					<div class="product-inner">
						<div class="product-caption">
							<h2 class="product-name">
								<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
							</h2>
							
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
					<?php if($config_slide['f_show_des']) { ?>
						<p class="product-des"><?php echo $product['description']; ?></p>
					<?php } ?>
					
					<?php if($config_slide['f_show_price']) { ?>
						<p class="price">
						  <?php if (!$product['special']) { ?>
							<?php echo $product['price']; ?>
						  <?php } else { ?>
								<span class="price-new"><?php echo $product['special']; ?></span>
								<span class="price-old"><?php echo $product['price']; ?></span>
						  <?php } ?>
						</p>
						<?php } ?>
					<div class="product-intro">
						<div class="actions-link2">
							<?php if($config_slide['f_show_addtocart']) { ?>
							<a class="btn-cart" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>');"><span class="button"><i class="fa fa-shopping-cart"></i><?php echo $button_cart; ?></span></a>
							<?php } ?>
						</div>						
					</div>
				</div>
				
				</div><!-- product-inner -->
				</div>				
				</div>
                        <?php if($count % $rows == 0 || $count == count($productTab['productInfo'])) { echo '</div>'; } ?>
                    <?php } ?>
                <?php else: ?>
                    <p><?php echo $productTab['text_empty']; ?></p>
                <?php endif; ?>
				</div>
				</div><!-- .row -->
			</div>
		<?php } ?>
	</div><!-- .tab_container -->
	</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() { 
 $(".product-tabs-container-slider .tabs li:first").addClass("active");
  $(".owl-demo-tabproduct").owlCarousel({
		items : <?php if($config_slide['items']) { echo $config_slide['items'] ;} else { echo 3;} ?>,
		autoPlay : <?php if($config_slide['autoplay']) { echo 'true' ;} else { echo 'false';} ?>,
		slideSpeed: <?php if($config_slide['f_speed']) { echo $config_slide['f_speed'] ;} else { echo 3000;} ?>,
		navigation : <?php if($config_slide['f_show_nextback']) { echo 'true' ;} else { echo 'false';} ?>,
		paginationNumbers : true,
		pagination : <?php if($config_slide['f_show_ctr']) { echo 'true' ;} else { echo 'false';} ?>,
		stopOnHover : false,
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [768,2], 
		itemsMobile : [480,1],
		navigationText : ['<i class="zmdi zmdi-chevron-left"></i>','<i class="zmdi zmdi-chevron-right"></i>'],
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
</script>
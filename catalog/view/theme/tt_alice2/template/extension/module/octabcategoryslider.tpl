<?php $tab_effect = 'wiggle'; ?>
<script type="text/javascript">
$(document).ready(function() {
	$(".<?php echo $cateogry_alias;?> .tab_content_category").hide();
	$(".<?php echo $cateogry_alias;?> .tab_content_category:first").show(); 
    $(".<?php echo $cateogry_alias;?> .tabs-categorys li:first").addClass("active");
	$(".<?php echo $cateogry_alias;?> .tabs-categorys li").click(function() {
		$(".<?php echo $cateogry_alias;?> .tabs-categorys li").removeClass("active");
		$(".<?php echo $cateogry_alias;?> .tabs-categorys .owl-item").removeClass("active");
		$(this).addClass("active");
		$(this).parent().addClass("active");
		$(".<?php echo $cateogry_alias;?> .tab_content_category").hide();
		$(".<?php echo $cateogry_alias;?> .tab_content_category").removeClass("animate1 <?php echo $tab_effect;?>");
		var activeTab = $(this).attr("rel"); 
		$("#"+activeTab) .addClass("animate1 <?php echo $tab_effect;?>");
		$("#"+activeTab).show(); 
	});
});
</script>
<?php
	$row = $config_slide['f_rows'];
	if(!$row) {$row=1;}
?>
<div class="product-tabs-category-container-slider <?php echo $cateogry_alias;?>">
    
<div class="row">
	<div class="col-lg-3 hidden-xs hidden-sm hidden-md col-img-cover">
		<div class="title-image">
			<img src="<?php echo $config_slide['image2'] ?>" alt="#" >
		</div>
	</div>
	<div class="col-md-12 col-lg-9">
	<div class="module-title">
	<div class="row">
	<div class=" col-xs-12 col-sm-5 col-md-4 col-lg-4">
        <h2>
			<?php 
				$title2 = explode(' ',$title,3); 
				for($i=0;$i<count($title2);$i++){ 
					$j=$i+1;
					echo $j>1 ? "<span class='word2'> ".$title2[$i]." </span>" : "<span> ".$title2[$i]." </span>";
				}
			?>
		</h2>
		</div>
		<div class=" col-xs-12 col-sm-7 col-md-8 col-lg-8">
		<ul class="tabs-categorys hidden-md hidden-sm hidden-xs"> 
			<?php $count=0; ?>
			<?php foreach($category_products as $cate_id => $products ){ ?>
				<?php if (isset($array_cates[$cate_id]['name'])) {?>
				<li rel="tab_cate<?php echo $cate_id; ?>"  >
					<?php  echo $array_cates[$cate_id]['name']; ?>
					<?php if($array_cates[$cate_id]['thumbnail_image']): ?>
						<img class="thumb-img" src="<?php echo $array_cates[$cate_id]['thumbnail_image']; ?>" alt="<?php echo $array_cates[$cate_id]['name']; ?>" />
					<?php endif; ?>
				</li>
				<?php } ?>
					<?php $count= $count+1; ?>
			<?php } ?>	
    	</ul>
		<div class="tabs-categorys btn-group hidden-lg">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="zmdi zmdi-format-align-justify"></i>
			</button>
			<ul class="dropdown-menu"> 
			<?php $count=0; ?>
			<?php foreach($category_products as $cate_id => $products ){ ?>
				<?php if (isset($array_cates[$cate_id]['name'])) {?>
				<li rel="tab_cate<?php echo $cate_id; ?>"  >
					<?php  echo $array_cates[$cate_id]['name']; ?>
					<?php if($array_cates[$cate_id]['thumbnail_image']): ?>
						<img class="thumb-img" src="<?php echo $array_cates[$cate_id]['thumbnail_image']; ?>" alt="<?php echo $array_cates[$cate_id]['name']; ?>" />
					<?php endif; ?>
				</li>
				<?php } ?>
					<?php $count= $count+1; ?>
			<?php } ?>	
    	</ul>
		</div>
		</div>
		</div>
	</div>
	<div class="tab_container_category quickview-added qv-wtext"> 
		<?php foreach($category_products as $cate_id => $products ){ ?>
			<div id="tab_cate<?php echo $cate_id; ?>" class="tab_content_category">
				<div class="productTabContent owl-demo-tabcate">
				<?php if($products): ?>
				<?php $count = 0; ?>
				<?php foreach ($products as $product){ ?>
					<?php if($count % $row ==0){  echo  "<div class='row_items'>";} $count++; ?>
				 	<div class="product-layout product-grid">
			  <div class="product-thumb layout1">
				  <div class="image">
				  <?php if($config_slide['tab_cate_show_label']): ?>
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
					
					<?php if($product['rotator_image']){ ?><img class="img-r lazy" src="<?php echo $product['rotator_image']; ?>" alt="<?php echo $product['name']; ?>" /><?php } ?>
				  <?php } else { ?>
					<img src="image/cache/no_image-100x100.png" alt="<?php echo $product['name']; ?>" />
				  <?php } ?>
				  <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive lazy" />
				  </a>
						<div class="actions-link">
							<a class="btn-wishlist" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-favorite-outline"></i></a>
							<a class="btn-compare" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-refresh-alt"></i></a>
						</div>
				</div>

				<div class="product-inner">
				  <div class="product-caption">
				
				  <h2 class="product-name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h2>
				  <?php if($config_slide['tab_cate_show_des']) { ?>
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
				  <?php if($config_slide['tab_cate_show_price']) { ?>
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
							<?php if($config_slide['tab_cate_show_addtocart']) { ?>
								<a class="btn-cart" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>');"><span class="button"><i class="fa fa-shopping-cart"></i><?php echo $button_cart; ?></span></a>
							<?php } ?>							
						</div>
					</div>
				  </div>
				  
				</div>
			  </div>
			</div>
					<?php if($count % $row == 0 ): ?>
					</div><!-- row_items-->
				 <?php else: ?>
					<?php if($count == count($products)): ?>
						</div><!-- row_items-->
					<?php endif; ?>
				 <?php endif; ?>
				<?php } ?>
				<?php else: ?>
					<p><?php echo $text_empty; ?></p>
				<?php endif; ?>
				</div><!-- productTabContent -->
			</div>
		<?php } ?>
	 </div> <!-- .tab_container_category -->
	</div>
	
	 </div>
</div><!-- <?php echo $cateogry_alias;?> -->
<script type="text/javascript">
$(document).ready(function() { 
  $(".<?php echo $cateogry_alias;?> .owl-demo-tabcate").owlCarousel({
	autoPlay: <?php if($config_slide['tab_cate_autoplay']) { echo 'true' ;} else { echo 'false';} ?>,
	items : <?php if($config_slide['items']) { echo $config_slide['items'] ;} else { echo 3;} ?>,
	slideSpeed : <?php if($config_slide['tab_cate_speed_slide']) { echo $config_slide['tab_cate_speed_slide'] ;} else { echo 200;} ?>,
	navigation : <?php if($config_slide['tab_cate_show_nextback']) { echo 'true' ;} else { echo 'false';} ?>,
	paginationNumbers : true,
	pagination : <?php if($config_slide['tab_cate_show_ctr']) { echo 'true' ;} else { echo 'false';} ?>,
	stopOnHover : false,
	addClassActive: true,
	lazyLoad : true,
	itemsDesktop : [1199,4],
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
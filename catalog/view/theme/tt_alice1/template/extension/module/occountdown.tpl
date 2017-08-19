<script type="text/javascript">
	if (typeof OC == 'undefined') OC = { };
	OC.Countdown = {
		TEXT_YEARS 		: '<?php echo $text_years ?>',
		TEXT_MONTHS 	: '<?php echo $text_months ?>',
		TEXT_WEEKS 		: '<?php echo $text_weeks ?>',
		TEXT_DAYS 		: '<?php echo $text_days ?>',
		TEXT_HOURS 		: '<?php echo $text_hours ?>',
		TEXT_MINUTES 	: '<?php echo $text_minutes ?>',
		TEXT_SECONDS 	: '<?php echo $text_seconds ?>'
	};
</script>
<script type="text/javascript" src="catalog/view/javascript/jquery.countdown.js"></script>
<?php
	  $count = 0;
	  $rows = $config_slide['f_rows'];
	if(!$rows) { $rows=1; }
   ?>
<div class="occountdown_module quickview-added qv-wtext">
<div class="countdown-title module-title"><h2><?php echo $title; ?></h2></div>
<?php if($products): ?>
	<div>
	<div class="countdown-product-inner">
    <?php foreach ($products as $product) : ?>
    <?php  if($count % $rows == 0 ) { echo '<div class="row_items">'; } $count++; ?>
            <div class="product-layout product-grid">
					<div class="product-thumb layout2">
						<div class="image">
							<a class="product-image" href="<?php echo $product['href']; ?>">
								<?php if($product['rotator_image']): ?>
								<img class="img-r lazy" src="<?php echo $product['rotator_image']; ?>" alt="<?php echo $product['name']; ?>" />
								<?php endif; ?>
								<img class="lazy" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
							</a>
							<?php if(!$config_slide['f_show_label']): ?>
								<?php if($product['is_new']){ ?>
									<div class="label-product l-new">
										<span><?php echo $text_new; ?></span>
									</div>
									<?php } ?>
									<?php if($product['special']) { ?>
										<div class="label-product <?php if($product['is_new']){ echo " f-label "; } ?>">
											<span><?php echo round(($product['special_num']-$product['orgprice_num'])/$product['orgprice_num']*100)."%"; ?></span>
										</div>
								<?php }?>
							<?php endif; ?>
							<div class="actions-link"></div>							
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
							<?php if ($product['orgprice']) { ?>
								<p class="price">
								  <?php if (!$product['special']) { ?>
									<?php echo $product['orgprice']; ?>
								  <?php } else { ?>
										<span class="price-new"><?php echo $product['special']; ?></span>
										<span class="price-old"><?php echo $product['orgprice']; ?></span>
								  <?php } ?>
								</p>
							<?php } ?>
							<div class="description"><?php echo $product['description']; ?></div>		
<?php if(strtotime($product['date_end'])) { ?>
								<div id="Countdown<?php echo $product['product_id']?>" class="box-timer">									
									<div class="block-timer">
										<input class="knob days" data-max="3650">
										<span><?php echo $text_days ?></span>
									</div>
									<div class="block-timer">
										<input class="knob hour" data-max="24">
										<span><?php echo $text_hours ?></span>
									</div>
									<div class="block-timer">
										<input class="knob minute" data-max="60">
										<span><?php echo $text_minutes ?></span>
									</div>
									<div class="block-timer">
										<input class="knob second" data-max="60">
										<span><?php echo $text_seconds ?></span>
									</div>
								</div>
								<script type="text/javascript">
									$('#Countdown<?php echo $product['product_id']?>').ccountdown(<?php echo date("Y",strtotime($product['date_end']))?>,<?php echo date("m",strtotime($product['date_end']))?>,<?php echo date("d",strtotime($product['date_end']))?>,<?php echo "'".date("H",strtotime($product['date_end'])).":" ?>,<?php echo date("s",strtotime($product['date_end']))."'" ?>);
									$(".knob").knob({
										min: 0,
										readOnly: true,
										thickness: ".04",
										width: "100%",
										height: "100%",
										displayPrevious: "true",
										fgColor: "#f9ba48",
										inputColor: "#373737",
										font: "Arial, sans-serif",
										fontWeight: "900",
										bgColor: "#ededed"
									});
								</script>
							<?php } ?>							
							<div class="product-intro">
								<div class="actions-link2">
									<a class="btn-cart" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="icon-basket-loaded"></i><span class="button"><?php echo $button_cart; ?></span></a>
									<a class="btn-wishlist" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-favorite-outline"></i></a>
									<a class="btn-compare" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-refresh-alt"></i></a>
								</div>						
							</div>
					
					</div>
				
				</div><!-- product-inner -->
				</div>				
				</div>
				<?php if($count % $rows == 0 || $count == count($products)): ?>
					</div>
				<?php endif; ?>
    <?php endforeach;  ?>
	</div>
	</div>
<?php else: ?>
<p><?php echo $text_empty; ?></p>
<?php endif; ?>
</div>

<script type="text/javascript">
	$('.countdown-product-inner').owlCarousel({
		autoPlay : <?php if($config_slide['autoplay']) { echo 'true' ;} else { echo 'false';} ?>,
		items : <?php if($config_slide['items']) { echo $config_slide['items'] ;} else { echo 3;} ?>,
		itemsDesktop : [1199,1],
		itemsDesktopSmall : [991,1],
		itemsTablet: [767,1],
		itemsMobile : [480,1],
		slideSpeed : <?php if($config_slide['f_speed']) { echo $config_slide['f_speed'] ;} else { echo 2000;} ?>,
		paginationSpeed : 3000,
		navigation : <?php if($config_slide['f_show_nextback']) { echo 'true' ;} else { echo 'false';} ?>,
		stopOnHover : true,
		pagination : <?php if($config_slide['f_show_ctr']) { echo 'true' ;} else { echo 'false';} ?>,
		scrollPerPage:true,
		addClassActive: true,
		lazyLoad : true,
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
		navigationText : ['<i class="zmdi zmdi-chevron-left"></i>','<i class="zmdi zmdi-chevron-right"></i>'],
	});
</script>
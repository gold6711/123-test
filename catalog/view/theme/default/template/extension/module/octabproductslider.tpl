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
<div class="product-tabs-container-slider">
	<div class="title-product-tabs">
        <h2><?php echo $title; ?></h2>
		<div class="container">
			<ul class="tabs"> 
			<?php $count=0; ?>
			<?php foreach($productTabslider as $productTab ){ ?>
				<li rel="tab_<?php echo $productTab['id']; ?>"  >
					<h3 class="tab_<?php echo $productTab['id']; ?>"><?php echo $productTab['name']; ?></h3>
				</li>
					<?php $count= $count+1; ?>
			<?php } ?>	
			</ul>
		</div>
	</div>
	<div class="tab_container">
		<div class="container">
		<?php foreach($productTabslider as $productTab){ ?>
			<div id="tab_<?php echo $productTab['id']; ?>" class="tab_content">
				<div class="row">
				<div class="owl-demo-tabproduct">
                <?php if($productTab['productInfo']): ?>
                    <?php
                        $count = 0;
                        $rows = $config_slide['f_rows'];
                        if(!$rows) { $rows=1; }
                    ?>
                    <?php foreach ($productTab['productInfo'] as $product) { ?>
                        <?php  if($count % $rows == 0 ) { echo '<div class="row_items">'; } $count++; ?>
                            <di<div class="item">
            <div class="item-inner product-thumb transition">
              <div class="images-container">
                <a class="product-image" href="<?php echo $product['href']; ?>">
                  <span class="product-image-wrapper" style="padding-bottom: 100%;">
                    <?php if($product['rotator_image']): ?>
                    <img class="img2 image2" src="<?php echo $product['rotator_image']; ?>" alt="<?php echo $product['name']; ?>" />
                    <?php endif; ?>
                    <img class="image1" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                  </span>
                </a>
                <div class="button-group">
                  <div class="addtocart-btn">
                    <?php if($config_slide['f_show_addtocart']) { ?>
                    <button class="btn-addtocart" type="button" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>');">
                      <span class="text-btn"><?php echo $button_cart; ?></span>
                      <span class="tooltip-content clearfix">
                        <em class="tooltips"><?php echo $tooltip_cart; ?></em>
                      </span>
                    </button>
                    <?php } ?>
                  </div>
                  <div class="addtolink-btn">
                    <button class="btn-wishlist" type="button" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
                      <span class="text-btn"><i class="fa fa-heart-o"></i></span>
                      <span class="tooltip-content clearfix">
                        <em class="tooltips"><?php echo $tooltip_wishlist; ?></em>
                      </span>
                    </button>
                    <button class="btn-compare" type="button" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');">
                      <span class="text-btn"><i class="fa fa-files-o"></i></span>
                      <span class="tooltip-content clearfix">
                        <em class="tooltips"><?php echo $tooltip_compare; ?></em>
                      </span>
                    </button>
                    <div class="btn-quick-view"></div>
                  </div>
                </div>
              </div>
              <?php if($config_slide['f_show_label']): ?>
                <?php if ($product['is_new']):
                  if($product['special']): ?>
                  <div class="label-pro-sale">
                  <?php if($product['special']){
                  $a = ($product['special2']);
                  $b = ($product['price2']);
                  $c = round(($a-$b)/$b,2)*100;
                  }?>
                  <?php if($product['special']){?>
                  <span class="sale-percent"><?php echo $c;?>%</span>
                  <?php }?>
                  </div>
                  <?php else: ?>
                  <div class="label-pro-new"><span><?php echo $text_new; ?></span></div>
                  <?php endif; ?>
                  <?php else: ?>
                  <?php if($product['special']): ?>
                  <div class="label-pro-sale">
                  <?php if($product['special']){
                  $a = ($product['special2']);
                  $b = ($product['price2']);
                  $c = round(($a-$b)/$b,2)*100;
                  }?>
                  <?php if($product['special']){?>
                  <span class="sale-percent"><?php echo $c;?>%</span>
                  <?php }?>
                  </div>
                  <?php endif; ?>
                  <?php endif; ?>
              <?php endif; ?>
            <div class="des-container">
              <div class="name">
                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
              </div>
              <?php if($config_slide['f_show_des']) { ?>
                <?php } ?>

              <?php if (isset($product['rating'])) { ?>
              <div class="rating">
                <span>
                  <img src="catalog/view/theme/tt_alice3/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" />
                </span>
              </div>
              <?php } ?>
              
              <?php if($config_slide['f_show_price']) { ?>
              <?php if ($product['price']) { ?>
              <div class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <span class="price-new"><?php echo $product['special']; ?></span>
                <span class="price-old"><?php echo $product['price']; ?></span>
                <?php } ?>
              </div>
              <?php } ?>
              <?php } ?>
            </div>
          </div> <!-- item-inner -->
        </div> <!-- item -->
                        <?php if($count % $rows == 0 || $count == count($productTab['productInfo'])) { echo '</div>'; } ?>
                    <?php } ?>
                <?php else: ?>
                    <p><?php echo $productTab['text_empty']; ?></p>
                <?php endif; ?>
				</div>
				</div><!-- .row -->
			</div>

		<?php } ?>
	
	</div>
	</div><!-- .tab_container -->
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
		itemsDesktop : [1199,4], 
		itemsDesktopSmall : [980,3], 
		itemsTablet: [768,2], 
		itemsMobile : [479,1]
  });
 
});
</script>




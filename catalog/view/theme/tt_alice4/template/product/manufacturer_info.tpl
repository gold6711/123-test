<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($products) { ?>
                <div><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div>
                <div class="toolbar">
					<div class="col-md-2 toolbar3">
                        <div class="btn-group">
                            <button type="button" id="list-view" class="btn btn-default"><i class="fa fa-th-list"></i></button>
                            <button type="button" id="grid-view" class="btn btn-default"><i class="fa fa-th"></i></button>
                        </div>
                    </div>
                    <div class="col-md-2 toolbar1 pull-right">
                        <select id="input-sort" class="form-control" onchange="location = this.value;">
                            <?php foreach ($sorts as $sorts) { ?>
                            <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                            <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $text_sort; ?>&nbsp;<?php echo $sorts['text']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $sorts['href']; ?>"><?php echo $text_sort; ?>&nbsp;<?php echo $sorts['text']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2 toolbar2 pull-right">
                        <select id="input-limit" class="form-control" onchange="location = this.value;">
                            <?php foreach ($limits as $limits) { ?>
                            <?php if ($limits['value'] == $limit) { ?>
                            <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $text_limit; ?>&nbsp;<?php echo $limits['text']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $limits['href']; ?>"><?php echo $text_limit; ?>&nbsp;<?php echo $limits['text']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="custom-products">
                    <div>
                    <?php foreach ($products as $product) { ?>
                        <div class="product-layout product-list col-xs-12">
                          <div class="product-thumb layout1">
							<div class="image">
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
                              <a href="<?php echo $product['href']; ?>">
                              <?php if ($product['thumb']) { ?>
                                
                                <?php if($product['rotator_image']){ ?><img class="img-r lazy" src="<?php echo $product['rotator_image']; ?>" alt="<?php echo $product['name']; ?>" /><?php } ?>
                              <?php } else { ?>
                                <img  src="image/cache/no_image-100x100.png" alt="<?php echo $product['name']; ?>" />
                              <?php } ?>
							  <img  src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive lazy" />
                              </a>
							  <div class="actions-link">									
									<a class="btn-wishlist" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-favorite-outline"></i></a>									
									<a class="btn-compare" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-refresh-alt"></i></a>
								</div>
                            </div>
                            <div class="product-inner">
                              <div class="product-caption">
							
                              <h2 class="product-name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h2>
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
							  <?php if ($product['price']) { ?>
                                <p class="price">
                                  <?php if (!$product['special']) { ?>
                                  <?php echo $product['price']; ?>
                                  <?php } else { ?>
									<span class="price-new"><?php echo $product['special']; ?></span>
								  <span class="price-old"><?php echo $product['price']; ?></span>
								  
								  <span class="per-sale"><?php echo round(($product['special_num']-$product['price_num'])/$product['price_num']*100)."%"; ?></span>
                                  <?php } ?>
                                  <?php if ($product['tax']) { ?>
                                  <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                  <?php } ?>
                                </p>
                                <?php } ?>
							 
                              
								<p class="product-des"><?php echo $product['description']; ?></p>
                              <div class="product-intro">
								<div class="actions-link2">
									<a class="btn-cart" data-toggle="tooltip" title="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>');"><span class="button"><i class="fa fa-shopping-cart"></i><?php echo $button_cart; ?></span></a>
									<a class="btn-wishlist" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-favorite-outline"></i></a>									
									<a class="btn-compare" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="zmdi zmdi-refresh-alt"></i></a>
								</div>
								</div>
                            </div>
							</div>
                          </div>
                        </div>
                    <?php } ?>
                </div>
                </div>
                <div class="row toolbar4">
                    <div class="col-sm-6"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
                <?php } ?>
                <?php if (!$products) { ?>
                <p><?php echo $text_empty; ?></p>
                <div class="buttons">
                    <div class="pull-right"><a href="<?php echo $continue; ?>" class="button btn btn-primary"><?php echo $button_continue; ?></a></div>
                </div>
                <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
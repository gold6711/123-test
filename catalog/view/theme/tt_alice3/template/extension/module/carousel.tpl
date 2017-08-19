<div class="carousel-module">
<div class="carousel-title module-title">
    <h2 class="title title-group"><?php echo $title; ?></h2>
  </div>
<?php
    $count = 0;
    $rows = 4;
    if(!$rows) { $rows = 1;}
  ?>

  <?php if ($banners) { ?>
  <div id="carousel<?php echo $module; ?>" class="owl-carousel brand-slider">
    <?php foreach ($banners as $banner) { ?>
      <?php  if($count % $rows == 0 ) { echo '<div class="row_items">'; } $count++; ?>
      <div class="item text-center">
        <?php if ($banner['link']) { ?>
        <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
        <?php } else { ?>
        <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
        <?php } ?>
      </div>
      <?php if($count % $rows == 0 || $count == count($banners)): ?>
            </div>
      <?php endif; ?>
    <?php } ?>
  <?php } ?>
</div>
</div>
<script type="text/javascript"><!--
$('#carousel<?php echo $module; ?>').owlCarousel({
  items: 2,
  autoPlay: false,
  navigation: true,
  navigationText : ['<i class="zmdi zmdi-chevron-left"></i>','<i class="zmdi zmdi-chevron-right"></i>'],
  pagination: false,
  itemsDesktop : [1199,2],
  itemsDesktopSmall : [991,3],
  itemsTablet: [768,3],
  itemsMobile : [320,2],
});
--></script>
<footer>
	<div class="top-footer">
	<div class="container">
    <div class="row">
		<?php if(isset($block3)){ ?>
		<?php echo $block3; ?>
	<?php } ?>
	<div class="position-4-socialfooter col-lg-3"></div>
	</div>
	</div>
	</div>
  <div class="container">
    <div class="row">
		<div class="col-sm-3 col-footer">
        <div class="title-footer"><?php echo $text_contact; ?></div>
        <ul class="list-unstyled text-content">
          <li><span><?php echo $text_address; ?></span><?php echo $address; ?></li>
          <li><span><?php echo $text_phone; ?></span><?php echo $telephone; ?></li>		  
          <li><span><?php echo $text_mail; ?></span><?php echo $email; ?></li>
        </ul>
      </div>
      <?php if ($informations) { ?>
      <div class="col-sm-3 col-footer">
        <div class="title-footer"><?php echo $text_information; ?></div>
        <ul class="list-unstyled text-content">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3 col-footer">
        <div class="title-footer"><?php echo $text_account; ?></div>
        <ul class="list-unstyled text-content">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3 col-footer">
        <div class="title-footer"><?php echo $text_extra; ?></div>
        <ul class="list-unstyled text-content">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
	  
    </div>
  </div>
    <div class="bottom-footer">
	<div class="container">
    <div class="text-powered">
		<p><?php echo $powered; ?></p>
		<div class="payment-method"><a href="#"><img src="image/catalog/demo/icon/payment.png" alt="image payment"></a></div>
	</div>
  </div>
  </div>   
  <div id="back-top"><i class="fa fa-angle-up"></i></div>
<script type="text/javascript">
$(document).ready(function(){
	// hide #back-top first
	$("#back-top").hide();
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 300) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});
		// scroll body to 0px on click
		$('#back-top').click(function () {
			$('body,html').animate({scrollTop: 0}, 800);
			return false;
		});
	});
});
</script>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->

</body></html>
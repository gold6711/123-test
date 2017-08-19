<div class="col-md-3">
<div class="vermagemenu visible-lg visible-md">
    <div class="content-vermagemenu"> 
        <h2 class="collapse collapse-btn">
            <span class="collapse-box">
                <i class="fa fa-align-justify"></i>
            </span>
            <span class="text-collapse-btn"><?php echo $heading_title;?></span>
        </h2>
        <div class="navleft-container">
            <div id="pt_vmegamenu" class="pt_vmegamenu">
                <?php echo $vermegamenu ?> 
            </div>	
        </div>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
var CUSTOMMENU_POPUP_EFFECT = <?php echo $effect; ?>;
var CUSTOMMENU_POPUP_TOP_OFFSET = <?php echo $top_offset ; ?>
//]]>
        $('.vermagemenu h2').click(function () {
            $( ".navleft-container" ).slideToggle("slow").toggleClass('is-active');
        });
</script>
</div>
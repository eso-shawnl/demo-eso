<div class="box bestsellers">
    <div class="box-event">
        <div class="box-product">
            <div class="padding">
                <div class="inner-white no-padding">
                    <a href="javascript:showhide(&quot;organizer&quot;)"><h3 style="text-align:center; padding: 1em;"><?php echo $heading_title; ?></h3>
                            <div id="organizer">
                    <?php foreach ($event_manufacturers as $event_manufacturer) {  ?>
                    <div style="min-height: 60px; text-align: left; margin-bottom:10px; padding-right:5px; cursor: pointer" class="name maxheight-best">
                        <div class="row" style="padding:10px;">
                            <div class="col-xm-6 col-xs-8">
                                <h4 ><?php echo $event_manufacturer['description'] ; ?></h4>
                            </div>
                            <div class="col-xm-6 col-xs-4" style="text-align:center">
                                <a href="<?php echo $event_manufacturer['website'] ; ?>" target="_blank">
                                    <img alt="" src="<?php echo $event_manufacturer['logo'] ; ?>" class="img-responsive" style="text-align:center;">
                                </a>
                            </div>

                        </div>
                    </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

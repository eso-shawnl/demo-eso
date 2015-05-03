<div class="box bestsellers">
    <div class="box-event">
        <div class="box-product">
            <div class="padding">
                <div class="inner-white">
                    <?php foreach ($event_manufacturers as $event_manufacturer) {  ?>
                    <div style="min-height: 100px text-align: left margin-bottom:10px padding-right:5px" class="name maxheight-best">
                        <div style="text-align:center">
                            <a href="<?php echo $event_manufacturer['website'] ; ?>" target="_blank">
                                <img alt="" src="<?php echo $event_manufacturer['logo'] ; ?>"></a>
                        </div>
                        <h4><?php echo $event_manufacturer['description'] ; ?></h4>
                        <br>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

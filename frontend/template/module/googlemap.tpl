<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=<?php echo $language_code ;?>"></script>
<div class="box bestsellers">
    <div class="box-event">
        <div class="box-product">
            <div class="padding">
                <div class="inner-white no-padding">
                    <div class="name maxheight-best">
                        <div style="text-align:center">
                            <a href="javascript:showhide(&quot;time_address&quot;)">
                                <h3 style="text-align:center; padding: 1em;"><?php echo $heading_title; ?></h3>
                            </a>
                            <div id="time_address">

                            <div id="map_canvas" class="col-sm-12">

                            </div>
                            <div id="map_detail" class="col-sm-12 text-left">

                                <p>
                                    <?php echo $text_address ?>
                                </p>
                                <span >
                                    <?php echo $text_date_available.' '.$start_date.' '.$start_time.'-'.$end_date.' '.$end_time ?>
                                </span>
                            </div>
                            <div >
                                <span style="opacity: 0;">
                                    <?php echo $text_date_available.' '.$start_date.' '.$start_time.'-'.$end_date.' '.$end_time ?>
                                </span>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript"><!--
        var map;

        var mylatlog = new google.maps.LatLng(<?php echo $latlng; ?>);

        $('#map_canvas').ready(function() {

            var z=<?php echo $zoom; ?>;

            var name = "";

            var mapOptions = {
                zoom: z,               //0-19
                center: mylatlog,
                mapTypeId: google.maps.MapTypeId.ROADMAP,   //ROADMAP- SATELLITE- HYBRID- TERRAIN-
                scaleControl: true,    //
                mapTypeControl: true,
                mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
            };
            map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

            var marker = new google.maps.Marker({
                position: mylatlog,
                draggable:true,
                animation: google.maps.Animation.DROP,
                //title:name,
                map:map
            });

            google.maps.event.addListener(marker, 'click', toggleBounce);

            // To add the marker to the map, call setMap();
            marker.setMap(map);

            function toggleBounce() {

                if (marker.getAnimation() != null) {
                    marker.setAnimation(null);
                } else {
                    marker.setAnimation(google.maps.Animation.BOUNCE);
                }
            }

        });

        var latlng = '<?php echo $latlng; ?>';
        var url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" + latlng + "&language=<?php echo $language_code ;?>&sensor=false";

        $.getJSON(url, function (data) {

            var table_body ="";
            var street_number = data.results[0]['address_components'][0]['short_name'];
            var route = data.results[0]['address_components'][1]['short_name'];
            var sublocality_level_1 = data.results[0]['address_components'][2]['short_name'];
            var locality = data.results[0]['address_components'][3]['short_name'];
            var administrative_area_level_1 = data.results[0]['address_components'][4]['short_name'];
            var country = data.results[0]['address_components'][5]['short_name'];

            table_body +=  street_number+' '
                                    + route+', '
                                    + sublocality_level_1+', '
                                    + locality+', '
                                    + administrative_area_level_1+', '
                                    + country ;

            $("#map_detail p").append(table_body);
        });


        //--></script>

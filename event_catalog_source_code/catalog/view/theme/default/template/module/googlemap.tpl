<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=<?php echo $language_code ;?>"></script>
<div class="box bestsellers">
    <div class="box-event">
        <div class="box-product">
            <div class="padding">
                <div class="inner-white">
                    <div style="min-height: 100px text-align: left margin-bottom:10px padding-right:5px" class="name maxheight-best">
                        <div style="text-align:center">
                            <h3><?php echo $heading_title; ?></h3>

                            <div id="map_canvas" style="width: 250px; height: 300px">

                            </div>
                            <div id="map_detail" style="width: 250px;">

                            </div>
                            <div >
                                <span ><p><strong><?php echo $date_available.' '.$date ?></strong></p></span>
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

            var name = "Hello World!";

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
                title:name,
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

        var latlng = "<?php echo $latlng; ?>";
        var url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" + latlng + "&language=<?php echo $language_code ;?>&sensor=false";

        $.getJSON(url, function (data) {
            /*
             for(var i=0;i<data.results.length;i++) {
             var address = data.results[i].formatted_address;
             }
             */
            var table_body ="";

            table_body += "<tr><td>" + data.results[0].formatted_address + "</td></tr>";

            $("#map_detail").html(table_body);
        });


        //--></script>

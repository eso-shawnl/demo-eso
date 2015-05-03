<?php echo $header; ?><?php echo $column_left; ?>
<div id="map-canvas" style="position:absolute;left:20%;width: 80%; height: 80%"></div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&language=<?php echo $language_code ;?>"></script>
<script type="text/javascript"><!--

    var map = new google.maps.Map(document.getElementById('map-canvas'), {
        zoom: 12,
        center: new google.maps.LatLng(-36.845075,174.7508926),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    $.ajax({
        url: 'index.php?route=dashboard/googlemap/autocomplete&token=<?php echo $token; ?>',
        dataType: 'json',
        success: function(json) {
            jQuery.each(json, function(locations, item) {

                for (i = 0; i < item.length; i++) {
                    var lat = item[i]['lat'];
                    var lng = item[i]['lng'];
                    var ip = item[i]['ip'];

                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(lat,lng),
                        map: map,
                        animation: google.maps.Animation.DROP
                    });

                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(item[i]['num']);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
            });

        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });


//--></script>



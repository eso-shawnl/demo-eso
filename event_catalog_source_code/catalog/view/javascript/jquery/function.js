/**
 * Created by steven on 3/24/15.
 */

var timerID = null;
var timerRunning = false;

function stopclock (){
    if(timerRunning)
        clearTimeout(timerID);
    timerRunning = false;
}

function startclock () {
    stopclock();
    showtime();
}

function showtime () {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var timeValue = now.getYear()+1900+"/"+(now.getMonth()+1)+"/"+now.getDate()+" " +((hours >= 12) ? " PM " : " AM " );
    timeValue += ((hours >12) ? hours -12 :hours);
    timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
    timeValue += ((seconds < 10) ? ":0" : ":") + seconds;
    <!--$('#time').html(now); -->
    $('#time').html(timeValue);
    timerID = setTimeout("showtime()",1000);
    timerRunning = true;
}

$().ready(function(){
    startclock();
});


$.getJSON("http://ip-api.com/json/?callback=?", function (data) {
    /*
    var table_body = "";

    if (data.countryCode != '')
        table_body += data.countryCode;

    if (data.city != '')
        table_body += "--" + data.city;

    if (data.query != '')
        table_body += "--" + data.query;


     $.each(data, function(k, v) {
     table_body += "<tr><td>" + k + "</td><td><b>" + v + "</b></td></tr>";
     });

    $("#geo_info").html(table_body);
     */
    //
    var json = 'userinfo=' + JSON.stringify(data);
    $.ajax({
        type: "POST",
        url: 'index.php?route=common/ip/autocomplete',
        data: json,
        success: function (data) {
            console.log(data);
        }
    });
})

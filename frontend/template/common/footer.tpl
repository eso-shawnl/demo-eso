<footer>
  <div class="container">
    <div class="row">
      <!--
    <?php if ($informations) { ?>
    <div class="col-sm-3">
      <h5><?php echo $text_information; ?></h5>
      <ul class="list-unstyled">
        <?php foreach ($informations as $information) { ?>
        <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>

    <div class="col-sm-3">
      <h5><?php echo $text_service; ?></h5>
      <ul class="list-unstyled">
        <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
        <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
        <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
      </ul>
    </div>
    <div class="col-sm-3">
      <h5><?php echo $text_extra; ?></h5>
      <ul class="list-unstyled">
        <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
        <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
        <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
        <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
      </ul>
    </div>
    <div class="col-sm-3">
      <h5><?php echo $text_account; ?></h5>
      <ul class="list-unstyled">
        <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
        <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
        <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
      </ul>
    </div>
      -->
    </div>
    <hr>
    <p><?php echo $powered; ?></p>
  </div>
</footer>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->
<script type="text/javascript">
  (function () {
    function checkTime(i) {
      return (i < 10) ? "0" + i : i;
    }

    function startTime() {

      var today = new Date(),
              y = checkTime(today.getFullYear()),
              m = checkTime(today.getMonth()+1),
              d = checkTime(today.getDate()),
              h = checkTime(today.getHours()),
              i = checkTime(today.getMinutes()),
              s = checkTime(today.getSeconds()),
              time = y + '-' + m + '-' + d + ' ' + h + ":" + i + ":" + s;
      document.getElementById('current-time').innerHTML = time;
      t = setTimeout(function () {
        startTime()
      }, 500);
    }
    startTime();
  })();
</script>

<!-- Robin 2015-05-06 show or hide div object in page -->
<script type="text/javascript">
  function showhide(objid)   {
   // alert(objid);
   var div = document.getElementById(objid);
    if (div.style.display !== "none") {
        div.style.display = "none";
    }
    else {
        div.style.display = "block";
    }
  }
</script>
<!---- Robin 2015-05-06 show or hide div object in page -->

</body></html>
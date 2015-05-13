<?php echo $header; ?>
<?php var_dump($tickets[2]['zone']); ?>
<?php var_dump($tickets[2]['zone1']); ?>
<div class="container">
        <!--
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
        -->
  <div class="row">
    <div id="content" class="col-sm-12">
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="ticket-form" class="form-horizontal"  data-toggle="validator" role="form" >
          <!-- Start Tickets table -->
        <div class="table-responsive">
          <table class="table table-striped" id="choose-table">
            <thead>
              <tr>
                <th class="text-center col-xs-2"><?php echo $column_name; ?></th>
                <th class="text-center col-xs-2"><?php echo $column_price; ?></th>
                <th class="text-center col-xs-4"><?php echo $column_zone; ?></th>
                <th class="text-center col-xs-2"><?php echo $column_quantity; ?></th>
                <th class="text-center col-xs-2"><?php echo $column_subtotal; ?></th>
              </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $row => $ticket) { ?>
                <tr id="<?php echo $row ?>">
                    <td class="text-center col-xs-2 input-name"><?php echo $ticket['name'] ?></td>
                    <td class="text-center col-xs-2 input-price">$<?php echo $ticket['price'] ?></td>
                    <td class="text-center col-xs-4">
                        <div>
                            <select name="<?php echo $row ?>[zone]" class="form-control zone-input tickets-input" >
                                <option value="0">Choose Zone</option>
                                <?php foreach ($ticket['zone'] as $k=>$v) { ?>
                                <option <?php if($k==$ticket['zone1']){echo 'selected';} ?> value="<?php echo $k; ?>" remain="<?php echo $v; ?>"><?php echo $k; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td class="text-center col-xs-2">
                        <div>
                            <input disabled type="number" name="<?php echo $row ?>[quantity]" class='form-control quantity-input tickets-input' min="0" value="<?php echo $tickets[$row]['quantity']; ?>" step="1" >
                            <input type="hidden" name="<?php echo $row ?>[name]" value="<?php echo $ticket['name'] ?>">
                            <input type="hidden" name="<?php echo $row ?>[price]" value="<?php echo $ticket['price'] ?>">
                            <input type="hidden" name="<?php echo $row ?>[subtotal]" value="0" class="subtotal-post">
                        </div>
                    </td>
                    <td class="text-center col-xs-2 input-subtotal">
                        0.00
                    </td>
                </tr>
                <?php } ?>
            </tbody>
          </table>
          <table class="table-total table-striped col-sm-4 col-sm-offset-8">
              <td>Total (incl. GST)</td>
              <td id="total">$0.00</td>
              <input type="hidden" value="" name="total">
            </tr>
          </table>
            <br>
            <br>
            <div class="buttons">
                <input class="pull-right btn btn-primary" type="button" value="<?php echo $button_nextstep; ?>" id="ticket-nextstep">
            </div>
        </div>
    <!-- End tickets table -->
        <div id="order-info">
          <div id="personal_info">
          <fieldset>
              <legend><?php echo $text_personal_info; ?></legend>
              <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                    <div class="col-sm-6">
                        <input type="text" name="customer[firstname]" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" required />
                        <?php if ($error_firstname) { ?>
                        <div class="text-danger"><?php echo $error_firstname; ?></div>
                        <?php } ?>
                    </div>
                    <span class="col-sm-4"><?php echo $info_firstname; ?></span>
                </div>
              <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                    <div class="col-sm-6">
                        <input type="text" name="customer[lastname]" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" required />
                        <?php if ($error_lastname) { ?>
                        <div class="text-danger"><?php echo $error_lastname; ?></div>
                        <?php } ?>
                    </div>
                    <span class="col-sm-4"><?php echo $info_lastname; ?></span>
                </div>
              <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-agegroup"><?php echo $entry_agegroup; ?></label>
                    <div class="col-sm-6">
                        <select name="customer[agegroup]" class="form-control" id="input-agegroup">
                            <option value="0" >Choose one</option>
                            <?php if (!empty($age_group['age_group'])) { ?>
                            <?php foreach ($age_group['age_group'] as $key => $value) { ?>
                            <option value="<?php echo $key; ?>" <?php if ($agegroup_1==$key) {echo 'selected';} ?>  >
                                <?php echo $value; ?>
                            </option>

                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <span class="col-sm-4"><?php echo $info_agegroup; ?></span>
                </div>
          </fieldset>
          <fieldset>
            <legend><?php echo $text_your_email; ?></legend>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                <div class="col-sm-6">
                    <input type="email" name="customer[email]" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" required />
                    <?php if ($error_email) { ?>
                    <div class="text-danger"><?php echo $error_email; ?></div>
                    <?php } ?>
                </div>
                <span class="col-sm-4"><?php echo $info_email; ?></span>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_confirm_email; ?></label>
                <div class="col-sm-6">
                    <input type="email" name="" value="<?php echo $email; ?>" placeholder="<?php echo $entry_confirm_email; ?>" id="input-email" class="form-control" required />
                    <?php if ($error_email) { ?>
                    <div class="text-danger"><?php echo $error_email; ?></div>
                    <?php } ?>
                </div>
                <span class="col-sm-4"><?php echo $info_comfirm_email; ?></span>
            </div>
        </fieldset>
          <fieldset>
            <legend><?php echo $text_your_telephone; ?></legend>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-phone"><?php echo $entry_telephone; ?></label>
                <div class="col-sm-6">
                    <input type="phone" name="customer[phone]" value="<?php if(isset($phone)) {echo $phone;} ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-phone" class="form-control" />
                </div>
                <span class="col-sm-4"><?php echo $info_telephone; ?></span>
            </div>
        </fieldset>
        </div>

          <fieldset>
            <legend><?php echo $text_your_otherinfo; ?></legend>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="promotion_code"><?php echo $entry_promotion_code; ?></label>
                <div class="col-sm-6">
                    <input type="text" name="customer[promotion_code]" value="<?php echo $promotion_code; ?>" placeholder="<?php echo $entry_promotion_code; ?>" id="promotion_code"  class="form-control" />
                </div>
                <span class="col-sm-4"><?php echo $info_promotion_code; ?></span>
            </div>
        </fieldset>

          <!-- Come from checkout page -->
          <div class="col-xs-12" id="shipping-methods">
              <h2 class="required"><?php echo $text_shipping_method; ?></h2>
              <div class="radio">
                  <label for="shipping-pickup">
                      <input type="radio" name="customer[shipping_method]" id="shipping-pickup" value="pickup" checked>
                      <?php echo $text_pickup ?>
                  </label>
              </div>
              <div class="radio">
                  <label for="shipping-mail">
                      <input type="radio" name="customer[shipping_method]" id="shipping-mail" value="mail">
                      <?php echo $text_post ?>
                  </label>
              </div>
          </div>
          <div class="col-xs-12" id="shipping-details">
              <h2><?php echo $text_shipping_detail; ?></h2>
              <div id="shipping-detail-pickup">
                  <?php foreach($stores as $key=>$store) { ?>
                  <div class="radio">
                      <label for="pickup-store-<?php echo $store['ticket_office_id'] ?>">
                          <input type="radio" name="customer[shipping_detail]" id="pickup-store-<?php echo $store['ticket_office_id'] ?>" value="<?php echo $store['ticket_office_id'] ?>" >
                          <?php echo $store['name'], $store['phone'], $store['address'] ?>
                      </label>
                  </div>
                  <?php } ?>
              </div>
              <div id="shipping-detail-mail" class="col-sm-12">
                  <!-- Start Address Fields -->
                  <fieldset>
                      <legend><?php echo $text_your_address; ?></legend>
                      <div class="form-group required">
                          <label class="col-sm-2 control-label" for="autocomplete"><?php echo $entry_full_address; ?></label>
                          <div class="col-sm-6" >
                              <input type="text" name="customer[full_address]" value="<?php echo $full_address; ?>" placeholder="<?php echo $entry_full_address; ?>" onFocus="geolocate()" id="autocomplete"  class="form-control" />
                          </div>
                          <label class="col-sm-4" for="autocomplete"><?php echo $info_address; ?></label>
                      </div>
                      <input type="hidden" name="customer[street_number]" value="<?php echo $street_number; ?>"  readonly="true" placeholder="<?php echo $entry_street_number; ?>" id="street_number"  class="form-control" />
                      <input type="hidden" name="customer[route]" value="<?php echo $route; ?>"  readonly="true" placeholder="<?php echo $entry_route; ?>" id="route"  class="form-control" />
                      <input type="hidden" name="customer[city]" value="<?php echo $city; ?>"  readonly="true" placeholder="<?php echo $entry_city; ?>" id="locality"  class="form-control" />
                      <input type="hidden" name="customer[suburb]" value="<?php echo $suburb; ?>"  readonly="true" placeholder="<?php echo $entry_suburb; ?>" id="sublocality_level_1"  class="form-control" />
                      <input type="hidden" name="customer[zone]" value="<?php echo $zone; ?>"  readonly="true" placeholder="<?php echo $entry_zone; ?>" id="administrative_area_level_1"  class="form-control" />
                      <input type="hidden" name="customer[country]" value="<?php echo $country; ?>" readonly="true"  placeholder="<?php echo $entry_country; ?>" id="country"  class="form-control" />
                      <input type="hidden" name="customer[postcode]" value="<?php echo $postcode; ?>"  readonly="true" placeholder="<?php echo $entry_postcode; ?>" id="postal_code"  class="form-control" />
                  </fieldset>
                  <!-- End address Fields -->
              </div>
          </div>
          <div class="col-xs-12" id="payment-methods">
              <h2><?php echo $text_payment_method; ?></h2>
              <div class="radio">
                  <label for="payment-online">
                      <input type="radio" name="customer[payment_method]" id="payment-online" value="online" checked>
                      <?php echo $text_online_banking; ?>

                      <?php echo $text_bank_account; ?>

                  </label>
              </div>
              <div class="radio">
                  <label for="payment-offline">
                      <input type="radio" name="customer[payment_method]" id="payment-offline" value="offline">
                      <?php echo $text_onsite; ?>
                  </label>
              </div>
          </div>
          <!-- End from checkout page -->

          <div class="buttons form-group">
              <input type="submit" class="pull-right btn btn-primary" value="<?php echo $button_checkout; ?>" id="submit">
          </div>
        </div>
      </form>
    <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript" src="./js/checkout.js"></script>
<script type="text/javascript" src="./js/purchase.js"></script>
<script type="text/javascript" src="./js/validator.min.js"></script>



<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
<script type="text/javascript">
    // This example displays an address form, using the autocomplete feature
    // of the Google Places API to help users fill in the information.

    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        sublocality_level_1: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };

    function initialize() {
        // Create the autocomplete object, restricting the search
        // to geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
                /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
                { types: ['geocode'] });
        // When the user selects an address from the dropdown,
        // populate the address fields in the form.
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            fillInAddress();
        });
    }

    // [START region_fillform]
    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
            var found = false;
            document.getElementById(component).value = '';

            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];

                if (component ==addressType) {
                    var val = place.address_components[i][componentForm[addressType]];

                    document.getElementById(addressType).value = val;
                    found = true;
                    break;
                }

            }

            if (found) {
                document.getElementById(component).readOnly = true;
            }
            else {
                document.getElementById(component).readOnly = false;
            }
        }

    }
    // [END region_fillform]

    // [START region_geolocation]
    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = new google.maps.LatLng(
                        position.coords.latitude, position.coords.longitude);
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
    // [END region_geolocation]

</script>
<?php echo $footer; ?>

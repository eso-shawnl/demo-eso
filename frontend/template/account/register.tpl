<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <p><?php echo $text_account_already; ?></p>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset id="account">
          <legend><?php echo $text_your_details; ?></legend>
          <div class="form-group required" style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
            <label class="col-sm-2 control-label"><?php echo $entry_customer_group; ?></label>
            <div class="col-sm-10">
              <?php foreach ($customer_groups as $customer_group) { ?>
              <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
              <div class="radio">
                <label>
                  <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                  <?php echo $customer_group['name']; ?></label>
              </div>
              <?php } else { ?>
              <div class="radio">
                <label>
                  <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" />
                  <?php echo $customer_group['name']; ?></label>
              </div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="col-sm-4">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
              <?php if ($error_firstname) { ?>
              <div class="text-danger"><?php echo $error_firstname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-4">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
              <?php if ($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
            </div>
          </div>

            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-agegroup"><?php echo $entry_agegroup; ?></label>
                <div class="col-sm-4">
                <select name="agegroup" class="form-control">
                    <?php if (!empty($agegroup)) { ?>
                    <?php foreach ($agegroup as $value) { ?>
                    <option value="<?php echo $value['key']; ?>" >
                        <?php echo $value['value']; ?>
                    </option>

                    <?php } ?>
                    <?php } ?>
                </select>
                    </div>
            </div>
        </fieldset>
          <fieldset>
              <legend><?php echo $text_your_email; ?></legend>

              <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                  <div class="col-sm-4">
                      <input type="email" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                      <?php if ($error_email) { ?>
                      <div class="text-danger"><?php echo $error_email; ?></div>
                      <?php } ?>
                  </div>
              </div>

          </fieldset>
          <fieldset>
              <legend><?php echo $text_your_telephone; ?></legend>

              <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-phone"><?php echo $entry_telephone; ?></label>
                  <div class="col-sm-4">
                      <input type="phone" name="phone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-phone" class="form-control" />
                      <?php if ($error_telephone) { ?>
                      <div class="text-danger"><?php echo $error_telephone; ?></div>
                      <?php } ?>
                  </div>
              </div>

          </fieldset>
          <fieldset>
              <legend><?php echo $text_your_address; ?></legend>
              <div class="form-group">
                  <body onload="initialize()">
                  <label class="col-sm-2 control-label" for="autocomplete"><?php echo $entry_full_address; ?></label>
                  <div class="col-sm-10" >
                      <input type="text" name="full_address" value="<?php echo $full_address; ?>" placeholder="<?php echo $entry_full_address; ?>" onFocus="geolocate()" id="autocomplete"  class="form-control" />
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-sm-2 control-label" for="street_number"><?php echo $entry_street_number; ?></label>
                  <div class="col-sm-4" >
                      <input type="text" name="street_number" value="<?php echo $street_number; ?>"  readonly="true" placeholder="<?php echo $entry_street_number; ?>" id="street_number"  class="form-control" />
                      <?php if ($error_street_number) { ?>
                      <div class="text-danger"><?php echo $error_street_number; ?></div>
                      <?php } ?>
                  </div>

                  <label class="col-sm-2 control-label" for="route"><?php echo $entry_route; ?></label>
                  <div class="col-sm-4" >
                      <input type="text" name="route" value="<?php echo $route; ?>"  readonly="true" placeholder="<?php echo $entry_route; ?>" id="route"  class="form-control" />
                      <?php if ($error_route) { ?>
                      <div class="text-danger"><?php echo $error_route; ?></div>
                      <?php } ?>
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 control-label" for="locality"><?php echo $entry_city; ?></label>
                  <div class="col-sm-4">
                      <input type="text" name="city" value="<?php echo $city; ?>"  readonly="true" placeholder="<?php echo $entry_city; ?>" id="locality"  class="form-control" />
                      <?php if ($error_city) { ?>
                      <div class="text-danger"><?php echo $error_city; ?></div>
                      <?php } ?>
                  </div>

                  <label class="col-sm-2 control-label" for="locality"><?php echo $entry_suburb; ?></label>
                  <div class="col-sm-4">
                      <input type="text" name="suburb" value="<?php echo $suburb; ?>"  readonly="true" placeholder="<?php echo $entry_suburb; ?>" id="sublocality_level_1"  class="form-control" />
                      <?php if ($error_city) { ?>
                      <div class="text-danger"><?php echo $error_city; ?></div>
                      <?php } ?>
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-sm-2 control-label" for="administrative_area_level_1"><?php echo $entry_zone; ?></label>
                  <div class="col-sm-4">
                      <input type="text" name="zone" value="<?php echo $zone; ?>"  readonly="true" placeholder="<?php echo $entry_zone; ?>" id="administrative_area_level_1"  class="form-control" />
                      <?php if ($error_zone) { ?>
                      <div class="text-danger"><?php echo $error_zone; ?></div>
                      <?php } ?>
                  </div>
                  <label class="col-sm-2 control-label" for="country"><?php echo $entry_country; ?></label>
                  <div class="col-sm-4">
                      <input type="text" name="country" value="<?php echo $country; ?>" readonly="true"  placeholder="<?php echo $entry_country; ?>" id="country"  class="form-control" />
                      <?php if ($error_country) { ?>
                      <div class="text-danger"><?php echo $error_country; ?></div>
                      <?php } ?>
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-sm-2 control-label" for="postal_code"><?php echo $entry_postcode; ?></label>
                  <div class="col-sm-4">
                      <input type="text" name="postcode" value="<?php echo $postcode; ?>"  readonly="true" placeholder="<?php echo $entry_postcode; ?>" id="postal_code"  class="form-control" />
                      <?php if ($error_postcode) { ?>
                      <div class="text-danger"><?php echo $error_postcode; ?></div>
                      <?php } ?>
                  </div>
              </div>


          </fieldset>
          <fieldset>
              <legend><?php echo $text_your_otherinfo; ?></legend>
              <div class="form-group">
                  <label class="col-sm-2 control-label" for="promotion_code"><?php echo $entry_promotion_code; ?></label>
                  <div class="col-sm-4">
                      <input type="text" name="promotion_code" value="<?php echo $promotion_code; ?>" placeholder="<?php echo $entry_promotion_code; ?>" id="promotion_code"  class="form-control" />
                  </div>
                  <label class="col-sm-2 control-label" for="ticket_code"><?php echo $entry_ticket_code; ?></label>
                  <div class="col-sm-4">
                      <input type="text" name="ticket_code" value="<?php echo $ticket_code; ?>"  placeholder="<?php echo $entry_ticket_code; ?>" id="ticket_code"  class="form-control" />
                  </div>
              </div>
          </fieldset>
        <fieldset>
          <legend><?php echo $text_your_password; ?></legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-4">
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>

              <label class="col-sm-2 control-label" for="input-confirm-password"><?php echo $entry_confirm_password; ?></label>
              <div class="col-sm-4">
                  <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>" placeholder="<?php echo $entry_confirm_password; ?>" id="input-confirm-password" class="form-control" />
                  <?php if ($error_confirm_password) { ?>
                  <div class="text-danger"><?php echo $error_confirm_password; ?></div>
                  <?php } ?>
              </div>
          </div>

        </fieldset>

        <fieldset>
          <legend><?php echo $text_newsletter; ?></legend>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_newsletter; ?></label>
            <div class="col-sm-10">
              <?php if ($newsletter) { ?>
              <label class="radio-inline">
                <input type="radio" name="newsletter" value="1" checked="checked" />
                <?php echo $text_yes; ?></label>
              <label class="radio-inline">
                <input type="radio" name="newsletter" value="0" />
                <?php echo $text_no; ?></label>
              <?php } else { ?>
              <label class="radio-inline">
                <input type="radio" name="newsletter" value="1" />
                <?php echo $text_yes; ?></label>
              <label class="radio-inline">
                <input type="radio" name="newsletter" value="0" checked="checked" />
                <?php echo $text_no; ?></label>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <?php if ($text_agree) { ?>
        <div class="buttons">
          <div class="pull-right"><?php echo $text_agree; ?>
            <?php if ($agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?>
            &nbsp;
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
        <?php } else { ?>
        <div class="buttons">
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
        <?php } ?>

      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div> 
<script type="text/javascript"><!--
// Sort the custom fields
$('#account .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account .form-group').length) {
		$('#account .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('#account .form-group').length) {
		$('#account .form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('#account .form-group').length) {
		$('#account .form-group:first').before(this);
	}
});

$('#address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#address .form-group').length) {
		$('#address .form-group').eq($(this).attr('data-sort')).before(this);
	} 
	
	if ($(this).attr('data-sort') > $('#address .form-group').length) {
		$('#address .form-group:last').after(this);
	}
		
	if ($(this).attr('data-sort') < -$('#address .form-group').length) {
		$('#address .form-group:first').before(this);
	}
});


//--></script>

<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>



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

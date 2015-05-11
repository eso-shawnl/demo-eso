<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-event" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-event" class="form-horizontal">

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-manufacturer"><span data-toggle="tooltip" title="<?php echo $help_manufacturer; ?>"><?php echo $entry_manufacturer; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="manufacturer" value="" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer" class="form-control" />
                <div id="event-manufacturer" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($event_manufacturers as $event_manufacturer) { ?>
                    <div id="event-manufacturer<?php echo $event_manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $event_manufacturer['name']; ?>
                        <input type="hidden" name="event_manufacturer[]" value="<?php echo $event_manufacturer['manufacturer_id']; ?>" />
                    </div>
                    <?php } ?>
                </div>
            </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
              <div class="col-sm-10">
                  <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                  <div id="event-category" class="well well-sm" style="height: 150px; overflow: auto;">
                      <?php foreach ($event_categories as $event_category) { ?>
                      <div id="event-category<?php echo $event_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $event_category['name']; ?>
                          <input type="hidden" name="event_category[]" value="<?php echo $event_category['category_id']; ?>" />
                      </div>
                      <?php } ?>
                  </div>
              </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <div class="checkbox">
                  <label>
                    <?php if (in_array(0, $event_store)) { ?>
                    <input type="checkbox" name="event_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="event_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php foreach ($stores as $store) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($store['store_id'], $event_store)) { ?>
                    <input type="checkbox" name="event_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="event_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-related"><span data-toggle="tooltip" title="<?php echo $help_related; ?>"><?php echo $entry_related; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
              <div id="event-related" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($event_related as $event_related) { ?>
                <div id="event-related<?php echo $event_related['event_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $event_related['name']; ?>
                  <input type="hidden" name="event_related[]" value="<?php echo $event_related['event_id']; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({height: 300});
<?php } ?>
//--></script> 
  <script type="text/javascript"><!--
// Manufacturer
$('input[name=\'manufacturer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				json.unshift({
					manufacturer_id: 0,
					name: '<?php echo $text_none; ?>'
				});
				
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['manufacturer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
        $('input[name=\'manufacturer\']').val('');

        $('#event-manufacturer' + item['value']).remove();

        $('#event-manufacturer').append('<div id="event-manufacturer' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="event_manufacturer[]" value="' + item['value'] + '" /></div>');
	}	
});

      $('#event-manufacturer').delegate('.fa-minus-circle', 'click', function() {
          $(this).parent().remove();
      });

// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'category\']').val('');

		$('#event-category' + item['value']).remove();
		
		$('#event-category').append('<div id="event-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="event_category[]" value="' + item['value'] + '" /></div>');
	}
});

$('#event-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Related
$('input[name=\'related\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/event/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['event_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'related\']').val('');

		$('#event-related' + item['value']).remove();

		$('#event-related').append('<div id="event-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="event_related[]" value="' + item['value'] + '" /></div>');
	}
});

$('#event-related').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
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
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>




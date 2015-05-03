<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">

                    <input type="hidden" name="id" value="<?php echo $event_id; ?>"/>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                        <div class="col-sm-10">
                            <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                            <input type="hidden" name="image" value="<?php echo $event_info['image']; ?>" id="input-image" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-event-name"><span data-toggle="tooltip" title="<?php echo $help_name; ?>"><?php echo $entry_name; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="event_name" value="<?php echo isset($event_info['name'])? $event_info['name'] :''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-event-name" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-layout"><?php echo $entry_layout; ?></label>
                        <div class="col-sm-10">
                            <select name="layout" class="form-control">
                                <option value=""></option>
                                <?php foreach ($layouts as $layout_id) { ?>
                                <?php if (isset($event_info['layout']) && $event_info['layout'] == $layout_id['layout_id']) { ?>
                                <option value="<?php $event_info['layout'] ; ?>" selected="selected"><?php echo $layout_id['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $layout_id['layout_id']; ?>"><?php echo $layout_id['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-location"><?php echo $entry_location; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="location" value="<?php echo isset($event_info['location'])? $event_info['location'] :''; ?>" placeholder="<?php echo $entry_location; ?>" id="input-location" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-publish-date"><?php echo $entry_publish_date; ?></label>
                        <div class="col-sm-3">
                            <div class="input-group date">
                                <input type="text" name="publish_date" value="<?php echo isset($event_info['publish_date'])? $event_info['publish_date'] :''; ?>" placeholder="<?php echo $entry_publish_date; ?>" data-date-format="YYYY-MM-DD hh:mm:ss" id="input-publish-date" class="form-control" />
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-start-date"><?php echo $entry_start_date; ?></label>
                        <div class="col-sm-3">
                            <div class="input-group date">
                                <input type="text" name="start_date" value="<?php echo isset($event_info['start_date'])? $event_info['start_date'] :''; ?>" placeholder="<?php echo $entry_start_date; ?>" data-date-format="YYYY-MM-DD hh:mm:ss" id="input-start-date" class="form-control" />
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-end-date"><?php echo $entry_end_date; ?></label>
                        <div class="col-sm-3">
                            <div class="input-group date">
                                <input type="text" name="end_date" value="<?php echo isset($event_info['end_date'])? $event_info['end_date'] :''; ?>" placeholder="<?php echo $entry_end_date; ?>" data-date-format="YYYY-MM-DD hh:mm:ss" id="input-end-date" class="form-control" />
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="<?php echo $help_minimum; ?>"><?php echo $entry_minimum; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="minimum" value="<?php echo isset($event_info['minimum'])? $event_info['minimum'] :'1'; ?>" placeholder="<?php echo $entry_minimum; ?>" id="input-minimum" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-maximum"><span data-toggle="tooltip" title="<?php echo $help_maximum; ?>"><?php echo $entry_maximum; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="maximum" value="<?php echo isset($event_info['maximum'])? $event_info['maximum'] :'1'; ?>" placeholder="<?php echo $entry_maximum; ?>" id="input-maximum" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="status" id="input-status" class="form-control">
                                <?php if ($event_info['status']) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="sort_order" value="<?php echo isset($event_info['sort_order'])? $event_info['sort_order'] :'1'; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
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
        $('input[name=\'manufacturer\']').val(item['label']);
        $('input[name=\'manufacturer_id\']').val(item['value']);
    }
});


//--></script>


<script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: true
    });

    $('.time').datetimepicker({
        pickDate: true
    });

    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });
    //--></script>
<script type="text/javascript"><!--

    $('#language a:first').tab('show');

//--></script>


<?php echo $footer; ?>

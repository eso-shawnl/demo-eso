<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-event" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
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

          <div class="tab-content">
            <input type="hidden" name="id" value="<?php echo $event_id; ?>"/>

            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab">
                        <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                        <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="event_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="event_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" class="input-description<?php echo $language['language_id']; ?>"><?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>

                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <textarea name="event_description[<?php echo $language['language_id']; ?>][meta_title]" placeholder="<?php echo $entry_meta_title; ?>" class="input-description<?php echo $language['language_id']; ?>"><?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['meta_title'] : ''; ?></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                        <textarea name="event_description[<?php echo $language['language_id']; ?>][meta_description]" placeholder="<?php echo $entry_meta_title; ?>" class="input-description<?php echo $language['language_id']; ?>" ><?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                        <textarea name="event_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-address<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_address; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="event_description[<?php echo $language['language_id']; ?>][address]" value="<?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['address'] : ''; ?>" placeholder="<?php echo $entry_address; ?>" id="input-address<?php echo $language['language_id']; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-city<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_city; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="event_description[<?php echo $language['language_id']; ?>][city]" value="<?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['city'] : ''; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city<?php echo $language['language_id']; ?>" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-country<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_country; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="event_description[<?php echo $language['language_id']; ?>][country]" value="<?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['country'] : ''; ?>" placeholder="<?php echo $entry_country; ?>" id="input-city<?php echo $language['language_id']; ?>" class="form-control" />
                        </div>
                    </div>
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
    $('.input-description<?php echo $language['language_id']; ?>').summernote({height: 200});

    <?php } ?>

//--></script>

<script type="text/javascript"><!--

    $('#language a:first').tab('show');

//--></script></div>

<?php echo $footer; ?>

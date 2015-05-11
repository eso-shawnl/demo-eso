<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
<div id="search_form" class="input-group" style="position: absolute; width:300px;left: 12%;height:10px;z-index:1">
  <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_search; ?>" class="form-control input-group" />
  <span class="input-group-btn">
      <button type="submit" class="btn btn-group-lg"><i class="fa fa-search"></i></button>
  </span>
</div>
</form>

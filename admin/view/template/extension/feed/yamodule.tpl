<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
		<div class="pull-right"> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
		<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
		</ul>
		</div>
	</div>
	<div class="container-fluid">
				<?php if($err_token) { ?>
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-circle"></i>
					<?php echo $err_token; ?>
					<button type="button" class="close" data-dismiss="alert">×</button>
				</div>
				<?php } ?>
					<div id="tab-p2p">
						<?php foreach ($p2p_status as $p) { echo $p; } ?>
					
								<?php if($mod_status) { ?>
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="p2p_form form-horizontal">
									<input type="hidden" value="p2p" name="type_data"/>
									<div class='row'>
										<div class='col-sm-12'>
											<p class="text-center"><?php echo $p2p_text_connect; ?></p>
											
												<div class="form-group">
													<label class="control-label col-sm-2">RedirectURL</label>
													<div class='col-sm-10'>
														<input class='form-control disabled' value='<?php echo $ya_p2p_linkapp; ?>' disabled>
													</div>
												</div>
										
						
												<div class="form-group">
													<label for="ya_p2p_number" class="col-sm-2 control-label"><?php echo $p2p_text_account; ?></label>
													<div class="col-sm-10">
														<input name="ya_p2p_number" value="<?php echo trim($ya_p2p_number); ?>" id="ya_p2p_number" class="form-control">
													</div>
												</div>
												<div class="form-group">
													<label for="ya_p2p_idapp" class="col-sm-2 control-label"><?php echo $p2p_text_appId; ?></label>
													<div class="col-sm-10">
														<input name="ya_p2p_idapp" value="<?php echo trim($ya_p2p_idapp); ?>" id="ya_p2p_idapp" class="form-control">
													</div>
												</div>
												<div class="form-group">
													<label for="ya_p2p_pw" class="col-sm-2 control-label"><?php echo $p2p_text_appWord; ?></label>
													<div class="col-sm-10">
														<input name="ya_p2p_pw" value="<?php echo trim($ya_p2p_pw); ?>" id="ya_p2p_pw" class="form-control">
													</div>
												</div>
												<div class="form-group hidden">
													<label for="ya_p2p_log" class="col-sm-2 control-label"><?php echo $p2p_text_debug; ?></label>
													<div class="col-sm-10">
														<label class='radio-inline'>
															<input type="radio" <?php echo (!$ya_p2p_log ? ' checked="checked"' : ''); ?> name="ya_p2p_log" value="0"> <?php echo $p2p_text_off; ?>
														</label>
														<label class='radio-inline'>
															<input type="radio" <?php echo ($ya_p2p_log ? ' checked="checked"' : ''); ?> name="ya_p2p_log" value="1"> <?php echo $p2p_text_on; ?>
														</label>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-2"><?php echo $p2p_text_status; ?></label>
													<div class='col-sm-10'>
														<select name="ya_p2p_os" id="ya_p2p_os" class="form-control">
															<?php foreach ($order_statuses as $order_status) { ?>
															<?php if ($order_status['order_status_id'] == $ya_p2p_os) { ?>
															<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
															<?php } ?>
															<?php } ?>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-2">Минимальная сумма</label>
													<div class='col-sm-10'>
														<input name="ya_p2p_minimum" value="<?php echo $ya_p2p_minimum; ?>" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-2">Порядок сортировки</label>
													<div class='col-sm-10'>
														<input name="ya_p2p_sort_order" value="<?php echo $ya_p2p_sort_order; ?>" class="form-control">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-2">Статус</label>
													<div class='col-sm-10'>
														<select name="ya_p2p_active" class="form-control">
															<option value="1" <?php echo ($ya_p2p_active ? ' selected="selected"' : ''); ?>>Включено</option>
															<option value="0" <?php echo ($ya_p2p_active ? '' : ' selected="selected"'); ?>>Отключено</option>
														</select>
													</div>
												</div>
										</div>
									</div>
								</form>
								<?php } else { ?>
								<div class="alert alert-danger">
									<i class="fa fa-exclamation-circle"></i>
									<?php echo $mod_off; ?>
									<button type="button" class="close" data-dismiss="alert">×</button>
								</div>
								<?php }?>
							
							<?php if($mod_status) { ?>
							<div class="clearfix text-center">
								<button type="button" onclick="$('.p2p_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-primary">
									<i class="process-icon-save"></i> <?php echo $p2p_sv; ?>
								</button>
							</div>
							<?php } ?>
					</div>
	</div>
</div>
<?php echo $footer; ?>


<style>
.bootstrap .tree-panel-heading-controls {
    line-height: 2.2em;
    font-size: 1.1em;
    color: #00aff0;
}

.bootstrap .tree-panel-heading-controls i {
    font-size: 14px;
}

.bootstrap .tree {
    list-style: none;
    padding: 0 0 0 20px;
}

.bootstrap .tree input {
    vertical-align: baseline;
    margin-right: 4px;
    line-height: normal;
}

.bootstrap .tree i {
    font-size: 14px;
}

.bootstrap .tree .tree-item-name,.bootstrap .tree .tree-folder-name {
    padding: 2px 5px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
}

.bootstrap .tree .tree-item-name label,.bootstrap .tree .tree-folder-name label {
    font-weight: 400;
}

.bootstrap .tree .tree-item-name:hover,.bootstrap .tree .tree-folder-name:hover {
    background-color: #eee;
    cursor: pointer;
}

.bootstrap .tree .tree-selected {
    color: #fff;
    background-color: #00aff0;
}

.bootstrap .tree .tree-selected:hover {
    background-color: #009cd6;
}

.bootstrap .tree .tree-selected i.tree-dot {
    background-color: #fff;
}

.bootstrap .panel-footer {
	height: 73px;
	border-color: #eee;
	background-color: #fcfdfe;
}

.bootstrap .tree i.tree-dot {
    display: inline-block;
    position: relative;
    width: 6px;
    height: 6px;
    margin: 0 4px;
    background-color: #ccc;
    -webkit-border-radius: 6px;
    border-radius: 6px;
}

.bootstrap .tree .tree-item-disable,.bootstrap .tree .tree-folder-name-disable {
    color: #ccc;
}

.bootstrap .tree .tree-item-disable:hover,.bootstrap .tree .tree-folder-name-disable:hover {
    color: #ccc;
    background-color: none;
}

.bootstrap .tree-actions {
    display: inline-block;
}

.bootstrap .tree-panel-heading-controls {
    padding: 5px;
    border-bottom: solid 1px #dfdfdf;
}

.bootstrap .tree-actions .twitter-typeahead {
    padding: 0 0 0 4px;
}

.bootstrap #categoryBox {
	padding: 10px 5px 5px 20px;
}

.bootstrap .tree-panel-label-title {
    font-weight: 400;
    margin: 0;
    padding: 0 0 0 8px;
}

.scrollbox > div {
	height: 23px;
}
</style>
<script type="text/javascript" src="view/javascript/jquery.total-storage.js"></script>
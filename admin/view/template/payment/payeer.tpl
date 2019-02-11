<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-liqpay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payment" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="payeer_status" id="input-status" class="form-control">
								<?php if ($payeer_status) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-url">
							<span data-toggle="tooltip" title="<?php echo $help_url; ?>"><?php echo $entry_url;?></span>
						</label>
						<div class="col-sm-10">
							<input type="text" name="payeer_url" value="<?php echo $payeer_url; ?>" id="input-url" class="form-control" />
							<?php if ($error_url) { ?>
								<div class="text-danger"><?php echo $error_url; ?></div>
							<?php } ?>
						</div>
					</div>
				  
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-merchant">
							<span data-toggle="tooltip" title="<?php echo $help_merchant; ?>"><?php echo $entry_merchant;?></span>
						</label>
						<div class="col-sm-10">
							<input type="text" name="payeer_merchant" value="<?php echo $payeer_merchant; ?>" id="input-merchant" class="form-control" />
							<?php if ($error_merchant) { ?>
								<div class="text-danger"><?php echo $error_merchant; ?></div>
							<?php } ?>
						</div>
					</div>
				  
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-security">
							<span data-toggle="tooltip" title="<?php echo $help_security; ?>"><?php echo $entry_security;?></span>
						</label>
						<div class="col-sm-10">
							<input type="text" name="payeer_security" value="<?php echo $payeer_security; ?>" placeholder="<?php echo $payeer_security; ?>" id="input-security" class="form-control" />
							<?php if ($error_security) { ?>
								<div class="text-danger"><?php echo $error_security; ?></div>
							<?php } ?>
						</div>
					</div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-log">
							<span data-toggle="tooltip" title="<?php echo $help_log; ?>"><?php echo $entry_log;?></span>
						</label>
						<div class="col-sm-10">
							<input type="text" name="payeer_log_value" value="<?php echo $payeer_log_value; ?>" id="input-log" class="form-control" />
						</div>
					</div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-list_ip">
							<span data-toggle="tooltip" title="<?php echo $help_list_ip; ?>"><?php echo $entry_list_ip;?></span>
						</label>
						<div class="col-sm-10">
							<input type="text" name="payeer_list_ip" value="<?php echo $payeer_list_ip; ?>" id="input-list_ip" class="form-control" />
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-admin_email">
							<span data-toggle="tooltip" title="<?php echo $help_admin_email; ?>"><?php echo $entry_admin_email;?></span>
						</label>
						<div class="col-sm-10">
							<input type="text" name="payeer_admin_email" value="<?php echo $payeer_admin_email; ?>" id="input-admin_email" class="form-control" />
						</div>
					</div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-order-wait"><?php echo $entry_order_wait; ?></label>
						<div class="col-sm-10">
							<select name="payeer_order_wait_id" id="input-order-wait" class="form-control">
							<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $payeer_order_wait_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
							</select>
						</div>
					</div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-order-success"><?php echo $entry_order_success; ?></label>
						<div class="col-sm-10">
							<select name="payeer_order_success_id" id="input-order-success" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $payeer_order_success_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-order-fail"><?php echo $entry_order_fail; ?></label>
						<div class="col-sm-10">
							<select name="payeer_order_fail_id" id="input-order-fail" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $payeer_order_fail_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
						<div class="col-sm-10">
							<select name="payeer_geo_zone_id" id="input-geo-zone" class="form-control">
								<option value="0"><?php echo $text_all_zones; ?></option>
								<?php foreach ($geo_zones as $geo_zone) { ?>
								<?php if ($geo_zone['geo_zone_id'] == $payeer_geo_zone_id) { ?>
									<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
				  
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
						<div class="col-sm-10">
							<input type="text" name="payeer_sort_order" value="<?php echo $payeer_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php echo $footer; ?>
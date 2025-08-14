<!DOCTYPE html>
<html>

<head>
	<title>E-Pesantren | SIM Pengelolaan Pesantren</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?= base_url() ?>b-asset/bs/css/bootstrap.css">
	<!-- Font Awesome -->
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/style.css">
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/frontend-style.css">
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/load-font-googleapis.css">

	<link rel="stylesheet" href="<?= base_url() ?>b-asset/vendors/owl-carousel/owl.carousel.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>b-asset/vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.css">

	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />

</head>

<body>
	<nav class="navbar navbar-default fixed">
		<div class="navbar-header">
			<a class="navbar-brand" href="#">
				<?php if (isset($setting_logo) and $setting_logo['setting_value'] != NULL) { ?>
					<img src="<?php echo upload_url('school/' . $setting_logo['setting_value']) ?>" style="height: 40px; margin-top: -10px;" class="pull-left">
				<?php } else { ?>
					<img src="<?php echo media_url('img/logo.svg') ?>" style="height: 40px; margin-top: -10px; margin-right: 5px;" class="pull-left">
				<?php } ?>
			</a>
			<font size="4">
				<b>
					E-Pesantren <?php echo $setting_school['setting_value'] ?>
				</b>
			</font>
			<br>
			<font size="2"><?php echo $setting_address['setting_value'] . ' ' . $setting_district['setting_value'] . ' - ' . $setting_city['setting_value'] . '<br> Telp. ' . $setting_phone['setting_value'] ?>
			</font>
			<!-- <button type="button" class="btn btn-default navbar-btn pull-right">Sign in</button> -->
		</div>
	</nav>

	<?php $this->load->view($main) ?>
	<?php $this->load->view('frontend/footer') ?>

	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="<?= base_url() ?>b-asset/bs/js/popper.js"></script>
	<script src="<?= base_url() ?>b-asset/bs/js/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>b-asset/vendors/owl-carousel/owl.carousel.min.js"></script>
	<script src="<?= base_url() ?>b-asset/vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.js"></script>
	<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
</body>

</html>
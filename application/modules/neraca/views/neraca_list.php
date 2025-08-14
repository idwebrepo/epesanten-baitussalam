<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header"> <br>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<select required="" name="majors_id" id="majors_id" class="form-control">
										<option value="">-- Pilih Unit --</option>
										<?php if ($this->session->userdata('umajorsid') == '0') { ?>
											<option value="all">Semua Unit</option>
										<?php } ?>
										<?php foreach ($majors as $row) { ?>
											<option value="<?php echo $row['majors_id']; ?>"><?php echo $row['majors_short_name'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="start" id="start" readonly="readonly" placeholder="Tanggal Awal">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="end" id="end" readonly="readonly" placeholder="Tanggal Awal">
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-primary" onclick="cari_data()">Filter</button>
						</div>

					</div>
					<div id="div_show_data">
						<div>
						</div>
					</div>

				</div>
	</section>
	<!-- /.content -->
</div>

<script>
	function cari_data() {
		var start = $("#start").val();
		var end = $("#end").val();
		var majors_id = $("#majors_id").val();
		//alert(id_jurusan+id_kelas);
		if (start != '' && end != '' && majors_id != '') {
			$.ajax({
				url: '<?php echo base_url(); ?>manage/neraca/get_neraca',
				type: 'POST',
				data: {
					'start': start,
					'end': end,
					'majors_id': majors_id,
				},
				success: function(msg) {
					$("#div_show_data").html(msg);
				},
				error: function(msg) {
					alert('msg');
				}

			});
		}
	}
</script>
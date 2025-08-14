<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-body">
						<div class="col-md-12 col-sm-12 col-xs-12 pull-left">
							<br>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-hover">
										<tbody>
											<tr>
												<td>ID Group Kegiatan</td>
												<td>:</td>
												<td><?php echo $group['group_id'] ?></td>
											</tr>
											<tr>
												<td>Nama Group</td>
												<td>:</td>
												<td><?php echo $group['group_name'] ?></td>
											</tr>
											<tr>
												<td>Nama Kegiatan</td>
												<td>:</td>
												<td><?php echo $group['kegiatan_name'] ?></td>
											</tr>
											<tr>
												<td>Tempat Kegiatan</td>
												<td>:</td>
												<td><?php echo $group['group_tempat'] ?></td>
											</tr>
											<tr>
												<td>Tanggal Kegiatan</td>
												<td>:</td>
												<td><?php echo pretty_date('d-m-Y', $group['group_date']); ?></td>
											</tr>
											<tr>
												<td>Keterangan</td>
												<td>:</td>
												<td><?php echo  $group['group_keterangan'] ?> </td>
											</tr>
										</tbody>
									</table>
									<br>
									<!-- <button type="submit" class="btn btn-block btn-success">Simpan</button> -->
									<a href="<?php echo site_url('manage/group'); ?>" class="btn btn-block btn-danger">Kembali</a>
									
									<!-- <a href="<?php echo site_url('manage/group/add_peserta?id_magang='.$group['group_id']) ?>" class="btn btn-sm btn-success right"><i class="fa fa-plus"></i> Tambah Peserta</a> -->
									<br>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="box">
										<div class="box-body">						
											<?php echo form_open(current_url(), array('method' => 'get')) ?>
											<div class="form-group">
												<div class="input-group">
													<div class="input-group-addon alert-info">Halaqoh</div>
													<div id="div_halaqoh">
													<select class="form-control" name="pr" onchange="this.form.submit()">
														<option value="">-- Pilih Halaqoh  --</option>
														<?php foreach ($halaqoh as $row): ?>
															<option <?php echo (isset($f['pr']) AND $f['pr'] == $row['halaqoh_id']) ? 'selected' : '' ?> value="<?php echo $row['halaqoh_id'] ?>"><?php echo $row['halaqoh_name'] ?></option>
														<?php endforeach; ?>
													</select>
													</div>
												</div>
											</div>
											<?php echo form_close() ?>
											
											<form action="<?php echo site_url('manage/group/multiple'); ?>" method="post" id="myform">
											<input type="hidden" name="group_id" id="halaqoh_id" value="<?php echo $group['group_id'] ?>">
													<table class="table table-hover table-bordered table-responsive">
														<tr>
															<th><input type="checkbox" id="check-all" value="checkbox" name="checkbox"></th> 
															<th>No</th>
															<th>NIS</th>
															<th>Nama</th>
															<th>Kelas</th>
														</tr>
														<tbody>
														<?php if($this->input->get(NULL)) { ?>
															<?php
															if (!empty($student)) {
																$i = 1;
																foreach ($student as $row):
																	?>
																	<tr style="<?php echo ($row['student_status']==0) ? 'color:#00E640' : '' ?>">
																		<td>
																			<input type="checkbox" class="<?php echo ($row['student_status']==0) ? NULL : 'checkbox' ?>" <?php echo ($row['student_status']==0) ? 'disabled' : NULL ?> name="student_id[]" value="<?php echo $row['student_id']; ?>">
																			<input type="hidden" name="halaqoh_id[]" id="halaqoh_id" value="<?php echo $row['halaqoh_id']; ?>">
																		</td>
																		<td><?php echo $i; ?></td>
																		<td><?php echo $row['student_nis']; ?></td>
																		<td><?php echo $row['student_full_name']; ?></td>	
																		<td><?php echo $row['class_name']; ?></td>	
						
																	</tr>
																	<?php
																	$i++;
																endforeach;
															} else {
																?>
																<tr id="row">
																	<td colspan="5" align="center">Data Kosong</td>
																</tr>
																<?php } ?>
																<?php } else {
																?>
																<tr id="row">
																	<td colspan="5" align="center">Data Kosong</td>
																</tr>
																<?php } ?>
															</tbody>
													</table>
												</div>
											</div>
										</div>
										</form>
										
										<form action="<?php echo site_url('manage/group/peserta'); ?>" method="post">						
										<div class="col-md-6">
											<div class="panel panel-info">
												<div class="panel-body">
													<?php if ($student_groups): ?>
													<table class="table table-hover table-bordered table-responsive">
														<thead>
														<tr>
															<th><input type="checkbox" id="check-all" value="checkbox" name="checkbox"></th> 
															<th>No</th>
															<th>NIS</th>
															<th>Nama Lengkap</th>
															<th>Kelas</th>
															<th>Nama Halaqoh</th>
														</tr>
														</thead>
														<tbody>
														<?php 
															$i = 1;
															foreach ($student_groups as $student_group): 
															?>
															<tr>
															<td>
																<input type="checkbox" class="<?php echo ($row['student_status']==0) ? NULL : 'checkbox' ?>" <?php echo ($row['student_status']==0) ? 'disabled' : NULL ?> name="student_id[]" value="<?php echo $row['student_id']; ?>">
																<input type="hidden" name="halaqoh_id[]" id="halaqoh_id" value="<?php echo $row['halaqoh_id']; ?>">
															</td>
															<td><?php echo $i; ?></td>
															<td><?php echo $student_group['student_nis']; ?></td>
															<td><?php echo $student_group['student_full_name']; ?></td>
															<td><?php echo $student_group['class_name']; ?></td>
															<td><?php echo $student_group['halaqoh_name']; ?></td>
															</tr>
														<?php 
															$i++;
															endforeach; 
														?>
														</tbody>
													</table>
													<?php else: ?>
													<p>Data tidak ditemukan.</p>
													<?php endif; ?>
													
												</div>
											</div>
										</div>
										</form>
									</div>
								</section>
								<!-- /.content -->
							</div>
							<!-- /.box-body -->
						</div>
					</div>
				</div>
			</div>
		</div>
				<!-- /.row -->

	</section>
</div>

<script>
	// Ambil semua checkbox di dalam form
	var checkboxes = document.getElementsByName('data[]');

	// Tambahkan event listener untuk setiap checkbox
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].addEventListener('change', function() {
			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'controller/save_data');
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send('data[]=' + this.value + '&checked=' + this.checked);
		});
	}
</script>
<script>
	$(document).ready(function() {
	// Check All checkbox
	$('#check-all').on('click', function() {
		$('.checkbox').prop('checked', this.checked);
	});

	// Single checkbox
	$('.checkbox').on('change', function() {
		if($('.checkbox:checked').length == $('.checkbox').length) {
		$('#check-all').prop('checked', true);
		} else {
		$('#check-all').prop('checked', false);
		}
	});

	// Submit form using AJAX
	$('#myform input').on('change', function() {
		$.ajax({
			url: $('#myform').attr('action'),
			type: 'post',
			data: $('#myform').serialize(),
			success: function(response) {
				console.log(response);
			}
		});
	});

	// Debounce function
	var debounce = null;
	$('#myform input').on('change', function() {
		if(debounce !== null) clearTimeout(debounce);
		debounce = setTimeout(function() {
			$('#myform').submit();
		}, 500);
	});
});

</script>
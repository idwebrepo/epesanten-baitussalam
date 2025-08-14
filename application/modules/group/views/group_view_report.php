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
								<div class="box box-success">
									<div class="box-body table-responsive">
										<table class="table table-responsive table-bordered" style="white-space: nowrap;">
												<thead>
												<tr>
													<th width='5' rowspan='2'>No.</th> 
													<th rowspan="2">Nis Santri</th> 
													<th rowspan="2">Nama Santri</th>
													<th colspan="4">Kehadiran</th> 
													<th rowspan="2">Keterangan</th>
												</tr>
												<tr>
													<th>H</th>
													<th>S</th>
													<th>I</th>
													<th>A</th>
												</tr>
												</thead>
												<tbody>
												<?php 
													$no = 1;
													foreach ($student as $row) :
												?>
													<tr>
														<td><?php echo $no++ ?><input type="hidden" name="student_id[]" value="<?php echo $row['student_id']?>"></td>
														<td><?php echo $row['student_nis']?></td> 
														<td><?php echo $row['student_full_name']?></td>
														<td><?php echo ($row['presensi_halaqoh_status']=='H'?'<b>V<b>' : '-' ) ?></td>
														<td><?php echo ($row['presensi_halaqoh_status']=='S'?'<b>V<b>' : '-' ) ?></td>
														<td><?php echo ($row['presensi_halaqoh_status']=='I'?'<b>V<b>' : '-' ) ?></td>
														<td><?php echo ($row['presensi_halaqoh_status']=='A'?'<b>V<b>' : '-' ) ?></td>
														<td>-</td>
													</tr>
            									<?php endforeach ?>
												</tbody>
										</table>
									</div>
									<!-- /.box-body -->
								</div>
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
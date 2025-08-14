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
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Filter Data Program</h3>
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="box-body table-responsive">
							<table id="dtable" class="table table-hover">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama Program</th>
										<th>Target</th>
										<th>Terkumpul</th>
										<th>Batas Donasi</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($program)) {
										$i = 1;
										foreach ($program as $row) :
									?>
											<tr>
												<td><?= $i ?></td>
												<td><?= $row['program_name'] ?></td>
												<td><?= 'Rp ' . number_format($row['program_target'], '0', ',', '.') ?></td>
												<td><?= 'Rp ' . number_format($row['program_earn'], '0', ',', '.') ?></td>
												<td><?= pretty_date($row['program_end'], 'd F Y', false) ?></td>
												<td align="center">
													<button type="button" data-dismiss="modal" class="btn btn-primary btn-xs" onclick="ambil_data(<?= $row['program_id'] ?>)">Pilih</button>
												</td>
											</tr>
										<?php
											$i++;
										endforeach;
									} else { ?>
										<tr id="row">
											<td colspan="8" align="center">Data Kosong</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div><!-- /.box-body -->
				</div><!-- /.box -->

				<?php if ($f) { ?>

					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">Informasi Program</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="col-md-9">
								<table class="table table-striped">
									<tbody>
										<tr>
											<td>Nama Program</td>
											<td>:</td>
											<?php echo (isset($f['r']) and $f['r'] == $donasi['program_id']) ?
												'<td>' . $row['program_name'] . '</td>' : '' ?>
										</tr>
										<tr>
											<td>Terbuka Sampai Tanggal</td>
											<td>:</td>
											<?php echo (isset($f['r']) and $f['r'] == $donasi['program_id']) ?
												'<td>' . pretty_date($row['program_end']) . '</td>' : '' ?>
										</tr>
										<tr>
											<td>Target</td>
											<td>:</td>
											<?php echo (isset($f['r']) and $f['r'] == $donasi['program_id']) ?
												'<td>Rp ' . number_format($row['program_target'], '0', ',', '.') . '</td>' : '' ?>
										</tr>
										<tr>
											<td>Terkumpul</td>
											<td>:</td>
											<?php echo (isset($f['r']) and $f['r'] == $donasi['program_id']) ?
												'<td>Rp ' . number_format($row['program_earn'], '0', ',', '.') . '</td>' : '' ?>
										</tr>
										<tr>
											<td>Kekurangan</td>
											<td>:</td>
											<?php echo (isset($f['r']) and $f['r'] == $donasi['program_id']) ?
												'<td>Rp ' . number_format($row['program_target'] - $row['program_earn'], '0', ',', '.') . '</td>' : '' ?>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-md-3">
								<?php if (isset($f['r']) and $f['r'] == $donasi['program_id']) { ?>
									<?php if (!empty($row['program_gambar'])) { ?>
										<img src="<?php echo upload_url('program/' . $row['program_gambar']) ?>" class="img-thumbnail img-responsive">
								<?php }
								} ?>
							</div>
						</div>
					</div>

					<!-- List Tagihan Bulanan -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Transaksi Donasi</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="box-body">
								<button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#addDonasi"><i class="fa fa-plus"></i> Donasi</button>
							</div>
							<div class="box-body table-responsive">
								<table class="table table-bordered" style="white-space: nowrap;">
									<thead>
										<tr class="info">
											<th>No.</th>
											<th>Tanggal</th>
											<th>Nama</th>
											<th>Email</th>
											<th>No. HP/WA</th>
											<th>Nominal</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;
										foreach ($list_donasi as $row) :
										?>
											<tr>
												<td><?php echo $i ?></td>
												<td><?php echo pretty_date($row['donasi_datetime'], 'd F Y H:i:s', false) ?></td>
												<td><?php echo $row['donasi_name'] ?></td>
												<td><?php echo $row['donasi_email'] ?></td>
												<td><?php echo $row['donasi_hp'] ?></td>
												<td>Rp <?php echo number_format($row['donasi_nominal'], 0, ',', '.') ?></td>
												<td>#</td>
											</tr>
										<?php
											$i++;
										endforeach;
										?>
									</tbody>
								</table>
							</div>
						<?php } ?>
						</div>
					</div>
			</div>
		</div>
	</section>
</div>

<!-- Modal -->
<div class="modal fade" id="addDonasi" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Data Donasi Donatur</h4>
			</div>
			<?php echo form_open('manage/donasi/create_donasi', array('method' => 'post')); ?>
			<div class="modal-body">
				<div class="row">
					<input type="hidden" name="program_program_id" class="form-control" value="<?= $donasi['program_id'] ?>">
					<input type="hidden" name="program_name" class="form-control" value="<?= $donasi['program_name'] ?>">
					<div class="col-md-12">
						<div class="form-group">
							<label for="">Nama Donatur <small>*</small></label>
							<input type="text" required="" name="donasi_name" class="form-control" placeholder="Masukkan Nama Donatur">
						</div>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="Hamba Allah" name="as_anonim" id="as_anonim">
							<label class="form-check-label" for="as_anonim" style="color:lightslategray">
								Donasi Sebagai Hamba Allah
							</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group"><label for="">No. Whatsapp <small>*</small></label>
							<input type="text" required="" name="donasi_hp" class="form-control" placeholder="Masukkan No. Whatsapp Donatur">
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group"><label for="">Email</label>
							<input type="email" name="donasi_email" class="form-control" placeholder="Masukkan No. Email Donatur">
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group"><label for="">Nominal Donasi <small>*</small></label>
							<input type="number" name="donasi_nominal" class="form-control" placeholder="Masukkan Nominal Donasi">
						</div>
						<p>*) Nominal Donasi Minimal Rp 10.000</p>
					</div>

					<div class="col-md-12">
						<label>Akun Penerimaan *</label>
						<select required="" name="donasi_account_id" id="donasi_account_id" class="form-control">
							<option value="">-- Pilih Akun Penerimaan --</option>
							<?php
							foreach ($dataKas as $row) {
							?>
								<option value="<?php echo $row['account_id'] ?>" <?php echo ($dataKasActive == $row['account_id']) ? 'selected' : '' ?>>
									<?php echo $row['account_code'] . ' - ' . $row['account_description'];
									?>
								</option>
							<?php
							}
							?>
						</select>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success">Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script>
	function ambil_data(data) {

		window.location.href = '<?php echo base_url(); ?>manage/donasi?r=' + data;
	}
</script>
<style>
	div.over {
		width: 425px;
		height: 165px;
		overflow: scroll;
	}

	div.extended {
		width: 900px;
		height: 200px;
		overflow: scroll;
	}
</style>
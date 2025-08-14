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
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<div class="row">
							<div class="col-md-9">
								<?php echo form_open(current_url(), array('class' => 'form-horizontal', 'method' => 'get')) ?>

								<table>
									<tr>
										<td>
											<select style="width: 150px;" id="t" name="t" class="form-control" required>
												<option value="" <?php echo (isset($s['t']) && $s['t'] == '') ? 'selected' : '' ?>> Pilih Transaksi </option>
												<option value="pay" <?php echo (isset($s['t']) && $s['t'] == 'pay') ? 'selected' : '' ?>>Pembayaran</option>
												<option value="bank" <?php echo (isset($s['t']) && $s['t'] == 'bank') ? 'selected' : '' ?>>Tabungan</option>
												<option value="donation" <?php echo (isset($s['t']) && $s['t'] == 'donation') ? 'selected' : '' ?>>Donasi</option>
												<option value="homestay" <?php echo (isset($s['t']) && $s['t'] == 'homestay') ? 'selected' : '' ?>>Penginapan</option>
											</select>
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											<div style="width: 150px;" class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												<input class="form-control" type="text" name="start" id="start" readonly="readonly" placeholder="Tanggal Awal" value="<?= isset($s['start']) ? $s['start'] : date('Y-m-d') ?>" required>
											</div>
										</td>
										<td>
											<div style="width: 150px;" class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												<input class="form-control" type="text" name="end" id="end" readonly="readonly" placeholder="Tanggal Akhir" value="<?= isset($s['end']) ? $s['end'] : date('Y-m-d') ?>" required>
											</div>
										</td>
										<td>
											<select style="width: 150px;" id="m" name="m" class="form-control" required onchange="get_kelas()">
												<option value=""> Pilih Unit </option>
												<?php foreach ($majors as $row) { ?>
													<option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
												<?php } ?>
											</select>
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td id="td_kelas">
											<select style="width: 150px;" id="c" name="c" class="form-control" required>
												<option value=""> Pilih Kelas </option>
												<?php if (isset($s['m'])) { ?>
													<option value="all" <?php echo (isset($s['c']) && $s['c'] == 'all') ? 'selected' : '' ?>>Semua Kelas</option>
													<?php foreach ($class as $row) { ?>
														<option value="<?php echo $row['class_id']; ?>" <?php echo (isset($s['c']) && $s['c'] == $row['class_id']) ? 'selected' : '' ?>><?php echo $row['class_name'] ?></option>
													<?php } ?>
											</select>
										<?php } ?>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											<select style="width: 150px;" id="s" name="s" class="form-control" required>
												<option value="" <?php echo (isset($s['s']) && $s['s'] == '') ? 'selected' : '' ?>> Pilih Status </option>
												<option value="all" <?php echo (isset($s['s']) && $s['s'] == 'all') ? 'selected' : '' ?>>Semua Status</option>
												<option value="PENDING" <?php echo (isset($s['s']) && $s['s'] == 'PENDING') ? 'selected' : '' ?>>Pending</option>
												<option value="SUCCESSFUL" <?php echo (isset($s['s']) && $s['s'] == 'SUCCESSFUL') ? 'selected' : '' ?>>Berhasil</option>
											</select>
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
										</td>
									</tr>
								</table>
								<?php echo form_close(); ?>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body table-responsive">
							<table id="dtable" class="table table-hover">
								<thead>
									<tr>
										<th>No</th>
										<th>Tanggal</th>
										<th>NIS</th>
										<th>Nama</th>
										<th>Kelas</th>
										<th>Bank</th>
										<th>Nomor VA</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($trxs)) {
										$i = 1;
										foreach ($trxs as $row):

									?>
											<tr>
												<td><?php echo $i; ?></td>
												<td><?php echo pretty_date($row['tanggal'], 'd-m-Y H:i:s', false); ?></td>
												<td><?php echo $row['student_nis']; ?></td>
												<td><?php echo $row['student_full_name']; ?></td>
												<td><?php echo $row['majors_short_name'] . ' - ' . $row['class_name']; ?></td>
												<td><?php echo $row['kode']; ?></td>
												<td><?php echo $row['va_no']; ?></td>
												<td><label class="label <?php if ($row['status'] == 'SUCCESSFUL') {
																			echo 'label-success';
																		} else if ($row['status'] == 'PENDING') {
																			echo 'label-warning';
																		} else if ($row['status'] == 'CANCELED') {
																			echo 'label-danger';
																		} ?>">
														<?php echo str_replace('SUCCESSFUL', 'BERHASIL', $row['status']) ?>
													</label>
												</td>
											</tr>

										<?php
											$i++;
										endforeach;
									} else {
										?>
										<tr id="row">
											<td colspan="6" align="center">Data Kosong</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
			</form>
	</section>
	<!-- /.content -->
</div>

<script>
	function get_kelas() {
		var id_majors = $("#m").val();
		//alert(id_jurusan+id_kelas);
		$.ajax({
			url: '<?php echo base_url(); ?>manage/flip_trx/class_searching',
			type: 'POST',
			data: {
				'id_majors': id_majors,
			},
			success: function(msg) {
				$("#td_kelas").html(msg);
			},
			error: function(msg) {
				alert('msg');
			}

		});
	}
</script>